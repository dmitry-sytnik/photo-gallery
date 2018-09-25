<?php
// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

//Сначала определяем разделитель папок

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
// DIRECTORY_SEPARATOR - это предопределенная переменная в PHP, "знающая", какой знак разделения папок используется в данный момент (В Windows\ и Unix/ - это различные слэши)

// defined('DS') спрашивает: определена переменная DS? Если да, то ничего делать не надо: null.
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

// Определяем путь до сайта
defined('SITE_ROOT') ? null : 
	define('SITE_ROOT', 'C:'.DS.'OSPanel'.DS.'domains'.DS.'photo-gallery'); 
	// D:\OSPanel\domains\photo-gallery

// Определяем абсолютный путь к папке includes	
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// Порядок подключения файлов важен
// Загрузка конфиг файла первым
require_once(LIB_PATH.DS.'config.php');
// Загрузка основных функций
require_once(LIB_PATH.DS.'functions.php');
// Загрузка объектов ядра (ключевых объектов)
require_once(LIB_PATH.DS.'session.php');
require_once(LIB_PATH.DS.'database.php');
require_once(LIB_PATH.DS.'database_object.php');
require_once(LIB_PATH.DS.'pagination.php');

// Так подключается PHPmailer
// https://github.com/PHPMailer/PHPMailer
// У меня не заработал. Папка была переменована PHPMailer 6.0
/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once(LIB_PATH.DS."PHPMailer".DS."src".DS."Exception.php");
require_once(LIB_PATH.DS."PHPMailer".DS."src".DS."PHPmailer.php");
require_once(LIB_PATH.DS."PHPMailer".DS."src".DS."SMTP.php");
require_once(LIB_PATH.DS."PHPMailer".DS."language".DS."phpmailer.lang-ru.php");
*/

// Загрузка классов, связанных с базой данных
require_once(LIB_PATH.DS.'user.php');
require_once(LIB_PATH.DS.'photograph.php');
require_once(LIB_PATH.DS.'comment.php');

?>