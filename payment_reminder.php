<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require 'config/config.php';

$stmt = $conn->prepare("SELECT * FROM members_info");
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){ 
    while($rows = $result->fetch_assoc()){
        $member_email = $rows['email'];
        $member_name = $rows['name'];
        $set_amount = $rows['set_amount'];
        $due_amount = $rows['due_amount'];

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
        $htmlContent =        
'<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Reminder</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
            }
            .email-container {
                max-width: 600px;
                margin: 20px auto;
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
                text-align: left; /* Changed to left for better readability */
                color: #333333;
            }
            .email-body p {
                margin: 15px 0; /* Uniform margin for paragraphs */
                line-height: 1.5; /* Improved line height for readability */
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
        </style>
    </head>
<body>
    <div class="email-container">
        <div class="email-header">
            Niamotpur Travel Club
        </div>
        <div class="email-body">
            <p class="hello-user">Hello '.$member_name.',</p>
            <p>We kindly remind you to pay <strong>'.$set_amount.'</strong> Taka within the next 10 days. Also your due is <strong>'.$due_amount.'</strong> Taka, which we request you to pay as soon as possible.</p>
            <p>Best regards,</p>
            <p>Niamotpur Travel Club</p>
        </div>
        <div class="email-footer">
            <p>This is an automated message, please do not reply.</p>
            <p>&copy; 2023-'.date('Y').' Niamotpur Travel Club (NTC). All rights reserved.</p>
        </div>
    </div>
</body>
</html>
';

        // Set the email subject and body
        $mail->Subject = '[NTC] Payment Reminder';
        $mail->Body    = $htmlContent;
        $mail->send();
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to send verification code. Please try again.";
    }

    
}
}
   


?>
