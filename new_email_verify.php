<?php
session_start();
require 'auth_check.php';
if(!isset($_POST['new_email_verify']) && $_SERVER['REQUEST_METHOD'] != 'POST'){
    header('location: index.php');
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<div class="mb-4 position-relative" style="padding-top: 20px;">
    <div class="form-floating" style="position: relative;">
        <input type="hidden" id="csrfToken" value="<?php echo $_SESSION['csrf_token'];?>">
        <input type="text" class="form-control" name="otp" id="inputOtp" placeholder="OTP" required 
               style="border-radius: 50px; padding-right: 120px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); font-size: 16px;">
        <label for="otp" style="font-size: 14px; padding-left: 15px; color: #666;">Enter OTP</label>
        
        <!-- Button positioned at the right, styled for a premium look -->
        <button type="button" id="sendOtp" class="btn btn-primary" 
                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); 
                       border-radius: 50px; background: linear-gradient(135deg, #12c2e9, #c471ed, #f64f59); 
                       border: none; font-size: 14px; padding: 8px 20px; color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            Send OTP
        </button>
    </div>
    <span class="error text-danger" id="otpError" 
          style="display: none; margin-right: 150px; font-size: 13px; margin-top: 5px;">
        OTP is required!
    </span>
    <span class="error text-danger" id="wrongOtpError" 
          style="display: none; margin-right: 150px; font-size: 13px; margin-top: 5px;">
        Invaild OTP!
    </span>
</div>

<!-- Save button with a more premium gradient, font, and spacing -->
<button type="button" id="submitOtp" name="submit" class="btn btn-primary text-white" 
        style="background: linear-gradient(135deg, #12c2e9, #c471ed, #f64f59); 
               border-radius: 50px; font-size: 18px; margin-top: 15px; padding: 5px 60px; 
               box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); border: none; font-weight: 600;">Save</button>
               
<script src="assets/js/update_profile.js"></script>