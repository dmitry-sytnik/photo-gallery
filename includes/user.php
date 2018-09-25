<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start. -
// Если это понадобится базе данных, то, вероятно, разумно потребовать ее, прежде чем мы начнем.
// require_once('database.php'); уже запрашивался в index.php, но это ничего страшного и полезно на всякий случай.
require_once(LIB_PATH.DS."database.php");

class User extends DatabaseObject {
	
	protected static $table_name="users";
	
	// это специальная переменная, которая будет хранить в массиве только те значения, которые соответствуют столбцам в базе данных
	protected static $db_fields = array('id', 'username', 'password', 'first_name', 'last_name');
	
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	
	public function full_name() {
		if (isset($this->first_name)&&isset($this->last_name)){		
		return $this->first_name." ".$this->last_name;
		} else {
			return "";
		}
	}
	
	// Если будет выполнена эта функция, то во время ее выполнения уже будет создан объект посредством функции find_by_sql, в самой которой вызывается instantiate, инициализирующая объект .
	public static function authenticate($username="", $password="") {
		global $database;
		$username = $database->escape_value($username);
		$password = $database->escape_value($password);

		$sql  = "SELECT * FROM users ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "LIMIT 1";
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
		// благодаря функции array_shift вытаскивается один объект с массивом своих переменных. 
		// (В принципе и не могло быть более одного объекта, т.к. запрашивалось LIMIT 1)
	}
	
	
			
}

?>