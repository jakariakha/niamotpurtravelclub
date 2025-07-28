<?php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'ntc_members');
define('DB_PASS', 'Ov[BFZMH#}3Cej].}CHse%b)J');
define('DB_NAME', 'ntc');

// Database Connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// SMTP Configuration
$hostname = 'smtp-relay.brevo.com';
$username = '7a9932002@smtp-brevo.com';
$password = 'h7PLtgjQzndrWAfY';
$mail_from= 'no-reply@ntc.khapayplus.live';
$mail_from_name = 'NTC';

define('MAIL_HOST', $hostname);
define('MAIL_PORT', 587);
define('MAIL_USERNAME', $username);
define('MAIL_PASSWORD', $password);
define('MAIL_FROM', $mail_from);
define('MAIL_FROM_NAME', $mail_from_name);
?>