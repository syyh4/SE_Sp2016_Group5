<?php
	DEFINE( "DB_HOST" , "localhost");
	DEFINE( "DB_USERNAME" , "web_user");
	DEFINE( "DB_PASSWORD" , "pass");
	DEFINE( "DB_DATABASE" , "linkedin_group_5");	
	
	
	$db_conn = new mysqli(constant("DB_HOST"), constant("DB_USERNAME"), constant("DB_PASSWORD"), constant("DB_DATABASE"));
	
	if (!$db_conn) {
		
		die("The connection couldn't be created...");
	}
	if ($db_conn->error) {
		
		die("The connection to the database failed with the error code (" + $db_conn->error + ")");
	}
	
	$get_users_query = "SELECT uid, username FROM user";
	
	$user_information = array();
	
	if ($result = $db_conn->query($get_users_query)) {
		
		echo "\nThe query returned " . $result->num_rows . " rows";
		echo "\n";
		
		while($row = $result->fetch_array(MYSQLI_NUM)) {
			
			$single_user_info = array(
				"uid" => $row[0],
				"username" => $row[1],
				"salt" => "",
				"hash" => ""
			);
			
			array_push($user_information, $single_user_info);
		}
	}
	
	echo json_encode($user_information);
	
	
	$default_password = "justadefaultpassword";
	
	
	$prepared_insert_sql = "INSERT INTO user_authentication (uid, password_hash, salt) VALUES ( ? , ? , ? )";
	
	$prepared_insert_stmt = $db_conn->stmt_init();
	
	if ($prepared_insert_stmt->prepare($prepared_insert_sql)) {
		
		
		
		
		$arr_count = count($user_information);
		
		for ($i = 0; $i < $arr_count; $i++) {
			
			$salt = sha1( mt_rand() );
			
			$hash = sha1($salt.$default_password);
			
			echo "\nI think I'll just insert the following values for (user, salt, hash) " . "( " . $user_information[$i]['username'] . " , " . $salt . " , " . $hash . " )";
			
			
			$prepared_insert_stmt->bind_param("iss", $user_information[$i]['uid'], $hash, $salt);
			
			if (!$prepared_insert_stmt->execute()) {
				
				die( "I could not insert the values. The error I got was => " . $db_conn->error);
			}
		}
	}
	
	echo "\n\n\n";
	
	
	
	$check_results_query = "SELECT * FROM user_auth_view";
	
	
	if ($result = $db_conn->query($check_results_query)) {
		
		echo "\n";
		while( $row = $result->fetch_row()) {
			
			echo json_encode($row);
			
			$uid = $row[0];
			$username = $row[1];
			$hash = $row[2];
			$salt = $row[3];
			
			echo "\n I got the following values for (uid, username, hash, salt) ... ($uid , $username, $hash, $salt)";
			
		}
	}
	else
	{
		die("I couldn't get any results from the DB" + $db_conn->error);
	}
?>