<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start. -
// Если это понадобится базе данных, то, вероятно, разумно потребовать ее, прежде чем мы начнем.
// require_once('database.php'); уже запрашивался в index.php, но это ничего страшного и полезно на всякий случай.
require_once(LIB_PATH.DS."database.php");

class DatabaseObject {
	
	// I'm waiting for Late Static Bindings in PHP 5.3
	// http://www.php.net/lsb
	
	// Часто используемые методы базы данных
	
	public static function find_all() {		
		return static::find_by_sql("SELECT * FROM ".static::$table_name);
		// $table_name определяется в наследуемом классе, нет необходимости определять её здесь.
    }
	
	public static function count_all() {
		global $database;
		// Внимательно к синтаксису: после FROM должен быть пробел
		$sql = "SELECT COUNT(*) FROM ".static::$table_name;
		// Чтоб понять работу этого кода, надо помнить, как выглядит таблица, выдаваемая MySQL в результате этого запроса.
		// Этот запрос выдаёт строку, но в ней только один элемент строки: ячейка с числом. Тем не менее, мы не можем иметь доступ непосредственно к числу, мы имеем доступ только к строке или ее элементам. Поэтому мы ,как обычно, выбираем набор результатов в $row (while нам не нужно, т.к. нам достаточно выбрать первую строку).
		$result_set = $database->query($sql);
		$row = $database->fetch_array($result_set);
		// А после этого мы выбираем первый элемент из строки. Он, вообще-то там единственный, но он нам и нужен. Так нам этот метод вернёт значение (число) из этого элемента.
		return array_shift($row);
	}
	
	public static function find_by_id($id=0) {
		global $database;
    $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE id=".$database->escape_value($id)." LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
  }    
	
    public static function find_by_sql($sql="") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = static::instantiate($row);
			// здесь запускается instantiate. она создает объект с переменными. Каждая вытащенная из базы данных строка $row теперь будет существовать как объект со своими переменными, имеющими определенное значение.
			// все объекты помещаются в массив
		}
		return $object_array;
		// судя по всему, возвращается сложный массив объектов, каждый со своим массивом переменных
    }	
	
	// Эта функция будет запущена в public find_by_sql().
	// Эта функция сама по себе инициализирует новый объект класса User.
	// Эта функция присваивает переменным объекта формальные значения. Например $object->id = $record['id'], $object->username = $record['username'].
	// Эти значения она определяет через цикл foreach, который перебирает все атрибуты. Условно говоря, значения прям здесь не присваиваются. Они будут присвоены, когда выполнится код instantiate ($row) в цикле while в функции find_by_sql($sql=""). Получится $object->id = $row['id'], $object->username = $row['username'] и т.д.
	// Эта функция "заточена" на работу с массивами строк, вытащенных из базы данных. Все подряд данные, предложенные ей, она понимать не будет. Но отлично понимает ассоциативные массивы с уже заданными ключами и значениями.
	private static function instantiate ($record) {
		// Следует также прверять, что record - это массив и в нём есть записи.
		
		//$class_name = get_called_class();
		//$object = new $class_name;
		// либо так, более правильно:
		$object = new static;
		
		// Простой, но долгий способ
		//$object->id = $record['id'];
		//$object->username = $record['username'];
		//$object->password = $record['password'];
		//$object->first_name = $record['first_name'];
		//$object->last_name = $record['last_name'];
				
		// Более динамичный, короткий способ
		foreach($record as $attribute=>$value) {
			if($object->has_attribute($attribute)) {
			// если объект имеет такой же по названию атрибут, как и ключ в массиве $record (в $record подставляется строка из базы данных), то
				// переменной объекта (заметим, что используется два знака доллара $object->$) с таким схожим названием присваиваем значение ключа $value из массива
				$object->$attribute = $value;
			}
		}
		// в этой функции создаётся объект и возвращается объект (с атбрибутами)		
		return $object;
	}

	private function has_attribute($attribute) {
	  // get_object_vars returns an associative array with all attributes (incl. private ones!)...
	  // - get_object_vars возвращает ассоциативный массив со всеми атрибутами (и включает также сюда приватные атрибуты!)
	  // поиск ведётся по текущему объекту ($this)
	  $object_vars = get_object_vars($this);
	  
	  // возможно, можно использовать другой код из подкласса user
	  // $object_vars = $this->attributes();
	  
	  // We don't care about the value, we just want to know if the key exists - Мы не заботимся о значении, мы просто хотим знать, существует ли ключ	  
	  return array_key_exists($attribute, $object_vars);
	  // проверяется, есть ли ключ attribute в массиве object_vars
	  // Will return true or false - Вернет true или false
	}

	// возвращение атрибутов без эскэйпинга
	protected function attributes() {		
		// вернет ассоциативный массив атрибутов ключей и их значений
		
		// вернет переменные объекта как атрибуты
		// return get_object_vars($this)
		
		// неудобство get_object_vars() в том, что она без разбора воспринимает все переменные, перечисленные в классе. Мы используем этот набор для обращения к базе данных в функциях сreate, update. Поэтому сюда ненарочно могут попасть названия переменных, которым не соответствуют никакие столбцы в базе данных, что приведет к ошибкам.
		// так же ко "всем переменным" относятся и приватные, и защищенные переменные.
		// Поэтому используем специальную переменную $db_fields, которая оперирует только с нужными названиями переменных
	
		$attributes = array();
		foreach(static::$db_fields as $field) {
			if(property_exists($this, $field)) {
			  $attributes[$field] = $this->$field;
			  // используется динамическое обращение $this->$field с ->$. Изначально ведь не существует атрибута field в объекте, поэтому обращение $this->field не будет иметь смысла. Здесь же $field постоянно сменяет название, и обращение то к одному названию переменной, то к другому (а они присутствуют в объекте) уже будет иметь смысл. Причем переменные присутствуют в объекте со своими значениями, поэтому здесь мы получаем полноценный ассоциативный массив.
			}
		}
		return $attributes;
				
	}
	
	// возвращение атрибутов с эскэйпингом	
	protected function sanitized_attributes() {
		// эскэйпингу подвергаются только значения ($value)
		// здесь берутся пары ключ-значение из текущего объекта и они просматриваются заново уже с эскэйпом значений
		
	  global $database;
	  $clean_attributes = array();
	  // sanitize the values before submitting
	  // Note: does not alter the actual value of each attribute
	  foreach($this->attributes() as $key => $value){
	    $clean_attributes[$key] = $database->escape_value($value);
	  }
	  return $clean_attributes;
	}
	
	public function save() {
	  // A new record won't have an id yet.
	  // Если id уже задана, то выполняй update, если не задана - create
	  return isset($this->id) ? $this->update() : $this->create();
	}
	
	public function create() {
		global $database;
		// Не забывайте синтаксис SQL и хорошие привычки:
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - одиночные кавычки вокруг всех значений
		// - escape всех значений, чтобы предотвратить SQL-инъекцию
		$attributes = $this->sanitized_attributes();
		
		$sql = "INSERT INTO ". static::$table_name ." (";
		//$sql .= "username, password, first_name, last_name";
		// этот код можно заменить на код с join
		// здесь join перечисляет ключи в массиве $attributes.
		$sql .= join(", ", array_keys($attributes));
		// join 
		$sql .= ") VALUES ('";
		/*
		$sql .= $database->escape_value($this->username) ."', '";
		$sql .= $database->escape_value($this->password) ."', '";
		$sql .= $database->escape_value($this->first_name) ."', '";
		$sql .= $database->escape_value($this->last_name) ."')";
		*/
		// этот код можно заменить на код с join
		// здесь join перечисляет значения в массиве $attributes.
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
		
		if($database->query($sql)) { // если запрос прошёл успешно, то
		// получаем в переменную текущего объекта значение только что вставленного id	
	    $this->id = $database->insert_id();
		// просто возвращаем true этим методом
	    return true;
	  } else {
		 // просто возвращаем false этим методом 
	    return false;
	  }		
	}
	
	public function update() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
			$attribute_pairs[] = "{$key}='{$value}'";
		}		
		
		$sql = "UPDATE ". static::$table_name ." SET ";
		/*
		$sql .= "username='". $database->escape_value($this->username) ."', ";
		$sql .= "password='". $database->escape_value($this->password) ."', ";
		$sql .= "first_name='". $database->escape_value($this->first_name) ."', ";
		$sql .= "last_name='". $database->escape_value($this->last_name) ."' ";
		*/
		// этот код можно заменить на код с join
		// здесь join перечисляет значения ключей. Ключи в $attribute_pairs - 0,1,2,3 . Значения "{$key}='{$value}'". Эти переменные наполняются значениями из массива $attributes ($attributes as $key => $value). Т.о., на выводе получаются только пары $key=$value.
		$sql .= join(", ", $attribute_pairs);
		
		
		$sql .= " WHERE id=". $database->escape_value($this->id);
		
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}
	
	public function delete() {
		global $database;
		// Не забывайте синтаксис SQL и хорошие привычки:
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape всех значений, чтобы предотвратить SQL-инъекцию
		// - используйте LIMIT 1
		
	  $sql = "DELETE FROM ". static::$table_name;
	  $sql .= " WHERE id=". $database->escape_value($this->id);
	  $sql .= " LIMIT 1";
	  $database->query($sql);
	  return ($database->affected_rows() == 1) ? true : false;
	  
		// Однако удаление из базы данных, не удаляет сам объект из PHP. 
		// Поэтому к объекту в PHP, его атрибутам, доступ в коде остаётся.
	
		// NB: After deleting, the instance of User still 
		// exists, even though the database entry does not.
		// This can be useful, as in:
		//   echo $user->first_name . " was deleted";
		// but, for example, we can't call $user->update() 
		// after calling $user->delete().	
	}
}