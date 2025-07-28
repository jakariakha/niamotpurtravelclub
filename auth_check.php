<?php
if(!(isset($_SESSION['member_email']) && $_SESSION['login_success'])){
    header('location: login.php');
    exit;
}
?>