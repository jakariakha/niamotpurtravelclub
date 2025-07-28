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
<h2 class="text-center" style="margin-top: 30px;">Transactions History</h2>
<div class="border border-info" style="padding: 20px; margin-top: 25px; width: 100%;">
            <div class="panel">
                <div class="table-responsive  text-center">
                    <table class="table">   
                        <tr>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                            <th scope="col">Transaction ID</th>
                            <th scope="col">Payment method</th>                          
                            <th scope="col">Date & Time</th>
                        </tr>
                        <tbody class="table-group-divider">
                            <?php 
                            $member_uid = $_SESSION['member_uid'];
                            $stmt = $conn->prepare("SELECT * FROM transactions WHERE member_uid = ? order by id desc");
                            $stmt->bind_param("s", $member_uid);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows > 0){                              
                                while($rows = $result->fetch_assoc()){
                                    echo '<tr>';
                                    echo '<td>'.$rows['amount'].'</td>';
                                    if ($rows['payment_status'] == 'Deposited') {
                                        echo '<td><span class="badge bg-custom-deposit text-black p-2"><i class="bi bi-plus-circle"></i>' . $rows['payment_status'] . '</span></td>';
                                      } 
                                    echo '<td>'.$rows['tran_id'].'</td>';
                                    echo '<td>'.$rows['payment_method'].'</td>';
                                    $date = new DateTime($rows['tran_date']);
                                    echo '<td>' . $date->format('d-m-Y g:i:A') . '</td>';
                                    $no_record_found = false;
                                } 
                            } else{
                                $no_record_found = true;
                            }                                                                             
                           ?>
                        </tbody>
                    </table>
                    <?php
                        if($no_record_found){
                            echo 'No record found.';     
                        }
                        if(isset($stmt)){
                            $stmt->close();
                        }
                        $conn->close();
                    ?>
                </div>
            </div>
        </div>