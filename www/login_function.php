<?php
session_start(); // Starting Session
include("../../db_security/security.php");
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
$error = "Email or Password is invalid";
echo $error;
header("location: user.php");
}
?>

