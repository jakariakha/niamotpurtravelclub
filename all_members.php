<?php 
session_start();
include 'config/config.php';
define('APP_STARTED', true);
include 'navbar.php';
require 'auth_check.php';
?>
<html>
    <head>
        <link rel="stylesheet" href="assets/css/transactions.css">
    </head>
</html>
<h2 class="text-center" style="margin-top: 30px;">All Members of NTC</h2>
<div class="border border-info" style="padding: 20px; margin-top: 25px; width: 100%;">
            <div class="panel">
                <div class="table-responsive  text-center">
                    <table class="table">   
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Balance</th>
                            <th scope="col">Total Due</th>
                            <th scope="col">Not Pay</th>                          
                            <th scope="col">Last Pay</th>
                        </tr>
                        <tbody class="table-group-divider">
                            <?php 
                            $email = $_SESSION['member_email'];
                            $stmt = $conn->prepare("SELECT * FROM members_info");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows > 0){                              
                                while($rows = $result->fetch_assoc()){
                                    echo '<tr>';
                                    echo '<td>'.$rows['name'].'</td>';
                                    echo '<td>'.$rows['deposit_amount'].' Taka</td>';
                                    echo '<td>'.$rows['due_amount'].' Taka</td>';
                                    echo '<td>'.$rows['due_month'].' Month</td>';
                                    $stmt_fetch_last_pay = $conn->prepare("SELECT tran_date FROM transactions WHERE member_uid = ? order by id desc limit 5");
                                    $stmt_fetch_last_pay->bind_param("s", $_SESSION['member_uid']);
                                    $stmt_fetch_last_pay->execute();
                                    $last_pay_result = $stmt_fetch_last_pay->get_result();
                                    if($last_pay_result->num_rows > 0){
                                        $row = $last_pay_result->fetch_assoc();
                                        $date = new DateTime($row['tran_date']);
                                        echo '<td>'.$date->format('d-m-Y g:i:A').'</td>';
                                    } else{
                                        echo '<td>Not Found</td>';
                                    }                                 
                                } 
                            } 
                            if(isset($stmt)){
                                $stmt->close();
                            }
                            $conn->close();                                                                               
                           ?>
                        </tbody>
                    </table>
                </div>
            </div>
</div>