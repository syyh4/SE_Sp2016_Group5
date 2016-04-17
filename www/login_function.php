<?php
session_start(); // Starting Session
include("../../db_security/security.php");
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
if (empty($_POST['email']) || empty($_POST['password'])) {
$error = "Email or Password is empty";
}
else
{
// Define $username and $password
$email=$_POST['email'];
$password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
//$connection = mysql_connect("localhost", "root", "");
$db_conn = new mysqli(constant("DB_HOST"), constant("DB_USERNAME"), constant("DB_PASSWORD"), constant("DB_DATABASE"));
	
	if ($db_conn->error_code) {
		
		//	This should be replace PHP that sets the HTTP status code to 500 and
		//	sets the body to the JSON object that contains the error_code and
		//	error_string as defined by the API
		die("The connection to the database failed: " . $db_conn->connect_error);
	}
// To protect MySQL injection for Security purpose
$email = stripslashes($email);
$password = stripslashes($password);
$email = mysql_real_escape_string($email);
$password = mysql_real_escape_string($password);
// Selecting Database
$db = mysql_select_db("users", $db_conn);
// SQL query to fetch information of registerd users and finds user match.
$query = mysql_query("select * from login where email='$email'", $db_conn);
$rows = mysql_num_rows($query);
if ($rows == 1) {
$_SESSION['login_user']=$email; // Initializing Session
header("location: user.php"); // Redirecting To Other Page
} else {
$error = "Email or Password is invalid";
}
mysql_close($db_conn); // Closing Connection
}
}
?>
