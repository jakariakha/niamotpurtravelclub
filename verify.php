<?php
session_start();
require 'cdn.php';
if(!isset( $_SESSION['otp_sent']) && $_SERVER['REQUEST_METHOD'] === 'POST'){
    header('location: 404.php');
    exit;
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
    <title>NTC - Verify OTP</title>
    <link rel="stylesheet" href="assets/css/toast.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4" style="max-width: 380px; width: 100%; min-height: 400px; border-radius: 20px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; justify-content: center;">
            <h2 class="text-center mb-4">NTC-Verify Your Account</h2>
            <h4 class="text-center mb-4"></h4>
            <form>
                <input type="hidden" id="csrfToken" value="<?php echo $_SESSION['csrf_token'];?>">
                <div class="mb-3 input-group">
                    <span class="input-group-text bg-light border-0" style="border-radius: 50px 0 0 50px;">🔑</span>
                    <input type="text" class="form-control" name="otp" id="submitOtp" placeholder="Enter OTP" required style="border-radius: 0 50px 50px 0;">
                </div>
                <span class="text-danger" id="otpError" style="display: none; margin-right: 183px;">OTP is required!</span>
                <button type="button" class="btn w-100 text-white" id="verify" name="verify" style="background: linear-gradient(135deg, #12c2e9, #c471ed, #f64f59); border-radius: 50px; font-size: 18px;">Verify</button>
            </form>
        </div>
    </div>
    <script src="assets/js/login.js"></script>
</body>
</html>
