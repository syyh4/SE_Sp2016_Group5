<?php
	
	function get_url_parameters_information( $param_array ) {
		
		$param_count = count($param_array);
		
		return array(
			"paramc" => $param_count,
			"params" => $param_array
		);
		
	}
	function generate_random_string( $str_length ) {
		
		$max_length = 256;
		
		if ($str_length <= 0 && $str_length > $max_length) {
			$str_length = $max_length;
		}
		
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $str_length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    
	    return $randomString;
	}
	
	function set_sql_error_response ( $error_code , $db_error ) {
		
		if (!isset($error_code))
			$error_code = 201;
			
		$http_response_code = 500;
		
		$response_array = array(
			"error_code" => $error_code,
			"error_message" => "There was a SQL error -> " . $db_error
		);
		
		http_response_code($http_response_code);
		
		echo json_encode($response_array);
		
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
?>