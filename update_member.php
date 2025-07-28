<?php
session_start();
define('APP_STARTED', true);
include 'config/config.php';
include 'navbar.php';
require 'auth_check.php';
if(!(isset($_SESSION['position']) && $_SESSION['position'] == 'President')){
    header('location: index.php');
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "select * from members_info where id = '$id'";
    $result = mysqli_query($conn, $sql);
    $rows = mysqli_fetch_assoc($result);
    $member_name = $rows['name'];
    $member_email = $rows['email'];
    $member_uid = $rows['member_uid'];
    $deposit_amount = $rows['deposit_amount'];
    $position = $rows['position'];
    $due_amount = $rows['due_amount'];
    $due_month = $rows['due_month'];
    $set_amount = $rows['set_amount'];
    $joining_date = $rows['joining_date'];
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
    <title>NTC-Update Member Info</title>
    <link rel="stylesheet" href="assets/css/toast.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100" style="padding-bottom: 200px; padding-top: 50px;">
        <div class="card p-5" style="max-width: 500px; width: 100%; min-height: 400px; border-radius: 20px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);">
            <div class="payment-content text-center">
                <h2 class="mb-4">Update Member</h2>
                <form action="update.php" method="post">
                    <input type="hidden" id="csrfToken" value="<?php echo $_SESSION['csrf_token'];?>">
                <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="member_id" id="memberId" value="<?php echo $id;?>" placeholder="ID" required style="border-radius: 50px 50px 50px;">
                            <label for="id">ID</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="member_name" id="memberName" value="<?php echo $member_name;?>" placeholder="Name" required style="border-radius: 50px 50px 50px;">
                            <label for="name">Name</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                        <input type="hidden" class="form-control" name="member_email" id="memberOldEmail" value="<?php echo $member_email;?>" placeholder="Email" required style="border-radius: 50px 50px 50px;">
                            <input type="email" class="form-control" name="member_email" id="memberEmail" value="<?php echo $member_email;?>" placeholder="Email" required style="border-radius: 50px 50px 50px;">
                            <label for="email">Email</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="member_uid" id="memberUid" value="<?php echo $member_uid;?>" placeholder="Member UID" required style="border-radius: 50px 50px 50px;">
                            <label for="member_uid">UID</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="deposit_amount" id="memberDepositAmount" value="<?php echo $deposit_amount;?>" placeholder="Deposit Amount" required style="border-radius: 50px 50px 50px;">
                            <label for="balance">Deposit Amount</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="set_amount" id="memberSetAmount" value="<?php echo $set_amount;?>" placeholder="Deposit Amount" required style="border-radius: 50px 50px 50px;">
                            <label for="set_amount">Set Amount</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="due_amount" id="memberDueAmount" value="<?php echo $due_amount;?>" placeholder="Due Amount" required style="border-radius: 50px 50px 50px;">
                            <label for="due-amount">Due Amount</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="due_month" id="memberDueMonth" value="<?php echo $due_month;?>" placeholder="Due Month" required style="border-radius: 50px 50px 50px;">
                            <label for="due-month">Due Month</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="joining_date" id="memberJoiningDate" value="<?php echo $joining_date;?>" placeholder="Joining Date" required style="border-radius: 50px 50px 50px;">
                            <label for="joining-date">Joining Date</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <select class="form-select" name="position" id="memberPosition" aria-label="Default select example" required style="border-radius: 50px 50px 50px; padding: 20px; padding-right: 50px;">
                                <option>Select Position</option>
                                <option value="President" <?php if ($position == 'President') echo 'selected'; ?>>President</option>
                                <option value="Cashier" <?php if ($position == 'Cashier') echo 'selected'; ?>>Cashier</option>
                                <option value="Member" <?php if ($position == 'Member') echo 'selected'; ?>>Member</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" name="update_member" id="update_member" class="btn btn-primary w-100 text-white" style="background: linear-gradient(135deg, #12c2e9, #c471ed, #f64f59); border-radius: 50px; font-size: 18px;">Update</button>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/update_member.js"></script>
</body>
</html>
