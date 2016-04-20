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
			//	Check the authorization_type (either 'initial' or 'refresh'
			
			$valid_auth_token = false;
			
			if (isset($_GET['auth_token'])) {
				
				//	Check to see if the auth token exists in the database
				$auth_token = $_GET['auth_token'];
				
				
				$get_token_sql = "SELECT issued_to, token from user_auth_tokens where token = " . "'$auth_token'";
				
				if ($result = $db_conn->query($get_token_sql))
				{
					if ($result->num_rows > 0) {
						$valid_auth_token = true;
					}
				}
				else 
				{
					set_error_response( 21 , "SQL statement could not prepare " . $db_conn->error);
				}
								
			}
			else 
			{
				set_error_response( 4, "The auth parameter was not properly set");
			}
			
			
			
			if ($valid_auth_token) {
				
				if (isset($_REQUEST['req_type'])) {
					$req_type = $_GET['req_type'];
					
					
					switch ($req_type) {
						
						case "analytics":
						
							if (isset($_GET["cid"]) && isset($_GET["graph-type"])) {
								$cid = $_GET["cid"];
								$graph_type = $_GET["graph-type"];
								
								
								
								
								switch ($graph_type) {
									
									case "age-bar-chart":
									
										
										$bar_data = array();
										
										
										$ranges = array(
											
											"18 - 25" => "P.age >= 18 AND P.age < 25",
											"25 - 35" => "P.age >= 25 AND P.age < 35",
											"35 - 45" => "P.age >= 35 AND P.age < 45",
											"45 - 60" => "P.age >= 45 AND P.age < 60",
											"60+" => "P.age >= 60"	
										);
										
										foreach( $ranges as $key => $value ) {
											
											$bar_chart_data_sql = "SELECT C.uid, C.name, COUNT(P.age) as age_count FROM company C, person P WHERE " 
																	. $value 
																	. " AND C.uid = " . $cid 
																	. " GROUP BY C.uid";
											
											
											if ($result = $db_conn->query($bar_chart_data_sql)) {
												
												if ($result->num_rows == 1) {
													
													if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
														
														$final_arr = array(
															"range" => $key,
															"count" => $row["age_count"]
														);
														
														
														array_push($bar_data, $final_arr);
													}
												}
												else {
													set_error_response( 202 , "The row count was off" );
												}
												
											}
											else {
												
												set_error_response( 201, "SQL ERROR -> " . $db_conn->error);
											}
										}
										
										$labels_arr = array();
										$data_arr = array();
										
										foreach($bar_data as $key => $value ) {
											
											array_push($labels_arr, $key);
											array_push($data_arr, $value["count"]);
										}
										
										$dataset_one = array(
											"label" => "age-data",
											"fillColor" => convert_reqular_color_to_rgb("blue"),
											"strokeColor" => convert_reqular_color_to_rgb("blue"),
											"highlightFill" => convert_reqular_color_to_rgb("light-blue"),
											"highlightStroke" => convert_reqular_color_to_rgb("blue"),
											"data" => $data_arr
										);
										
										$company_info = array(
											"cid" => $cid
										);
										
										$chart_data = array(
											"labels" => $labels_arr,
											"datasets" => array( $dataset_one )
										);
										
										$ret_array = array(
											"company-info" => $company_info,
											"bar-chart-data" => $chart_data
										);
										
										http_response_code(200);
										
										echo json_encode($ret_array);
									
									
									break;
									
									
									
									
									
									
								}
							}
						
						
						
						break;
						
						
						
						
					}	
					
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
		GET
	*/
	
	
	function send_all_companies($db_conn, $uid) {
		
		
				
		
	}
	/*
		UTILITY FUNCTIONS
	*/
	
	function convert_reqular_color_to_rgb( $color ) {
		$rgb_string;
		
		switch ($color) {
			
			case "blue":
				$hex_string = "#336699";
				$alpha_val = 0.8;
				$rgb_string = convert_hex_to_rgb( $hex_string, $alpha_val);
			break;
			
			case "light-blue":
				$hex_string = "#336699";
				$alpha_val = 0.4;
				$rgb_string = convert_hex_to_rgb( $hex_string, $alpha_val);
			break;
			
			case "red":
			
				$hex_string = "#e60000";
				$alpha_val = 0.8;
				$rgb_string = convert_hex_to_rgb( $hex_string, $alpha_val);
			break;
			
			case "light-red":
			
				$hex_string = "#e60000";
				$alpha_val = 0.4;
				$rgb_string = convert_hex_to_rgb( $hex_string, $alpha_val);
			break;
			
			default:
			
				$hex_string = "#e60000";
				$alpha_val = 0.4;
				$rgb_string = convert_hex_to_rgb( $hex_string, $alpha_val);
			break;
		}
		return $rgb_string;	
	}
	
	
	function convert_hex_to_rgb( $hex_string, $alpha) {
		
		$clean_string = $hex_string;
		
		if (strpos($hex_string, "#") == 0) {
			$clean_string = str_replace("#", "", $hex_string);
		}
		
		$red = substr($clean_string, 0, 2);
		$green = substr($clean_string, 2, 2);
		$blue = substr($clean_string, 4, 2);
		
		$red_dec = hexdec($red);
		$green_dec = hexdec($green);
		$blue_dec = hexdec($blue);
		
		return get_rgb_string_for_values( $red_dec, $green_dec, $blue_dec, $alpha);		
	}

	function get_rgb_string_for_values( $r , $g , $b, $a ) {
		
		return "rgba( $r, $g, $b, $a )";
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