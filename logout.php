<?php
session_start();
session_unset();
session_destroy();
require 'auth_check.php';
header('location: login.php');
exit();
?>