<?php
session_start();
require 'config/config.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_SESSION['csrf_token']) && ($_POST['csrf_token'] === $_SESSION['csrf_token'])){
        if(isset($_POST['login'])){
            $member_email = filter_var($_POST['member_email'], FILTER_SANITIZE_EMAIL);
            if(!filter_var($_POST['member_email'], FILTER_VALIDATE_EMAIL)){
                echo 'emailNotValid';
                exit;
            }

            $stmt = $conn->prepare("select * from members_info where email = ?");
            $stmt->bind_param("s", $member_email);
            $stmt->execute();
            $rows = $stmt->get_result()->fetch_assoc();
            if($rows){
               $_SESSION['member_email'] = $member_email;
               $_SESSION['member_id'] = $rows['id'];
               $_SESSION['member_name'] = $rows['name'];
               $_SESSION['position'] = $rows['position'];
               $_SESSION['set_amount'] = $rows['set_amount'];
               $_SESSION['deposit_amount'] = $rows['deposit_amount'];
               $_SESSION['joining_date'] = $rows['joining_date'];
               $_SESSION['due_amount'] = $rows['due_amount'];
               $_SESSION['due_month'] = $rows['due_month'];
               echo 'emailFound';
               $_SESSION['otp_required'] = true;
            } else{
               echo 'emailNotFound';
            }
        }
        
        if(isset($_POST['otp_verify'])){
            $submit_otp = $_POST['submit_otp'];
            $sent_otp = $_SESSION['otp'];
            if($submit_otp === $sent_otp){
                if(time() < $_SESSION['otp_expiry']){
                    $_SESSION['login_success'] = true;
                    echo 'loginSuccess';
                    unset($_SESSION['otp_sent']);
                    unset($_SESSION['csrf_token']);
                } else echo 'otpExpired';
                
            } else{
                echo 'wrongOtp';
            }         
        }
        
    } else{
        header('location: 404.php');
        exit();
    }
} else{
    header('location: 404.php');
    exit();
}

if(isset($stmt)){
    $stmt->close();
}
$conn->close();
?>