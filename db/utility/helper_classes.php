<?php
class CompanyEmployeeHelper
{
	public $companies = array();
	public $employees = array();
	
	public function add_employees_to_company( $cid , $emp_info ) {
		
		$company;
		
		for ($i = 0; $i < count($this->companies); $i++) {
			
			$comp = $this->companies[$i];
			
			if ($comp->cid == $cid) {
				$company = $comp;
			}
		}
		
		
		if (isset($company)) {
			
			unset($company->employees);
			$company->employees = array();
			
			foreach ($emp_info as $info_bit) {
				
				echo "\n\n Info Bit -> " . json_encode($info_bit) . "\n\n";
				$new_emp = new Employee();	
				
				$new_emp->birthDate = $info_bit["birth_date"];
				$new_emp->uid = $info_bit["uid"];
				
				array_push($company->employees, $new_emp);
			}
		}
		
		
		
		
		
		
	}
	public function get_select_uid_list_for_companies() {
		$select_list = array();
		
		$comp_count = count($this->companies);
		
		for ($i = 0; $i < $comp_count; $i++) {
			
			$comp = $this->companies[$i];
			
			
			array_push($select_list, $comp->cid);
			
		}
		
		
		
		return $select_list;	
	}
	public function get_update_list_for_emp_birthdates() {
		
		$this->update_emp_birthdates_to_match_company();
		
		$ret_array = array();
		
		$total_comps = count($this->companies);
		
		for ($i = 0; $i < $total_comps; $i++) {
			
			$comp = $this->companies[$i];
			
			$average_age = $comp->average_age;
			
			
			$emp_count = count($comp->employees);
			
			for ($j = 0; $j < $emp_count; $j++) {
				
				$emp = $comp->employees[$j];
				
				
				$emp_arr = array(
					"uid" => $emp->uid,
					"birth_date" => $emp->birthDate
				);
				
				array_push($ret_array, $emp_arr);
			}
			
			
		}
		
		return $ret_array;
	}
	
	private function update_emp_birthdates_to_match_company() {
		
		
		$total_comps = count($this->companies);
		
		for ($i = 0; $i < $total_comps; $i++) {
			
			$comp = $this->companies[$i];
			
			$average_age = $comp->average_age;
			
			
			$emp_count = count($comp->employees);
			
			for ($j = 0; $j < $emp_count; $j++) {
				
				$emp = $comp->employees[$j];
				
				$emp->birthdate = $this->get_random_birthdate_for_avg_age( $average_age );
				
			}
			
			
		}
	}
	private function get_random_birthdate_for_avg_age( $avg_age ) {
		
		$low_range_min = $avg_age - 10;
		if ($low_range_min < 18) {
			$low_range_min = 18;
		}
		$low_range_max = $avg_age;
		
		$mid_range_min = $avg_age - 1;
		$mid_range_max = $avg_age + 2;
		
		$high_range_min = $avg_age;
		$high_range_max = $avg_age + 8;
		
		
		$rand_float = $this->frand_v2(0.0, 1.0);
		
		echo "\nThe rand float is -> " . $rand_float . "\n";
		
		$rand_year;
		
		if ($rand_float >= 0 && $rand_float <= 65) {
			$rand_year = rand($mid_range_min, $mid_range_max);
		}
		else if ($rand_float > 65 && $rand_float <= 85) {
			$rand_year = rand($low_range_min, $low_range_max);
		}
		else {
			$rand_year = rand($high_range_min, $high_range_max);
		}
		
		
		$rand_year_days = $this->convert_years_to_days( $rand_year );
		
		if ($this->random_bool()) {
			$rand_year_days += $this->random_day_in_year();
		}
		else {
			$rand_year_days -= $this->random_day_in_year();
		}
		
		$date = date('Y-m-d', strtotime("-" . $rand_year_days . " days"));
		
		echo "\n\nRandom date -> " . $date . " for average_age -> " . $avg_age;
		
		return $date;
	}
	public function add_employee( $emp ) {
		array_push($this->employees, $emp );
	}
	
	public function add_company( $comp ) {
		array_push($this->companies, $comp );
	}
	
	public function load_insert_values() {
		
		$this->setup_weights();
		$this->place_emps_in_companies();
	}
	private function setup_weights() {
		
		$set_weights  = array();
		$unset_weights = array();
		
		$running_weight_total = 0.0;
		
		for ($i = 0; $i < count($this->companies); $i++){
			
			$company = $this->companies[$i];
			
			if ($company->isWeightSet()) {
				$running_weight_total += $company->getWeight();
				array_push($set_weights, $company);
			}	
			else {
				array_push($unset_weights, $company);
			}
		}
		
		$remaining_weight = 1.0 - $running_weight_total;
		
		$perc_diff = $remaining_weight / count($unset_weights);
		
		$perc = 0.0;
		echo "\nPercent Diff -> " . $perc_diff . "  Remaining Weight -> " . $remaining_weight . " Running Weight Total -> " . $running_weight_total . "\n";
		
		for ($i = 0; $i < count($unset_weights); $i++) {
			
/*
			$perc_bottom = $perc;
			$perc_top = $perc + $perc_diff;
			
			$rand_perc = $this->frand_v2($perc_bottom, $perc_top);
			
			$weight = $remaining_weight * $rand_perc;
		*/	
			$comp = $unset_weights[$i];
		
			$comp->setWeight( $perc_diff );
			
			array_push($set_weights, $comp);
		}
		
		$this->companies = $set_weights;
		$emp_count = count($this->employees);
		
		for ($i = 0; $i < count($set_weights); $i++) {
			
			$comp = $set_weights[$i];
			$weighted_count_val = floor( $comp->getWeight() * $emp_count);
			echo "\nWeighted Count -> " . $weighted_count_val . "\n";
			$comp->weighted_count = $weighted_count_val;
			
		}
		
		$this->companies = $set_weights;
	}
	private function frand_v2($min, $max) {
		$rand = $this->frand($min * 100.0, $max * 100.0, 0);
		
		$real_rand = $rand / 100.0;
		
		return $real_rand;
	}
	private function frand($min, $max, $decimals = 0) {
		$scale = pow(10, $decimals);
		return mt_rand($min * $scale, $max * $scale) / $scale;
	}
	public function get_comp_emp_insert_values () {
		
		$insert_vals = array();
		
		$comp_count = count($this->companies);
		
		for ($i = 0; $i < $comp_count; $i++) {
			
			$comp = $this->companies[$i];
			
			$emp_count = count($comp->employees);
			
			for ($j = 0; $j < $emp_count; $j++) {
			
				$emp = $comp->employees[$j];
				
				$info_array = array(
					"eid" => $emp->uid,
					"cid" => $comp->cid,
					"date_started" => $this->get_random_date( $comp->age_profile )
				);
				echo "\n" . json_encode($info_array) . "\n";
				
				array_push($insert_vals, $info_array);
			}
		
		
		}
		
		return $insert_vals;
	}
	
	private function get_random_date( $age_profile ) {
		
		
		
		$date_ranges = array(
			array(
				"min" => 1,
				"max" => 4
			),
			array(
				"min" => 5,
				"max" => 10
			),
			array(
				"min" => 10,
				"max" => 50
			)
		);
		
		
		$used_array;
		
		switch ($age_profile) {
			case "young":
				$used_array = $date_ranges[0];
			break;
			
			case "medium":
				$used_array = $date_ranges[1];
			break;
			
			case "old":
				$used_array = $date_ranges[2];
			break;
		}
		
		
		$rand_year = rand($used_array["min"], $used_array["max"]);
		
		$rand_year_days = $this->convert_years_to_days( $rand_year );
		
		if ($this->random_bool()) {
			$rand_year_days += $this->random_day_in_year();
		}
		else {
			$rand_year_days -= $this->random_day_in_year();
		}
		
		$plus_minus = $this->random_plus_minus();
		
		return date('Y-m-d', strtotime("-" . $rand_year_days . " days"));
	}
	private function convert_years_to_days( $years ) {
		
		return $years * 365;
	}
	private function random_day_in_year() {
		
		return rand(1, 365);
	}
	private function random_plus_minus() {
		
		if ($this->random_bool()) {
			return "-";
		}
		else {
			return "+";
		}
	}
	private function random_bool() {
		
		$rand_int = rand(0, 1);
		
		if ($rand_int == 0) {
			return true;
		}
		else {
			return false;
		}
	}
	private function place_emps_in_companies() {
		$total_index = 0;
		
		shuffle($this->employees);
		$total_emps = count($this->employees);
		$total_comps = count($this->companies);
		echo "\nTotal Employees -> " . count($this->employees) . "\n";
		
		echo json_encode($this->employees);
		
		for ($i = 0; $i < $total_comps;$i++) {
			
			$company = $this->companies[$i];
			
			echo "\n" . json_encode($company) . "\n";
			
			if ($i == count($this->companies) - 1) {
				$jay_value = $total_emps - $total_index + 1;
			}	
			else {
				$jay_value = $company->weighted_count;
			}
			
			
			for ($j = 0; $j < $jay_value; $j++) {
				
					if ($total_index < $total_emps) {
						
					$emp = $this->employees[$total_index];
							
					array_push($company->employees, $emp);
					
					$total_index++;
				}
			}
		}
		
		echo "\n Total count of companies -> " . count($this->companies) . "\n";
		
		for ($i = 0; $i < count($this->companies); $i++) {
			
			$comp = $this->companies[$i];
			
			$employee_count = count($comp->employees);
			
			echo "\nCompany Name -> " . $comp->companyName . " Employee Count -> " . count($comp->employees) . "  Weight -> " . $comp->getWeight();
		}
	}
	
	

}
class WeightedValue {
	
	private $weight;
	
	public function setWeight( $newWeight ) {
		
		if ($newWeight >= 0 && $newWeight <= 100) {
			$this->weight = $newWeight;
		}
	}
	
	public function getWeight() {
		return $this->weight;
	}
	
	public function isWeightSet() {
		if (isset($this->weight)) {
			return true;
		}
		else {
			return false;
		}
	}
}	

class Company extends WeightedValue {
	
	
	public $weighted_count;
	
	//	Parent Functions
	public function setWeight( $newWeight ) {
		
		parent::setWeight( $newWeight );
	}
	
	public function getWeight() {
		return parent::getWeight();
	}
	
	public function isWeightSet() {
		return parent::isWeightSet();
	}
	
	//	Main Attributes
	public $companyName;
	public $cid;
	public $employees = array();
	public $age_profile;
	public $average_age;
	
	public function set_values( $name, $id ) {
		
		//	Default Weights
		switch ($name) {
			case "Microsoft":
			case "Google":
				$this->setWeight(0.15);
				$this->age_profile = "young";
				$this->average_age = 22;
			break;
			
			case "Linked In":
				$this->setWeight( 0.10 );
				$this->age_profile = "old";
				$this->average_age = 41;
			break;
			
			default:
				$this->age_profile = "medium";
				$this->average_age = 30;
				break;
				
		}
		
		$this->companyName = $name;
		$this->cid = $id;
	}
}

class Employee {

	public $uid;	
	public $fname;
	public $lname;
	public $mname;
	public $birthDate;
	public $gender;
	public $guarded = false;
	
	public function load_properties_with_sql( $row_assoc ) {
		
		$this->uid = $row_assoc["uid"];
		$this->fname = $row_assoc["firstname"];
		$this->mname = $row_assoc["middlename"];
		$this->lname = $row_assoc["lastname"];
		$this->birthDate = $row_assoc["birth_date"];
		$this->gender = $row_assoc["gender"];
		
		if ($this->lname == "Forsythe") {
			$this->guarded = true;
		}
	}
}

class ArrayHelper {
	
	public $main_array;
	
	
	public function get_randomized_array() {
		
		$random_array = array();
		
		
		for ($i = 0; $i < count($this->main_array); $i++) {
		
			array_push($random_array, $this->main_array[$i]);	
		}
			
		if (shuffle($random_array)) {
				
			return $random_array;	
		}
		else
		{
			return $random_array;
		}
		
		
	}
	
}
?>