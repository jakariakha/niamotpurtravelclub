<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set('Asia/Dhaka');
require 'vendor/autoload.php';
include 'config/config.php';

session_start();

if(!(isset($_SESSION['otp_required']) && $_SERVER['REQUEST_METHOD'] == 'POST')){
    header('location: 404.php');
}

if(isset($_POST['send_otp'])){
    $member_email = $_POST['member_email'];
    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }
    
    $otp = generateRandomString(8);

    $_SESSION['otp'] = $otp;

    // Send OTP via email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = MAIL_PORT;

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($member_email);

        // Set email format to HTML
        $mail->isHTML(true);

        // HTML Body
        $htmlContent = '
    <html>
    <head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #12c2e9, #c471ed, #f64f59);
            padding: 20px;
            text-align: center;
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
        }
        .email-body {
            padding: 40px;
            text-align: center;
            color: #333333;
        }
        .email-body h2 {
            font-size: 22px;
            margin-bottom: 20px;
            font-weight: normal;
        }
        .email-body p {
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        .otp-code {
            display: inline-block;
            background-color: #f7f7f7;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #3a6073;
        }
        .email-footer {
            padding: 20px;
            background-color: #f7f7f7;
            text-align: center;
            border-top: 1px solid #e6e6e6;
        }
        .email-footer p {
            font-size: 14px;
            color: #555555;
            margin: 5px 0;
        }
        .email-footer a {
            color: #3a6073;
            text-decoration: none;
            font-weight: bold;
        }
        .email-footer a:hover {
            text-decoration: underline;
        }
        .developed-by {
            margin-top: 15px;
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            Niamotpur Travel Club
        </div>
        <div class="email-body">
            <h2>Your login verification code:</h2>
            <div class="otp-code">' . $otp . '</div>
            <p>This verification code is valid for the next 5 minutes. If you did not request this otp, please ignore this email.</p>
        </div>
        <div class="email-footer">
            <p>This is an automated message, please do not reply.</p>
            <p>&copy; 2023-' . date('Y') . ' Niamotpur Travel Club (NTC). All rights reserved.</p>
        </div>
    </div>
</body>
</html>
';

        // Set the email subject and body
        $mail->Subject = '[NTC] Login Verification Code';
        $mail->Body    = $htmlContent;

        if($mail->send()){
            $otpExpiryTime = time() + 300;
            $_SESSION['otp_expiry'] = time() + 300;
            $_SESSION['otp_sent'] = true;
            echo 'otpSent';
            unset($_SESSION['otp_required']);
        } else{
            echo 'otpNotSent';
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to send verification code. Please try again.";
    } 
}

?>
