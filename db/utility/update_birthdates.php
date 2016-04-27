<?php
	include("./helper_classes.php");
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
	
	//	Get Companies
	
	$get_comp_sql = "SELECT * FROM company";
	
	$ceh = new CompanyEmployeeHelper();
	
	
	
	if ($result = $db_conn->query($get_comp_sql)) {
		
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			
			$comp = new Company();
			
			
			$comp->set_values($row["name"], $row["uid"]);
			
			
			$ceh->add_company( $comp );
		}		
	}
	
	$get_emp_sql = "SELECT uid FROM company";
	
	
	
	$comp_cid_list = array();
	
	if ($result = $db_conn->query($get_emp_sql)) {
		
		
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$row_val = $row["uid"];
			
			array_push($comp_cid_list, $row_val);
			
		}
	}
	
	echo_array( $comp_cid_list, "CID List", false);
	
	$emp_id_list_for_comp = array();
	$company_emps_sql = "SELECT P.birth_date, CE.eid FROM person P, company_employees CE WHERE P.uid=CE.eid AND CE.cid= ?";
			
	$company_emps_stmt = $db_conn->prepare($company_emps_sql);
	
	foreach($comp_cid_list as $comp_cid) {
		
		$company_emps_stmt->bind_param("i", $comp_cid);
		
		$company_emps_stmt->execute();
		
		$result = $company_emps_stmt->get_result();
		
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			
			$comp_emp = array(
				"cid" => $comp_cid,
				"birth_date" => $row["birth_date"],
				"eid" => $row["eid"]
			);
			
			array_push($emp_id_list_for_comp, $comp_emp);
		}
	}
	
	
	$main_array = array();
	
	foreach($emp_id_list_for_comp as $val ) {
		
		$cid = $val["cid"];
		$emp_arr = array(
			"uid" => $val["eid"],
			"birth_date" => $val["birth_date"]
		);
		
		if (!isset($main_array[$cid])) {
			$new_arr = array();
			
			$main_array[$cid] = $new_arr;

		}
		
		array_push($main_array[$cid], $emp_arr);
	}
	
	
	echo_array( $main_array, "MAIN", true);
	
	foreach($main_array as $k => $v) {
		$ceh->add_employees_to_company($k, $v);
	}

	
		$update_list = $ceh->get_update_list_for_emp_birthdates();
		
		
		$update_sql = "UPDATE person SET birth_date = ? WHERE uid = ?";
		
		if ($update_stmt = $db_conn->prepare($update_sql)) {
			
			
			foreach ($update_list as $update_entry) {
				
				echo_array( $update_entry, "UPDATE ENTRY", false);
				
				
				if ($update_stmt->bind_param("si", $update_entry["birth_date"], $update_entry["uid"])) {
					
					if ($update_stmt->execute()) {
						echo "\nUpdated successfully!";
					}
					else {
						echo "\nSQL Error -> " . $update_stmt->error;
					}
					
				}
				else
				{
					echo "\nSQL Error -> " . $update_stmt->error;
				}
				
				
				
				
				
				
			}
			
			
			
			
			
		}
		else {
			echo "\nSQL Error 1 -> " . $db_conn->error;
		}

	function echo_value( $val , $title) {
		echo "\n $title -> " . $val . "\n";
	}
	function echo_array( $arr, $title , $full ) {
		
		if (!$full) {
			echo "\n $title -> " . json_encode($arr) . "\n";
		}
		else {
			
			echo "\n\n\n $title -> \n";
			foreach($arr as $val) {
				echo "\n" . json_encode($val) . "\n";
			}
		}
	}

	
?>