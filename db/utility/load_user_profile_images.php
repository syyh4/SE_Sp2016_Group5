<?php
	
	//	Include reference to sensitive databse information
	//		Note:	This file should NOT be included in the public GitHub repository, it should only exist on the server.
	DEFINE( "DB_HOST" , "localhost");
	DEFINE( "DB_USERNAME" , "web_user");
	DEFINE( "DB_PASSWORD" , "pass");
	DEFINE( "DB_DATABASE" , "linkedin_group_5");	
	
		
	//	First connect to the database using values from the included file
	$db_conn = new mysqli(constant("DB_HOST"), constant("DB_USERNAME"), constant("DB_PASSWORD"), constant("DB_DATABASE"));
	
	if ($db_conn->error) {
		
		//	This should be replace PHP that sets the HTTP status code to 500 and
		//	sets the body to the JSON object that contains the error_code and
		//	error_string as defined by the API
		die("The connection to the database failed: " . $db_conn->error);
	}
	
	$male_img_urls = array();
	
	$load_all_male_image_urls_sql = "SELECT * FROM user_profile_images WHERE img_gender='male'";
	
	if (!($result = $db_conn->query($load_all_male_image_urls_sql))) {
		echo_simple( "I couldn't get all the male images..." );
		exit(1);
	}
	
	while ($result_row = $result->fetch_array(MYSQLI_ASSOC)) {
		
		$male_img_url = $result_row["img_url"];
		array_push($male_img_urls, $male_img_url);
	}
	
	/*
		GET ALL THE FEMALE IMAGE URLS
	*/
	
	$female_img_urls = array();
	
	$load_all_female_image_urls_sql	= "SELECT * FROM user_profile_images WHERE img_gender='female'";
	
	if (!($result = $db_conn->query($load_all_female_image_urls_sql))) {
		echo_simple( "I couldn't get all the female images..." );
		exit(1);
	}
	
	while ($result_row = $result->fetch_array(MYSQLI_ASSOC)) {
		
		$female_img_url = $result_row["img_url"];
		array_push($female_img_urls, $female_img_url);
	}

	echo "\n" . json_encode($female_img_urls) . "\n";
	

	$male_uids = array();
	
	$load_all_male_uids = "SELECT uid FROM person WHERE gender LIKE 'male%'";
	
	if (!($result = $db_conn->query($load_all_male_uids))) {
		echo_simple( "I couldn't get all the male uids");
		exit(2);
	}
	
	while ($result_row = $result->fetch_array(MYSQLI_ASSOC)) {
		
		$male_uid = $result_row["uid"];
		
		array_push($male_uids, $male_uid);
	}
	
	echo "\n Male UIDS -> \n " . json_encode($male_uids) . "\n";
	
	//	Get all the female UIDs
	
	$female_uids = array();
	
	$load_all_female_uids = "SELECT uid FROM person WHERE gender LIKE 'female%'";
	
	if (!($result = $db_conn->query($load_all_female_uids))) {
		echo_simple( "I couldn't get all the female uids");
		exit(2);
	}
	
	while ($result_row = $result->fetch_array(MYSQLI_ASSOC)) {
		
		$female_uid = $result_row["uid"];
		
		array_push($female_uids, $female_uid);
	}
	
	$male_update_values = array();
	
	
	foreach( $male_uids as $male_id ) {
		
		$uid_value = $male_id;
		
		$img_value = $male_img_urls[0];
		
		$ret_array = array(
			"uid" => $uid_value,
			"img_url" => $img_value
		);
		
		$male_update_values = array();
		
	}
	
	//	Update the male values
	
	$update_male_images_sql = "UPDATE user SET prof_image = ? WHERE uid = ?";
	
	if($update_male_images_stmt = $db_conn->prepare($update_male_images_sql)) {
		
		foreach( $male_update_values as $update_value_male) {
			
			$male_update_uid = $update_value_male["uid"];
			$male_update_img = $update_value_male["img_url"];
			
			$update_male_images_stmt->bind_param("si", $male_update_img, $male_update_uid);
			
			$update_male_images_stmt->execute();
		}
		
	}






	/*
		CUSTOM FUNCTIONS
	*/
	function echo_simple( $v ) {
		echo "\n$v\n";
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