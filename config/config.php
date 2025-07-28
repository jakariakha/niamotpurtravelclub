<?php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');

// Database Connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// SMTP Configuration
$hostname = '';
$username = '';
$password = '';
$mail_from= '';
$mail_from_name = '';

define('MAIL_HOST', $hostname);
define('MAIL_PORT', 587);
define('MAIL_USERNAME', $username);
define('MAIL_PASSWORD', $password);
define('MAIL_FROM', $mail_from);
define('MAIL_FROM_NAME', $mail_from_name);
?>
