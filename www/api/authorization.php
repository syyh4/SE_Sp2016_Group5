<?php
	
	//	Include reference to sensitive databse information
	//		Note:	This file should NOT be included in the public GitHub repository, it should only exist on the server.
	#	include("../../")
	
	
	//	First connect to the database using values from the included file
	$db_conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	
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
		{
			//	Check the authorization_type (either 'initial' or 'refresh')
			
		}
		break;
		
		default:
		{
			//	The authorization endpoint is only set up to handle GET requests. All other types should throw an error.
			handle_request_error();
		}
		break;
	}
	
	
	
	
	/*
		UTILITY FUNCTIONS
	*/
	function handle_request_error() {
		
		
		
		
	}
	
	
	
	//	PHP automatically closes the connection to the DB when the script is finished running
	//	so you don't have to do this yourself.
	
?>