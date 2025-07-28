<?php
session_start();
include 'config/config.php';
define('APP_STARTED', true);
include 'navbar.php';
require 'auth_check.php';

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<html>
    <head>
    <link rel="stylesheet" href="assets/css/toast.css">
    </head>
</html>
<div id="profile"></div>
<div class="container d-flex justify-content-center align-items-center min-vh-100" id="editProfileContainer" style="padding-bottom: 200px; padding-top: 50px">
        <div class="card p-5" style="max-width: 500px; width: 100%; min-height: 400px; border-radius: 20px; box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);">
            <div class="payment-content text-center">
                <h2 class="mb-4">Edit Profile</h2>
                <div id="otpInputBox"></div>
                <form id="editProfile">
                    <input type="hidden" id="csrfToken" value="<?php echo $_SESSION['csrf_token'];?>">
                    <input type="hidden" id="memberId" value="<?php echo $_SESSION['member_id']?>">
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="hidden" class="form-control" name="name" id="memberOldName" value="<?php if(isset($_SESSION['updated_name'])){echo $_SESSION['updated_name'];}else{echo $_SESSION['memberName'];}?>">
                            <input type="text" class="form-control" name="name" id="memberName" value="<?php if(isset($_SESSION['updated_name'])){echo $_SESSION['updated_name'];}else{echo $_SESSION['memberName'];}?>" placeholder="Name" required style="border-radius: 50px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); font-size: 16px;">
                            <label for="name">Name</label>
                        </div>
                        <span class="error text-danger" id="nameError" style="display: none; margin-right: 150px;">Name is required!</span>
                    </div>
                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="hidden" class="form-control" name="email" id="memberOldEmail" value="<?php if(isset($_SESSION['updated_email'])){echo $_SESSION['updated_email'];}else{echo $_SESSION['memberEmail'];}?>">
                            <input type="email" class="form-control" name="email" id="memberEmail" value="<?php if(isset($_SESSION['updated_email'])){echo $_SESSION['updated_email'];}else{echo $_SESSION['memberEmail'];}?>" placeholder="Email" required style="border-radius: 50px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); font-size: 16px;">
                            <label for="email">Email</label>
                        </div>
                        <span class="error text-danger" id="emailError" style="display: none; margin-right: 150px;">Email is required!</span>
                    </div>
            <button type="button" id="save_profile" name="submit" class="btn btn-primary text-white" 
               style="background: linear-gradient(135deg, #12c2e9, #c471ed, #f64f59); 
               border-radius: 50px; font-size: 18px; margin-top: 15px; padding: 5px 60px; 
               box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); border: none; font-weight: 600;">Save</button>
                </form>
            </div>
        </div>
    </div>
<script src="assets/js/update_profile.js"></script>