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
	
	$get_emp_sql = "SELECT * FROM user_person_view";
	
	if ($result = $db_conn->query($get_emp_sql)) {
		
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			
			$emp = new Employee();
			
			$emp->load_properties_with_sql( $row );
			
			$ceh->add_employee( $emp );
		}
	}
		

		$ceh->load_insert_values();
		
		$insert_vals = $ceh->get_comp_emp_insert_values();
		
		
		$insert_ce_sql = "INSERT INTO company_employees (eid, cid, start_date) VALUES ( ? , ? , ? )";
		
		if(	$insert_ce_stmt = $db_conn->prepare($insert_ce_sql)) {
			
			foreach( $insert_vals as $ce_insert_val) {
				
				$insert_ce_stmt->bind_param("iis", $ce_insert_val["eid"], $ce_insert_val["cid"], $ce_insert_val["date_started"]);
				
				if ($insert_ce_stmt->execute()) {
					
					echo "\nSuccessfully inserted the value";
				}
				else {
					echo "couldn't execute " . $insert_ce_stmt->error;
				}
			}	
		}
		else {
			echo "couldn't prepare -> " . $db_conn->error;
		}
	
	
?>