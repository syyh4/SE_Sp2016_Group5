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
			
			if (isset($_REQUEST['action'])) {
				
				switch ($_REQUEST['action']) {
					
					case 'reguser' :
					
						$validity_info = validate_parameters_for_action( 'reguser' );
						
						
						if (!$validity_info["isValid"]) {
							
							$response_arr = array(
								"unset_parameters" => $validity_info["missingValues"]
							);
							
							set_error_response( 101, $response_arr);
						}
						else {
							
							//	Pull all the information out of the request
							$req_username 	= $_REQUEST["username"];
							$req_email 		= $_REQUEST["email"];
							$req_password 	= $_REQUEST["password"];
							$req_birthdate 	= $_REQUEST["birthdate"];
							$req_gender 	= $_REQUEST["gender"];
							$req_fname 		= $_REQUEST["fname"];
							$req_mname 		= $_REQUEST["mname"];
							$req_lname 		= $_REQUEST["lname"];
							
							$clean_date_info = clean_date( $req_birthdate );
							
							if (!$clean_date_info["isValidDate"]) {
								set_error_response( 101, "The date format was invalid");
								break;
							}
							else {
								$req_birthdate = $clean_date_info["validDateString"];
							}
							
							
							//	Check to make sure this username isn't already taken
							
							$username_check_sql = "SELECT * FROM user WHERE username = ?";
							
							$username_check_stmt = $db_conn->stmt_init();
							
							$username_check_stmt->prepare($username_check_sql);
							$username_check_stmt->bind_param("s", $req_username);
							
							$username_is_valid = true;
							
							if ($username_check_stmt->execute()) {
								
								if ($username_check_result = $username_check_stmt->get_result()) {
									
									if ($username_check_result->num_rows > 0) {
										$username_is_valid = false;
									}
								}
								else {
									
									set_error_response( 201, "SQL Error -> " . $username_check_stmt->error);
								}
								
							}
							else {
								
								set_error_response( 201, "SQL Error -> " . $db_conn->error);
								break;
							}
						}
						
						$username_check_stmt->close();
						
						
						if (!$username_is_valid) {
							
							set_error_response( 203 , "The username is already taken");
							break;
						}
						
						
						//	If the information is valid then enter it into the database
						
						
						$insert_new_user_sql = "INSERT INTO user (username, email) VALUES ( ? , ? )";
											
						$insert_new_user_stmt = $db_conn->prepare($insert_new_user_sql);
						
						$insert_new_user_stmt->bind_param("ss" , $req_username , $req_email);
						
						$last_insert_id;
						
						if ($insert_new_user_stmt->execute()) {
							
							$last_insert_id = $insert_new_user_stmt->insert_id;
							
						}
						else {
							
							set_error_response( 201, "SQL Error -> " . $insert_new_user_stmt->error);
							
						}
						
						$insert_new_user_stmt->close();
						
						
						
						$saved_last_insert_id = $last_insert_id;
						
						if (isset($last_insert_id)) {
								
							$insert_new_person_sql = "INSERT INTO person (uid, firstname, middlename, lastname, birth_date, gender ) VALUES ( ?, ? , ? , ? , ? , ? )";
							
							if (!($insert_new_person_stmt = $db_conn->prepare($insert_new_person_sql))) {
								set_error_response( 201, "SQL Error -> " . $insert_new_person_stmt->error);
								break;
							}
							
							if (!($insert_new_person_stmt->bind_param("isssss", $last_insert_id, $req_fname, $req_mname, $req_lname, $req_birthdate, $req_gender))) {
								set_error_response( 201, "SQL Error -> " . $insert_new_person_stmt->error);
								break;
							}
							
							
							if ($insert_new_person_stmt->execute()) {
								
								//	Set that users salt and password
								
								$salt = sha1( mt_rand() );
								
								$hash = sha1( $salt . $req_password );
								
								$insert_user_auth_sql = "INSERT INTO user_authentication (uid , password_hash, salt) VALUES ('$saved_last_insert_id' , '$hash' , '$salt' )";
								
								if ($db_conn->query($insert_user_auth_sql)) {
									
									if ($db_conn->affected_rows == 1) {
										
									}
									else {
										set_error_response( 201, "SQL Error 2 -> " . $db_conn->error);
										break;
									}
									
								}
								else {
									set_error_response( 201, "SQL Error 1 -> " . $db_conn->error);
									break;
								}
								
								
								
								$issued_to = $saved_last_insert_id;
								$auth_token = generate_64_char_random_string();
								
								
								$insert_auth_token_query = "INSERT INTO user_auth_tokens (issued_to, token) VALUES ( $issued_to , '$auth_token')";
								
								if ($db_conn->query($insert_auth_token_query)) {
									//	Return the persons information
								
									http_response_code(200);
								
									$ret_auth_info = array(
										"uid" => $issued_to,
										"auth_token" => $auth_token,
										"expires_in" => 15
									);
									

									$ret_user_info = array(
										
										"uid" => $saved_last_insert_id,
										"username" => $req_username,
										"email" => $req_email,
										"first_name" => $req_fname,
										"middle_name" => $req_mname,
										"last_name" => $req_lname,
										"birth_date" => $req_birthdate,
										"gender" => $req_gender
									);
									
									$ret_arr = array(
										"auth_info" => $ret_auth_info,
										"user_info" => $ret_user_info
									);

									
									echo json_encode($ret_arr);
								}
								else {
									set_error_response( 201, "SQL Error -> " . $db_conn->error);
								}
								
								
								
								
								
								
								
								
								
							}
							else {
								set_error_response( 201, "SQL ERROR -> " . $insert_new_person_stmt->error);
							}
							
							
							
							
							
						}
						else {
							set_error_response( 200, "SQL Error");
							break;
						}
					
					
					
					break;
					
					
					
					
					default:
					
					
					break;
					
					
					
					
					
					
				}
				
				
				
				
				
				
				
				
				
			}
			
			
			
			
			
		break;
		
		default:
			
			//	Execute the placeholder query
			//execute_placeholder_query( $db_conn );
			
			//	The authorization endpoint is only set up to handle GET requests. All other types should throw an error.
			handle_request_error();
		break;
	}
	
	
	
	
	/*
		UTILITY FUNCTIONS
	*/
	function clean_date( $date_string ) {
		
		$is_valid_date = true;
		
		$clean_date_string;
		
		$pieces = explode("-", $date_string);
		
		
		if (count($pieces) == 3) {
			
			$year = intval($pieces[2]);
			$valid_year = true;
			$month = intval($pieces[0]);
			$valid_month = true;
			$day = intval($pieces[1]);
			$valid_day = true;
			
			$min_age = 15;
			
			
			$max_year = intval(date("Y")) - $min_age;
			
			if (!($year >= 1900 && $year < $max_year)) {
				$valid_year = false;
			}
			
			if (!($month >= 1 && $month <= 12)) {
				$valid_month = false;
			}
			
			if ($valid_year && $valid_month) {
				
				$max_day = cal_days_in_month(CAL_GREGORIAN, $valid_month, $valid_year);
				
				
				if ($day > 0 && $day <= $max_day) {
					$is_valid_date = true;
				}
			}
			else {
				$is_valid_date = false;
			}

			
			if ($is_valid_date) {
				
				$month_string;
				$day_string;
				
				
				if ($day < 10) {
					$day_string = "0" . $day;
				}
				else {
					$day_string = "$day";
				}
				
				if ($month < 10) {
					$month_string = "0" . $month;
				}
				else {
					$month_string = "$month";
				}
				
				$clean_date_string = $year . "/" . $month_string . "/" . $day_string;
			}
		}
		
		$ret_array = array(
			"isValidDate" => $is_valid_date,
			"validDateString" => $clean_date_string
		);
		
		return $ret_array;
	}
	function validate_parameters_for_action( $action ) {
		
		$isValid = true;
		$missing_values = array();
		
		switch ($action) {
			
			case "reguser":
			
			if (!isset($_REQUEST['fname'])) {
				$isValid = false;
				array_push($missing_values, "fname");
			}
			
			if (!isset($_REQUEST['mname'])) {
				$isValid = false;
				array_push($missing_values, "mname");
			}
			
			if (!isset($_REQUEST['lname'])) {
				$isValid = false;
				array_push($missing_values, "lname");
			}
			
			if (!isset($_REQUEST['birthdate'])) {
				$isValid = false;
				array_push($missing_values, "birthdate");
			}
			
			if (!isset($_REQUEST['gender'])) {
				$isValid = false;
				array_push($missing_values, "gender");
			}
			
			if (!isset($_REQUEST['email'])) {
				$isValid = false;
				array_push($missing_values, "email");
			}
			
			if (!isset($_REQUEST['username'])) {
				$isValid = false;
				array_push($missing_values, "username");
			}
			
			if (!isset($_REQUEST['password'])) {
				$isValid = false;
				array_push($missing_values, "password");
			}
			
			break;
			
			
			
			default:
			
			break;
			
		}
		
		
		$ret_array = array(
			"isValid" => $isValid,
			"missingValues" => $missing_values
		);
		
		
		return $ret_array;
	}
	function generate_64_char_random_string() {
		
		$length = 64;
		
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
	function set_error_response( $error_code , $error_message ) {
		
		
		$http_response_code = 500;
		
		$response_array = array(
			"error_code" => $error_code,
			"error_message" => $error_message
		);
				echo json_encode($response_array);

		http_response_code($error_code);
		
	}
	
	function print_result_values( $result ) {
		
		$num_fields = $result->field_count;
		
		while ($row = $result->fetch_row()) {
			
			echo "\n";
			for ($i = 0; $i < $num_fields; $i++) {
				
				echo $row[$i] . "\t\t";
			}	
		}
		
		echo "\n";
	}
	
	function print_result_headers( $result ) {
		
		echo "\n";
		
		$num_fields = $result->field_count;
		
		$fields = $result->fetch_fields();
		
		for ($i = 0; $i < $num_fields; $i++) {
			echo $fields[$i]->name . "\t\t";
		}	
	}
	function print_result_all( $result ) {
		
		print_result_headers( $result );
		print_result_values( $result );
	}
	//
	//	Random Utility Functions
	//
	function execute_placeholder_query( $db_conn ) {
		
		//	First prepare the SQL query
		$query_string = "SELECT * FROM user";
		
		if ($result = $db_conn->query($query_string)) {
		
			
			print_result_all( $result );
			
			
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