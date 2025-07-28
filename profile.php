<?php
session_start();
define('APP_STARTED', true);
include 'navbar.php';
include 'config/config.php';
require 'auth_check.php';

if(isset($_SESSION['member_id'])){
    $member_id = $_SESSION['member_id'];
    $stmt = $conn->prepare("SELECT * FROM members_info WHERE id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_assoc();
    $_SESSION['updated_name'] = $rows['name'];
    $_SESSION['updated_email'] = $rows['email'];
    $_SESSION['updated_member_uid'] = $rows['member_uid'];
    $_SESSION['updated_position'] = $rows['position'];
    
    if(isset($stmt)){
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NTC Profile</title>
    <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body>
    <div class="container d-flex flex-column justify-content-center align-items-center vh-100 pt-100">
        <div class="card mb-3" style="width: 70%;">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-person-circle icon-placeholder"></i>
                </div>
                <h6 class="card-subtitle mb-2">UID: <?php echo $rows['member_uid'];?></h6>
                <h5 class="card-title mb-1"><?php echo $rows['name'];?></h5>
                <h6 class="card-subtitle mb-2" style="color: white;"><?php echo $rows['position'];?></h6>
                <p>Email: <?php echo $rows['email']; ?></p>
                <div class="joined">
                    <p>Joined <?php $date = new DateTime($rows['joining_date']); echo $date->format('F Y')  ?> </p>
                </div>
            </div>
        </div>
        <a href="edit_profile.php" class="btn btn-custom">Edit Profile</a>
    </div>
</body>
</html>
