<?php
date_default_timezone_set('Asia/Dhaka');
session_start();

define('APP_STARTED', true);
include 'navbar.php';
include 'config/config.php';
require 'auth_check.php';

$member_email = $_SESSION['member_email'];
$stmt = $conn->prepare("SELECT * FROM members_info WHERE email = ?");
$stmt->bind_param("s", $member_email);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$_SESSION['member_uid'] = $result['member_uid'];
$_SESSION['deposit_amount'] = $result['deposit_amount'];
$_SESSION['due_amount'] = $result['due_amount'];
$_SESSION['due_month'] = $result['due_month'];
$sql = "select sum(deposit_amount)  as total_amount from members_info";
$result = mysqli_query($conn, $sql);
$rows = mysqli_fetch_assoc($result);
if($rows['total_amount']){
    $_SESSION['ntc_balance'] = $rows['total_amount'];
}
?>

<html>
    <head>
     <link rel="stylesheet" href="assets/css/transactions.css">
    </head>
    <body>
        <div class="row">
            <div class="col-sm-3 pt-5">
                <div class="card" style="background: linear-gradient(135deg, #12c2e9, #c471ed, #f64f59); padding: 20px; margin-top: -40px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text text-white" style="margin-bottom: 0;">Your Balance</p>
                            <h3 class="card-text text-white" style="margin-top:0;">৳<?php echo $_SESSION['deposit_amount'];?></h3>
                            
                        </div>
                        <i class="bi bi-wallet2" style="font-size: 2rem; color: white;"></i>
                    </div>
                </div>
            </div>


            <div class="col-sm-3 pt-5">
                <div class="card" style="background: linear-gradient(135deg, #12c2e9, #c471ed, #f64f59); padding: 20px; margin-top: -40px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text text-white" style="margin-bottom: 0;">Total Due</p>
                            <h3 class="card-text text-white" style="margin-top: 0;">৳<?php echo $_SESSION['due_amount'];?></h3>
                        </div>
                        <i class="material-icons icon" style="font-size: 2rem; color: white;">schedule</i>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-3 pt-5">                
                <div class="card" style="background: linear-gradient(135deg, #12c2e9, #c471ed, #f64f59); padding: 20px; margin-top: -40px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text text-white" style="margin-bottom: 0;">Not Pay</p>
                            <h3 class="card-text text-white" style="margin-top: 0;"><?php if(isset($_SESSION['due_month'])){echo $_SESSION['due_month'];}?> Month</h3>
                        </div>
                        <i class="material-icons icon" style="font-size: 2rem; color: white;">more_time</i>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 pt-5">
                <div class="card" style="background: linear-gradient(135deg, #12c2e9, #c471ed, #f64f59); padding: 20px; margin-top: -40px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-text text-white" style="margin-bottom: 0;">NTC Balance</p>
                            <h3 class="card-text text-white" style="margin-top: 0;">৳<?php echo $_SESSION['ntc_balance'];?></h3>
                        </div>
                        <i class="bi bi-graph-up-arrow" style="font-size: 2rem; color: white;"></i>

                    </div>
                </div>
            </div>
        </div>
        <h2 class="text-center" style="margin-top: 30px;">Last Five Transaction</h2>
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
                            $stmt = $conn->prepare("select * from transactions where member_uid = ? order by id desc limit 5");
                            $stmt->bind_param("s",$member_uid);
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
                                } 
                                $no_record_found = false;
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
</body>
</html>

