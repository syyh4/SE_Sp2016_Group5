<?php
	include("../../../db_security/security.php");

	echo "{ 'host' : '" . constant("DB_HOST") . " , 'username' : " . constant("DB_USERNAME") . " , 'password' : " . constant("DB_PASSWORD") . " , 'database' : " . constant("DB_DATABASE") . " }";
?>
