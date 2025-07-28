<?php
include 'cdn.php';
if(!defined('APP_STARTED')){
    header('location: 404.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="assets/css/navbar.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">NTC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php"><i class="bi bi-house"></i>Home</a>
                    </li>
                    <?php if(isset($_SESSION['position']) && $_SESSION['position'] == 'President' && $_SESSION['position'] == 'Cashier'): ?>
                        <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i>Dashboard</a>
                        </li>
                    <?php endif ?>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php"><i class="bi bi-person"></i>Profile</a>
                    </li>
                    <?php if(isset($_SESSION['position']) && $_SESSION['position'] == 'President'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="add_member.php"><i class="bi bi-person-add"></i>Add Member</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="fetch_members_info.php"><i class="bi bi-people"></i>Manage Members</a>                   
                    </li>
                    <?php endif ?>
                    <li class="nav-item">
                        <a class="nav-link" href="all_members.php" style="color: white;">
                        <i class="material-icons icon" style="vertical-align: middle;">groups</i> 
                            <span style="vertical-align: middle;">All Members</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="transaction_history.php"><i class="bi bi-hourglass-split"></i>Transactions History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-left"></i>Log Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>
