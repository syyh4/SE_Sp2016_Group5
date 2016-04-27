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
									
										$ranges = array(
											"18 - 25" => "age >= 18 AND age < 25",
											"25 - 35" => "age >= 25 AND age < 35",
											"35 - 45" => "age >= 35 AND age < 45",
											"45 - 60" => "age >= 45 AND age < 60",
											"60+" => "age >= 60"	
										);
										
										
										
										//	First get the total number of employees
										
										$total_employee_count;
										
										$total_employee_count_sql =	"SELECT COUNT(*) AS global_emp_count FROM employees_view";
										
										if ($result = $db_conn->query($total_employee_count_sql)) {
											
											if($result->num_rows == 1) {
												
												if($result_array = $result->fetch_array(MYSQLI_ASSOC)) {
													
													$global_emp_count = $result_array["global_emp_count"];
													
													$total_employee_count = $global_emp_count;
												}
												else {
													set_error_response( 204 , "SQL Error -> " . $db_conn->error );
												}
											}
											else {
												set_error_response( 203 , "I didn't get the correct number of rows back...");
												break;
											}
											
											
										}
										else {
											set_error_response( 203 , "SQL ERROR -> " . $db_conn->error );
											break;
										}
										
										
										$total_company_employees;
										$company_name;
										
										$get_company_info_sql = "SELECT * FROM company_employees_view WHERE cid = " . $cid;
										
										
										if ($result = $db_conn->query($get_company_info_sql)) {
											
											if ($result->num_rows == 1) {
												
												if ($result_array = $result->fetch_array(MYSQLI_ASSOC)) {
													
													$result_comp_name = $result_array["name"];
													$result_total_emp = $result_array["total_employees"];
													
													$company_name = $result_comp_name;
													$total_company_employees = $result_total_emp;

												}
												
											}
											else {
												set_error_response( 203 , "I didn't get the correct number of rows..." );
												break;
											}
										}
										else {
											set_error_response( 201 , "SQL ERROR -> " . $db_conn->error );
											break;
										}
										
										
										
										
										//	Get the employee counts for all companies
										$global_age_range_vals = array();
										$company_age_range_vals = array();
										
										
										
										foreach( $ranges as $k => $v ) {
											
											$global_range_query = "SELECT COUNT(*) as total_emp FROM employees_view "
																	. "WHERE " . $v;
											
											if ($result = $db_conn->query($global_range_query)) {
												
												if ($result->num_rows == 1) {
													
													if ($result_array = $result->fetch_array(MYSQLI_ASSOC)) {
														
														$tmp_arr = array(
															"range" => $k,
															"count" => $result_array["total_emp"]
														);
														
														array_push($global_age_range_vals, $tmp_arr);
													}
													else {
														set_error_response( 202 , "SQL ERROR -> " . $db_conn->error );
														break;
													}
													
												}
												else {
													set_error_response( 201 , "The number of rows was off...");
													break;
												}
											}
											else {
												set_error_response( 202 , "SQL ERROR -> " . $db_conn->error );
												break;
											}
											
											
											$company_range_query = "SELECT COUNT(*) as total_emp FROM employees_view "
																	. "WHERE " . $v . " "
																	. "AND cid = " . $cid;
												
											if ($result = $db_conn->query($company_range_query)) {
												
												if ($result->num_rows == 1) {
													
													if ($result_array = $result->fetch_array(MYSQLI_ASSOC)) {
														
														$tmp_arr = array(
															"range" => $k,
															"count" => $result_array["total_emp"]
														);
														
														array_push($company_age_range_vals, $tmp_arr);
													}
													else {
														set_error_response( 202 , "SQL ERROR -> " . $db_conn->error );
														break;
													}
													
												}
												else {
													set_error_response( 201 , "The number of rows was off...");
													break;
												}
											}
											else {
												set_error_response( 202 , "SQL ERROR -> " . $db_conn->error );
												break;
											}				
										}
										
										
										
										
										$ranges_values_count = count($company_age_range_vals);
										
										$global_data = array();
										$company_data = array();
										
										for ($i = 0; $i < $ranges_values_count; $i++) {
											
											$curr_global = $global_age_range_vals[$i];
											$curr_comp = $company_age_range_vals[$i];
											
											$curr_global_count = $curr_global["count"];
											$curr_comp_count = $curr_comp["count"];
											
											$global_value = ($curr_global["count"] / $total_employee_count) * 100;
											$comp_value = ($curr_comp["count"] / $total_company_employees) * 100;
											
											$global_value = number_format($global_value, 2, '.', '');
											$comp_value = number_format($comp_value, 2 , '.', '');
											
											array_push($global_data, $global_value);
											array_push($company_data, $comp_value);
										}
										
										
										$labels_arr = array();
										
										foreach($global_age_range_vals as $key => $value ) {
											
											array_push($labels_arr, $value["range"]);
										}
																				
										
										
										$dataset_one_series_name = $company_name . " Values";
										$dataset_one = array(
											"label" => "age-data",
											"fillColor" => convert_reqular_color_to_rgb("blue"),
											"strokeColor" => convert_reqular_color_to_rgb("blue"),
											"highlightFill" => convert_reqular_color_to_rgb("light-blue"),
											"highlightStroke" => convert_reqular_color_to_rgb("blue"),
											"data" => $company_data
										);
										
										$dataset_two_series_name = "Global Values";
										$dataset_two = array(
											"label" => "age-data",
											"fillColor" => convert_reqular_color_to_rgb("blue"),
											"strokeColor" => convert_reqular_color_to_rgb("blue"),
											"highlightFill" => convert_reqular_color_to_rgb("light-blue"),
											"highlightStroke" => convert_reqular_color_to_rgb("blue"),
											"data" => $global_data
										);
										
										$company_info = array(
											"cid" => $cid
										);
										
										$chart_data = array(
											"labels" => $labels_arr,
											"datasets" => array( $dataset_one, $dataset_two ),
											"series" => array( $dataset_one_series_name, $dataset_two_series_name)
										);
										
										$ret_array = array(
											"company-info" => $company_info,
											"bar-chart-data" => $chart_data
										);
										
										http_response_code(200);
										
										echo json_encode($ret_array);
										
									break;
									
									case "gender-pie-chart":
									
										//	Get the number of females for the company
										$female_count;
										
										$female_count_sql = "SELECT company_id, company_name, count(*) as female_count FROM individual_employees_view WHERE company_id = $cid and gender LIKE 'female%'";
										
										if ($result = $db_conn->query($female_count_sql)) {
											
											if ($result->num_rows == 1) {
												
												
												if ($result_array = $result->fetch_array(MYSQLI_ASSOC)) {
													
													$female_count = $result_array["female_count"];
													
												}
												
											}
											else {
												set_error_response( 201 , "SQL Error -> " . $db_conn->error);
												break;
											}
										}
										else {
											set_error_response( 201 , "SQL Error -> " . $db_conn->error );
											break;
										}
										
										
										//	Get the number of males for the company
										
										$male_count;
										
										$male_count_sql = "SELECT company_id, company_name, count(*) as male_count FROM individual_employees_view WHERE company_id = $cid and gender LIKE 'male%'";
										
										if ($result = $db_conn->query($male_count_sql)) {
											
											if ($result->num_rows == 1) {
												
												if ($result_row = $result->fetch_array(MYSQLI_ASSOC)) {
													
													$male_count = $result_row["male_count"];
													
												}
												else {
													set_error_response( 201 , "SQL Error -> " . $db_conn->error );
													break;
												}
											}
											else {
												set_error_response( 202 , "I didn't get the correct number of rows back...");
												break;
											}
											
											
										}
										else {
											set_error_response( 201 , "SQL Error -> " . $db_conn->error );
											break;
										}
									
									
									
									
										if ((isset($male_count)) && (isset($female_count))) {
											
											$total_count = $male_count + $female_count;
											
											$labels = array("Male", "Female");
											$data = array($male_count, $female_count);
											
											$ret_array = array(
												"cid" => $cid,
												"labels" => $labels,
												"data" => $data			
											);
											
											http_response_code(200);
											echo json_encode($ret_array);
										}
									break;
									
									
									
									
									
								}
							}
						
						
						
						break;
						
						
						case 'company-info':
						
						
							if (isset($_GET['cid'])) {
								
								$cid = $_GET['cid'];
								
								$get_company_info_sql = "SELECT * FROM company_full_view WHERE company_id = " . $cid;
								
								
								if ($result = $db_conn->query($get_company_info_sql)) {
									
									if ($result->num_rows == 1) {
										
										if ($result_row = $result->fetch_array(MYSQLI_ASSOC)) {
											
											http_response_code(200);
											echo json_encode($result_row);
											
										}
										else {
											set_error_response( 203 , "SQL Error -> " . $db_conn->error );
										}
										
										
									}
									else {
										set_error_response( 202 , "The number of rows wasn't right...");
										break;
									}
									
									
								}
								else {
									set_error_response( 202 , "SQL Error -> " . $db_conn->error );
									break;
								}
								
							}
						
						
						break;
						
						case 'company-employees':
						
						if (isset($_GET['cid'])) {
							
							$cid = $_GET['cid'];
							
							
							
							$get_company_employees_sql = "SELECT * FROM employees_view WHERE cid = $cid LIMIT 10";
							
							$employees = array();
							
							if ($result = $db_conn->query($get_company_employees_sql)) {
								
								
								while( $result_row = $result->fetch_array(MYSQLI_ASSOC)) {
									
									
									$result_eid			= $result_row["eid"];
									$result_fname 		= $result_row["firstname"];
									$result_lname 		= $result_row["lastname"];
									$result_birthdate 	= $result_row["birth_date"];
									$result_age 		= $result_row["age"];
									$result_company		= $result_row["company_name"];
									$result_cid			= $result_row["cid"];
									$result_img			= $result_row["emp_image"];

									$ret_array = array(
										"eid" =>	$result_eid,
										"fname" =>	$result_fname,
										"lname" =>	$result_lname,
										"birthdate" => $result_birthdate,
										"age" => $result_age,
										"cid" => $result_cid,
										"company_name" => $result_company
										"emp_image" => $result_img
									);
									
									array_push($employees, $ret_array);
									
								}
								
								
								
								http_response_code(200);
								
								echo json_encode($employees);
							}
							else {
								set_error_response( 201 , "SQL Error -> " . $db_conn->error );
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
	
	function echo_simple( $v ) {
		
		echo "\n $v \n";
	}
	
	function echo_with_title($v, $t) {
		
		echo "\n $t -> $v ";
	}
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