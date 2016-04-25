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
	$params = $_REQUEST;
	
	switch ($req_method) {
		
		case 'GET':
			
			if (isset($params['auth_token'])) {
				
				$filt_data = collect_filter_data_from_params( $params );
				$auth_token = $params['auth_token'];
				
				$check_auth_token_sql = "SELECT (issued_to) FROM user_auth_tokens WHERE token = '$auth_token'";
				
				$uid;
				
				if ($result = $db_conn->query($check_auth_token_sql)) {
					
					if ($result->num_rows == 1) {
						$row = $result->fetch_row();
						
						$uid = $row[0];
					}	
					
				}
				else {
					set_sql_error_response( 201, $db_conn->error);
				}
				
				
				if (isset($uid)) {
					
					
					$columns = array("uid", "username", "email", "firstname", "middlename", "lastname", "birth_date", "gender");
					
					$built_query = create_select_query_with_filters( $columns , "user_person_view", $filt_data);
					if ($result = $db_conn->query($built_query)) {
						
						
						$all_rows = $result->fetch_all();
						
						$clean_array = clean_up_user_response_array( $all_rows );
						
						http_response_code(200);
						
						echo json_encode($clean_array);
						
					}
					
					
					
					
					
				}
			}
		
		
		
		
		
		
		
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
	
	
	
	function create_select_query_with_filters( $columns , $table, $filter_data ) {
		
		$query = "SELECT *";
		
/*
		
		for ($i = 0; $i < count($columns); $i++) {
			
			$suffix;
			
			if ($i != count($columns) - 1) {
				$suffix = ",";
			}
			else {
				$suffix = ") ";
			}
			
			$query .= $columns[$i] . $suffix;
			
			
		}
*/

		
		$query .= "FROM " . $table . " ";
		
		if (count($filter_data["filter"]) > 0) {
			
			$query .= "WHERE ";
			
			$filter_count = 0;
			
			
			foreach($filter_data["filter"] as $filter) {
				
				$small_filter_count = 0;
				
				foreach($filter["filters"] as $filter_exp) {
					$suffix;
					
					if (($filter_count == count($filter_data["filter"]) - 1) && ($small_filter_count == count($filter["filters"]) - 1)) {
						$suffix = " ";
					}
					else {
						$suffix = " AND ";
					}
					
					$query .= $filter["column"] . " " . convert_url_exp_to_sql($filter_exp["expression"]) . " " . $filter_exp["filter_value"] . $suffix;
					$small_filter_count++;
				}
				
				$filter_count++;
			}
		}
		
		
		
		
		if (count($filter_data["order_by"]) > 0) {
			
			$query .= "ORDER BY ";
			
			for ($i = 0; $i < count($filter_data["order_by"]); $i++) {
				
				$curr = $filter_data["order_by"][$i];
				
				$suffix;
				
				if ($i == count($filter_data["order_by"]) - 1) {
					$suffix = " ";
				}
				else {
					$suffix = " , ";
				}
				
				
				
				$query .= $curr["column"] . " " . $curr["order"];
			}
		}
		
		
		if (count($filter_data["limit"]) == 1) {
			
			$limit = $filter_data["limit"][0];
			
			$query .= "LIMIT " . $limit["value"];
		}
		
		return $query;
		
	}
	function collect_filter_data_from_params( $params ) {
		
		$ob_start = "ob-";
		$filter_start = "fk-";
		$limit_start = "limit";
		
		$filter_data = array();
		$filters_arr = array();
		$order_by_arr = array();
		$limit_arr = array();
		
		foreach ($params as $key => $value ) {
			
			if (startsWith($key, $ob_start)) {
				
				$orderbykey = get_order_by_key( $key );
				
				if ($orderbykey == "birthdate") {
					$orderbykey = "birth_date";
				}
				
				$orderby = array(
					"type" => "orderBy",
					"column" => $orderbykey,
					"order" => strtoupper($value)
				);
				
				array_push($order_by_arr, $orderby);
			}
			else if (startsWith($key, $filter_start)) {
				
				
				$key_and_type = get_filter_key_and_type( $key );
				
				$filters = get_filters( $key_and_type, $value );
				
				$filt_arr = array(
					"type" => "filter",
					"column" => $key_and_type['key'],
					"dataType" => $key_and_type['dataType'],
					"filters" => $filters
				);
				
				array_push($filters_arr, $filt_arr);
				
			}
			else if(startsWith( $key, $limit_start)) {
				
				$lim_arr = array(
					"type" => "limit",
					"value" => $value
				);
				
				array_push($limit_arr, $lim_arr);
			}
		}
		
		$filter_data["filter"] = $filters_arr;
		$filter_data["order_by"] = $order_by_arr;
		$filter_data["limit"] = $limit_arr;
		
		return $filter_data;
	}
	
	function get_filters( $keyAndType , $valueStr ) {
		
		$filters = array();
		
		switch ($keyAndType['dataType']) {
			
			case "i" : 
				
				$filter_pieces = explode("_", $valueStr);
				
				foreach ($filter_pieces as $piece) {
					
					$pieces = explode("-", $piece);
					
					$filter_expression = $pieces[0];
					$filter_value = $pieces[1];
					
					$filter_array = array(
						"expression" => $filter_expression,
						"filter_value" => $filter_value
					);
					
					array_push($filters, $filter_array);
					
				}
			break;
			
			
			case "s" : 
				$filter_pieces = explode("_");
				
				foreach ($filter_pieces as $piece) {
					
					$pieces = explode("-", $piece);
					
					$filter_expression = $pieces[0];
					$filter_value = $pieces[1];
					
					$filter_array = array(
						"expression" => convert_url_exp_to_sql($filter_expression),
						"filter_value" => $filter_value
					);
					
					array_push($filters, $filter_array);
					
				}
			break;
			
			
			case "d" : 
				$filter_pieces = explode("_");
					
					foreach ($filter_pieces as $piece) {
						
						$pieces = explode("-", $piece);
						
						$filter_expression = $pieces[0];
						$filter_value = $pieces[1];
						
						$filter_array = array(
							"expression" => $filter_expression,
							"filter_value" => clean_filter_date_value( $filter_value )
						);
						
						array_push($filters, $filter_array);
						
					}
			break;
		}
		
		return $filters;
		
	}
	function convert_url_exp_to_sql( $url_exp ) {
		
		$sql_exp;
		
		switch ($url_exp) {
			
			case "gt":
			$sql_exp = ">";
			break;
			
			case "gte":
				$sql_exp = ">=";
			break;
			
			case "lt":
				$sql_exp = "<";
			break;
			
			case "lte":
				$sql_exp = "<=";
			break;
			
			case "eq":
				$sql_exp = "=";
			break;
		}
		
		return $sql_exp;
	}
	function clean_filter_date_value( $filterDate ) {
		
		$date_pieces = explode("%", $filerDate );
		
		$month = $date_pieces[0];
		$day = $date_pieces[1];
		$year = $date_pieces[2];
		
		
		return $month . "/" . $day . "/" . $year;
		
	}
	function get_filter_key_and_type( $filterstr ) {
		$filterKey;
		
		$filter_pieces = explode("-", $filterstr);
		
		$key = $filter_pieces[2];
		$type = $filter_pieces[1];
		
		
		return array(
			"dataType" => $type,
			"key" => $key
		);
	}
	function get_order_by_key( $orderbystr ) {
		$ob_key;
		
		$ob_pieces = explode("-", $orderbystr);
		
		$ob_key = $ob_pieces[1];
		
		return $ob_key;
	}

	function startsWith($haystack, $needle) {
	    // search backwards starting from haystack length characters from the end
	    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
	}
	
	function does_string_begin_with_string( $main_str, $sub_str) {
		
		$does_contain = false;
		
		if (strpos($main_str, $sub_str) == 0) {
			$does_contain = true;
		}
		echo json_encode(array("main_str" => $main_str, "sub_str" => $sub_str, "does_contain" => $does_contain));
		
		return $does_contain;
	}
	function clean_up_user_response_array( $result ) {
		
		$ret_array = array();
		
		for ($i = 0; $i < count($result); $i++) {
			
			$curr = $result[$i];
			
			
			$new_array = array(
				"uid" => $curr[0],
				"username" => $curr[1],
				"email"	=> $curr[2],
				"fname" => $curr[3],
				"mname" => $curr[4],
				"lname" => $curr[5],
				"birthdate" => $curr[6],
				"gender" => $curr[7]
			);
			
			array_push($ret_array, $new_array);
		}
		
		return $ret_array;
	}
?>