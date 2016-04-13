<?php
	
	//	Include reference to sensitive databse information
	//		Note:	This file should NOT be included in the public GitHub repository, it should only exist on the server.
	include("../../../db_security/security.php");
	
	
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
		{
			//	Check the authorization_type (either 'initial' or 'refresh')
		}
		break;
		
		default:
		{
			
			//	Execute the placeholder query
			execute_placeholder_query( $db_conn );
			
			//	The authorization endpoint is only set up to handle GET requests. All other types should throw an error.
			handle_request_error();
		}
		break;
	}
	
	
	
	
	/*
		UTILITY FUNCTIONS
	*/
	
	
	
	
	//
	//	Random Utility Functions
	//
	function execute_placeholder_query( $db_conn ) {
		
		//	First prepare the SQL query
		$query_string = "SELECT * FROM user";
		
		if ($stmt = $db_conn->prepare($query_string)) {
		
			//	If the statement could be prepare then go ahead and execute it
			
			$stmt->execute();
			
			$result = $stmt->fetch_all();
			
			print_r($result);	
		}
		else {
			echo "Couldn't prepare the statement";
		}
	}	
	//
	//	Error Handling
	//
	function handle_request_error() {
		
		
		
		
	}
	
	
	
	//	PHP automatically closes the connection to the DB when the script is finished running
	//	so you don't have to do this yourself.
	
?>