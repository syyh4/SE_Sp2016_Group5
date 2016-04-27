<?php
	
	//	Include reference to sensitive databse information
	//		Note:	This file should NOT be included in the public GitHub repository, it should only exist on the server.
	include("../source_code/db_security/security.php");
	
	
	//	First connect to the database using values from the included file
	$db_conn = new mysqli(constant("DB_HOST"), constant("DB_USERNAME"), constant("DB_PASSWORD"), constant("DB_DATABASE"));
	
	if ($db_conn->error_code) {
		
		//	This should be replace PHP that sets the HTTP status code to 500 and
		//	sets the body to the JSON object that contains the error_code and
		//	error_string as defined by the API
		die("The connection to the database failed: " . $db_conn->connect_error);
	}
	
	
	

	//	Get the latest entry
	$username = "justanewuser";
	
	if ($argc == 2) {
		$username = $argv[1];
	}		
	
	$query_string = "SELECT uid FROM user WHERE username = '$username'";
	
	if ($result = $db_conn->query($query_string)) {
		
		if ($result->num_rows == 1) {
	
			$only_row = $result->fetch_row();
			
			$uid = $only_row[0];			
			
			$query_string = "DELETE FROM person WHERE uid = " . $uid;
	
			if ($db_conn->query($query_string)) {
				
				$query_string = "DELETE FROM user_authentication WHERE uid = " . $uid;
				
				if ($db_conn->query($query_string)) {
					
					
					$query_string = "DELETE FROM user_auth_tokens WHERE issued_to = " . $uid;
					
					if ($db_conn->query($query_string)) {
						$query_string = "DELETE FROM user WHERE uid = " . $uid;
					
					
						if ($db_conn->query($query_string)) {
							echo "I successfully delete the user from user, person where uid = " . $uid . "\n";	
						}
						else {
							echo "SQL Error 1 -> " . $db_conn->error . "\n";
						}	
					}	
					else {
						echo "SQL Error 5 -> " . $db_conn->error . "\n";	
					}					
					
				}
				else {
					echo "SQL Error 2 -> " . $db_conn->error . "\n";
				}
			}
			else {
				echo "SQL Error 3 -> " . $db_conn->error . "\n";
			}
		}
		else {
			echo "I got no rows back...\n";
		}	
		
	}
	else {
		echo "That query didn't work -> " . $db_conn->error;
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
				
				if ($month < 10) {
					$month_string = "0" . $month;
				}
				
				$clean_date_string = $month_string . "/" . $day_string . "/" . $year;
				
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
	function generate_255_char_random_string() {
		
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