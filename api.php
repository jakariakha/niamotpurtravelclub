<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
//header("Access-Control-Allow-Origin: https://khapayplus.live");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set('Asia/Dhaka');
require 'vendor/autoload.php';
include 'config/config.php';

function sendAddMoneyConfirmation($member_email, $member_name, $deposit_amount, $payment_method){
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
            .single-line { /* Ensures text stays on a single line */
                white-space: nowrap;
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
            <p class="single-line">Your deposit of <strong>'.$deposit_amount.'</strong> Taka via <strong>'.$payment_method.' </strong> has been successfully completed.<br>Thank you!</p>
            <p>Best regards,<br> Niamotpur Travel Club</p>
        </div>
        <div class="email-footer">
            <p>This is an automated message, please do not reply.</p>
            <p>&copy; 2023-'.date('Y').' Niamotpur Travel Club (NTC). All rights reserved.</p>
        </div>
    </div>
</body>
</html>';

        // Set the email subject and body
        $mail->Subject = '[NTC] Deposit Confirmation';
        $mail->Body = $htmlContent;
        if($mail->send()){
            return true;
        } else{
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents("php://input"), true);
    if(isset($data['email']) && isset($data['api_key'])){
        $stmt = $conn->prepare("SELECT * FROM email_and_api WHERE email = ? AND api_key = ?");
        $stmt->bind_param("ss", $data['email'], $data['api_key']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows){
            if(isset($data['member_uid']) && isset($data['deposit_amount']) && isset($data['payment_method']) && isset($data['tran_id'])){
                $member_uid = filter_var($data['member_uid'], FILTER_VALIDATE_INT);
                $deposit_amount = filter_var($data['deposit_amount'], FILTER_VALIDATE_INT);
                $payment_method = $data['payment_method'];
                $tran_id = htmlspecialchars($data['tran_id'], ENT_QUOTES, 'UTF-8');
                $tran_date = date('Y-m-d H:i:s');
                $payment_status = "Deposited";

                $stmt_info = $conn->prepare("SELECT * FROM members_info WHERE member_uid = ?");
                $stmt_info->bind_param("i", $member_uid);
                $stmt_info->execute();
                $info_result = $stmt_info->get_result();
                if($info_result->num_rows){
                    $info_rows = $info_result->fetch_assoc();
                    $member_email = $info_rows['email'];
                    $member_name = $info_rows['name'];
                    $current_balance = $info_rows['deposit_amount'];
                    $set_amount = $info_rows['set_amount'];
                    $due_amount = $info_rows['due_amount'];
                    $due_month = $info_rows['due_month'];
        
                     //update member due
                    $total_due_amount = $due_amount - $deposit_amount;
                    $total_due_month = $due_month - ($deposit_amount / $set_amount);            
                    $total_amount = $deposit_amount + $current_balance;
                    $stmt = $conn->prepare("UPDATE members_info SET deposit_amount = ?, due_amount = ?, due_month = ? WHERE member_uid = ?");
                    $stmt->bind_param("iiii", $total_amount, $total_due_amount, $total_due_month, $member_uid);
        
                    //insert transaction info
                    $tran_date = date('Y-m-d H:i:s');
                    $stmt_tran = $conn->prepare("INSERT INTO transactions(member_uid, amount, tran_id, payment_method, payment_status, tran_date) VALUES(?, ?, ?, ?, ?, ?)");
                    $stmt_tran->bind_param("iissss", $member_uid, $deposit_amount, $tran_id, $payment_method, $payment_status, $tran_date);
        
                    if($stmt->execute() && $stmt_tran->execute()){
                       $status = sendAddMoneyConfirmation($member_email, $member_name, $deposit_amount, $payment_method);
                       if($status){
                            echo json_encode(['message' => 'success']);
                       } else{
                        echo json_encode(['error' => 'failed']);
                       }
                    } else{
                        echo json_encode(['error' => 'failed']);
                    }       
                } else{
                    echo json_encode(['error' => 'member not found']);
                }
            } else{
                echo json_encode(['error' => 'request invalid!']);
            }
        } else{
            echo json_encode(['error' => 'email or api key invalid!']);
            exit();
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
