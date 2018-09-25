<?php
// require_once("config.php");
require_once( LIB_PATH.DS. "config.php");

class MySQLDatabase {
	
	private $connection;
	//public $last_query;
	
	//private $magic_quotes_active;
	//private $real_escape_string_exists;
	
	function __construct() {
		$this->open_connection();
		//$this->magic_quotes_active = get_magic_quotes_gpc();
		//$this->real_escape_string_exists = function_exists( "mysql_real_escape_string" ); // i.e. PHP >= v4.3.0
	}
	
	public function open_connection() {	
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS,DB_NAME);
		if(mysqli_connect_errno()) {
			die("Database connection failed: " . 
			mysqli_connect_error() . 
			" (" . mysqli_connect_errno() . ")"
			);
		}
	}

	public function close_connection() {
		if(isset($this->connection)) {
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}
	
	public function query($sql) {
		//$this->last_query = $sql;
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;
	}
	
	public function escape_value($string) {
		
	$escaped_string = mysqli_real_escape_string($this->connection, $string);
		return $escaped_string;
	}
	
	// Database-нейтральные методы
	public function fetch_array($result_set) {
    return mysqli_fetch_array($result_set);
  }

	public function num_rows($result_set) {
		return mysqli_num_rows($result_set);
	}
  
	public function insert_id() {
		// get the last id inserted over the current db connection
		return mysqli_insert_id($this->connection);
	}
  
	public function affected_rows() {
		return mysqli_affected_rows($this->connection);
	}
	
	private function confirm_query($result) {
  	if (!$result) {
  		die("Database query failed.");
  	}
  }
	
	/*
	private function confirm_query($result) {
		if (!$result) {
			$output = "Database query failed: ".mysqli_error()."<br /><br />";
			// Следующий код $output следует закомментировать при выкладке на сайт
			$output.= "Last SQL query: ".$this->last_query;
			die($output);
		}
	}
	*/
}

$database = new MySQLDatabase();
$db =& $database;
// Закрытие соединения: $database->close_connection();

?>