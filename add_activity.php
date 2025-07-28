<?php
session_start();
include 'config/config.php';

if(!(isset($_SESSION['position']) && $_SESSION['position'] == 'President')){
    header('location: index.php');
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_SESSION['csrf_token']) && ($_POST['csrf_token'] == $_SESSION['csrf_token'])){
        if(isset($_POST['add_new_member'])){
            $member_name = mysqli_real_escape_string($conn, $_POST['new_member_name']);
            $member_email = mysqli_real_escape_string($conn, $_POST['new_member_email']);
            $member_uid = mysqli_real_escape_string($conn, $_POST['new_member_uid']);
            $deposit_amount = mysqli_real_escape_string($conn, $_POST['new_member_deposit_amount']);
            $set_amount = mysqli_real_escape_string($conn, $_POST['new_member_set_amount']);
            $position = mysqli_real_escape_string($conn, $_POST['new_member_position']);
            $joining_date = mysqli_real_escape_string($conn, $_POST['new_member_joining_date']);
            $due_amount = mysqli_real_escape_string($conn, $_POST['new_member_due_amount']);
            $due_month = mysqli_real_escape_string($conn, $_POST['new_member_due_month']);
        
            $stmt = $conn->prepare("SELECT * from members_info WHERE email = ?");
            $stmt->bind_param("s", $member_email);
            $stmt->execute();
        
            if($stmt->get_result()->num_rows > 0){
                echo 'emailExists';
                
            } else {
                $stmt = $conn->prepare("INSERT INTO members_info (name, email, member_uid, deposit_amount, position, set_amount, joining_date, due_amount, due_month) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssiisssss", $member_name, $member_email, $member_uid, $deposit_amount, $position, $set_amount, $joining_date, $due_amount, $due_month);
                
                if($stmt->execute()){
                    $_SESSION['new_member_email'] = $member_email;
                    $_SESSION['insert_deposit_amount'] = $deposit_amount;
                    $_SESSION['insert_set_amount'] = $set_amount;
                    $_SESSION['insert_joining_date'] = $joining_date;
                    $_SESSION['user_data_inserted'] = true;
                    echo 'memberInserted';
                    unset($_SESSION['csrf_token']);
                } else {
                    echo 'memberNotInserted';
                }
                
            }
        }
    } else{
        header('location: 404.php');
    }

} else{
    header('location: 404.php');
}

if(isset($stmt)){
    $stmt->close();
}
$conn->close();
?>