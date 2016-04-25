<?php
	
	//	Include reference to sensitive databse information
	//		Note:	This file should NOT be included in the public GitHub repository, it should only exist on the server.
	include("../../../db_security/security.php");
	
	//	Include untilities
	include("../php/utilities.php");
	
	//	First connect to the database using values from the included file
	$db_conn = new mysqli(constant("DB_HOST"), constant("DB_USERNAME"), constant("DB_PASSWORD"), constant("DB_DATABASE"));
	
	if ($db_conn->error_code) {
		
		//	This should be replace PHP that sets the HTTP status code to 500 and
		//	sets the body to the JSON object that contains the error_code and
		//	error_string as defined by the API
		die("The connection to the database failed: " . $db_conn->connect_error);
	}
	
	
	
	
	
	
	/*
		REQUEST HANDLING
	*/
	
	$req_method = $_SERVER['REQUEST_METHOD'];
	
	switch ($req_method) {
		
		case 'GET':
		
			
		
		
		
		
		
		
		
		break;
		
		case 'UPDATE':
		
		
		
		
		
		
		
		
		
		
		break;
		
		
		case 'DELETE':
		
		
		
		
		
		
		
		break;
		
		case 'POST':
		
		
		
		
		
		
		
		
		
		
		break;
				
		default:
			set_error_response( 101, "Unsopported HTTP operation");
		break;
	}	
?>