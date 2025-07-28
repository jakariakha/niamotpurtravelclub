<?php
session_start();
define('APP_STARTED', true);
include 'navbar.php';
include 'auth_check.php';
if(!(isset($_SESSION['position']) && $_SESSION['position'] == 'President')){
    header('location: index.php');
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NTC-Add user Info</title>
    <link rel="stylesheet" href="assets/css/toast.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100" style="padding-bottom: 100px">
        <div class="card p-5" style="max-width: 500px; width: 100%; min-height: 400px; border-radius: 20px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);">
            <div class="payment-content text-center">
                <h2 class="mb-4">Add Member</h2>
                <form>
                    <input type="hidden" id="csrfToken" value="<?php echo $_SESSION['csrf_token'];?>">
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="name" id="newMemberName" placeholder="Name" required style="border-radius: 50px 50px 50px;">
                            <label for="name">Name</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="email" class="form-control" name="email" id="newMemberEmail" placeholder="Email" required style="border-radius: 50px 50px 50px;">
                            <label for="email">Email</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="email" class="form-control" name="member_uid" id="newMemberUid" placeholder="UID" required style="border-radius: 50px 50px 50px;">
                            <label for="email">UID</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="deposit_amount" id="newMemberDepositAmount" placeholder="Deposit Amount" required style="border-radius: 50px 50px 50px;">
                            <label for="deposit-amount">Deposit Amount</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="due_amount" id="newMemberDueAmount" placeholder="Due Amount" required style="border-radius: 50px 50px 50px;">
                            <label for="due-amount">Due Amount</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="due_month" id="newMemberDueMonth" placeholder="Due Month" required style="border-radius: 50px 50px 50px;">
                            <label for="due-month">Due Month</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="set_amount" id="newMemberSetAmount" placeholder="Set Amount" required style="border-radius: 50px 50px 50px;">
                            <label for="set-amount">Set Amount</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" name="joining_date" id="newMemberJoiningDate" placeholder="Joining Date" required style="border-radius: 50px 50px 50px;">
                            <label for="joining-date">Joining Date</label>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <select class="form-select" name="position" id="newMemberPosition" aria-label="Select Position" required style="border-radius: 50px 50px 50px; padding: 20px; padding-right: 50px;">
                                <option selected>Select Position</option>
                                <option value="President">President</option>
                                <option value="Cashier">Cashier</option>
                                <option value="Member">Member</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" name="submit" id="addNewMember" class="btn btn-primary w-100 text-white" style="background: linear-gradient(135deg, #12c2e9, #c471ed, #f64f59); border-radius: 50px; font-size: 18px;">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/add_activity.js"></script>
</body>
</html>
