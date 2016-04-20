<?php
class QueryHelper
{
	public $query_string;
	public $filters = array();
	public $order_bys = array();
	public $limit;
	public $tables = new Tables();
	
	
	public function add_table_with_values( $table_name, $table_alias, $table_short_name ) {

		$tables->add_table($table_alias, $table_short_name, $table_name);
		
	}
	
	public function add_table_join( $tb_one_short_name, $table_two_short_name, $common_attribute ) {
		
		for ($i = 0; $i < count($tables->all_tables); $i++)
	}
	
	public function add_filter( $filter ) {
		
		$filters->add_filter( $filter );
	}
	
	
	
	
	
}
class Filters
{
	private $all_filters = array();
	
	
	public function add_filter( $filter ) {
		
		if (get_class($filter) == "QueryFilter") {
			array_push($all_filters, $filter);
		}
	}
	
	public function add_filter_with_values( $op , $col, $val ) {
		
		$new_filter = new QueryFilter();
		
		$new_filter->setup_with_values( $op , $col, $val );
		
		array_push($all_filters, $new_filter);
	}
}
class QueryFilter
{
	public $operator;
	public $column_name;
	public $value;
	
	public function setupValuesWithString( $valueString ) {
		
		
	}
	
	public function setup_with_values( $op, $col, $val ) {
		
		$isValidOp = check_operator( $op );
		
		$valid_op;
		
		if (!$isValidOp) {
			convert_url_exp_to_sql( $op );	
		}
		else {
			$valid_op = $op;
		}
		
		$operator = $valid_op;
		$column_name = $col;
		$value = $val;
		
	}
	
	public function printValues() {
		echo "Operator -> " . $operator "...Col Name -> " . $column_name . "... Value -> " . $value;
	}
	
	private function check_operator( $op ) {
		$isValid = true;
		
		switch($op) {
			case "<":
			case "<=":
			case ">":
			case ">=":
			case "!=":
			case "LIKE":
			case "like":
			case "=":
			break;
			
			default:
				$isValid = false;
			break;
		}
		
		return $isValid;
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
			
			default:
			break;
		}
		
		return $sql_exp;
	}
	
	
}
class TableJoin
{
	public $tableOne;
	public $tableTwo;
	public $commonAttribute;
	
	public function setupWithValues ($tableOne, $tableTwo, $commonAttribute) {
		
		$tableOne = $tableOne;
		$tableTwo = $tableTwo;
		$commonAttribute = $commonAttribute;
	}
}
class Tables
{
	public $all_tables = array();
	
	public function add_table($alias, $shortName, $table_name) {
		$newTable = new Table();
		
		$newTable->name = $table_name;
		$newTable->alias = $alias;
		$newTable->short_name = $shortName;
		
		array_push($all_tables, $newTable);
	}
	public function add_table( $table_name ) {
		
		$tb_count = count($all_tables);
		
		for ($i = 0; $i < $tb_count; $i++) {
			
			$curr = $all_tables[$i];
			
			if ($curr->tableName == $table_name) {
				
			}
			else {
				$newTable = new Table();
				
				$newTable->setTableName( $table_name );
				$newTable->tableAlias = $table_name;
				array_push($all_tables, $newTable);
			}
		}
	}
}
class Table
{
	public $name;
	public $alias;
	public $short_name;
	
	public function set_values($nm, $al, $sn) {
		$name = $nm;
		$alias = $al;
		$short_name = $sn;
	}
	
	public function setTableName( $table_name ) {
		
		if (strlen($table_name) >= 2) {
			$table_nickname = substr($table_name, 0, 2);
			
			$short_name = strtoupper($table_nickname);
		}
		
		$name = $table_name;
	}
}
?>