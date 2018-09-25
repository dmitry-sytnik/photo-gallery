<?php // хитрость функций и методов в том, что они могут выполнять какой-то код, делать работу, а возвращать просто true или false или же пустую строку, или же что-то другое, а не результат своей работы, или же ничего не возвращать, а просто делать работу. 
// Пример функции, которая делает работу,но ничего не возвращает: public function login($user) в session.php или public function logout() там же.
// Когда возвращается true или false, то тогда в коде можно одновременно выполнять функцию и оперировать с ее возвращенным "истина" или "ложь". Например, delete() выполняет запрос к базе данных, удаляет там строку, а возвращает true, если получилось, или false, если не получилось. Тогда в коде можно вызывать if (delete()) {} и это выполнит действия с базой данных и уже сразу вернет в код "истина" или "ложь", с которой можно работать.
// Не забываем, что функция возвращает (return) что-то одно и при этом первое, что подпало под истинные условия, остальное отсекается. При необходимости возвращать много значений, нужно возвращать массив; но опять-таки он будет возвращен единожды для функции.

function strip_zeros_from_date($marked_string="") {
		// удаляем помеченные нули
		// str_replace находит все звездочки с нулями и заменяет их на пустоту.
		$no_zeros = str_replace ('*0', '', $marked_string);
		// у полученного варианта очищаем также варианты звездочки с ноябрем и декабрем, которые иначе будут выглядеть как *1 в начале (*11  *12).
		$cleaned_string = str_replace('*', '', $no_zeros);
		return $cleaned_string;		
	}
	
function redirect_to ($location = NULL) {
	if ($location != NULL) {
		header("Location: {$location}");
		exit;
	}
}

function output_message($message="") {
	if (!empty($message)) {
		return "<p class=\"message\">{$message}</p>";
	} else {
		return "";
	}	
}

function __autoload($class_name) {
    $class_name = strtolower($class_name);
    $path = LIB_PATH.DS."{$class_name}.php";
    if(file_exists($path)) {
    	require_once($path);
    } else {
    	die ("The file {$class_name}.php could not be found.");
    }
}
	
function include_layout_template($template="") {
	include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}	

function log_action($action, $message="") {
	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	$new = file_exists($logfile) ? false : true;
	// если файл существует, то переменная $new получает false
	
	if($handle = fopen($logfile, 'a')) {// если файл открывается, расположить курсор для записи в конец файла
	// благодаря атрибуту "а" (append) файл будет создан, если еще не существует и будет просто открыт с курсором в конце, если уже существует. 
	$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
	// текущее время, красиво оформленное
	
	// подготавливаем, как будет выглядеть контент для записи в лог-файл: время, акт и сообщение
	$content = "{$timestamp} | {$action}: {$message}\r\n";
	
	// записываем после курсора контент
	fwrite($handle, $content);
	// закрываем файл
	fclose($handle);
	
	// если файл теперь существует, распространяем на него права 755
	if($new) { chmod($logfile, 0755); }
	} else {		
		// иначе, если файл не открывается и не получается разместить курсор в конце файла, то
		echo "Could not open log file for writing";
	}	
}

function datetime_to_text($datetime="") {
  $unixdatetime = strtotime($datetime);
  // strtotime - Преобразует текстовое представление даты на английском языке в метку времени Unix
  return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
}



?>