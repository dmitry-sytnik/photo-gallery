<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) {
redirect_to("login.php"); } ?>
<?php

	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	////
	// запись в лог совершается в login.php
	////
	// очистка файла лога
	////	
	if($_GET['clear'] == 'true') { // если была нажата ссылка "Clear log file", то
	    // очистка
		file_put_contents($logfile, '');
	  
	  // Добавляем первую запись в лог
	  log_action('Logs Cleared', "by User ID {$session->user_id}");
    
	// redirect to this same page so that the URL won't 
    // have "clear=true" anymore
	// - перезапускаем этот файл для пользователя
    redirect_to('logfile.php');
    }
	
	
?>

<?php include_layout_template('admin_header.php'); ?>

<a href="index.php">&laquo; Back</a><br />
<br />

<h2>Log File</h2>

<p><a href="logfile.php?clear=true">Clear log file</a><p>

<?php
	////
	// вывод на экран лог файла
	////
  if( file_exists($logfile) && is_readable($logfile) && 
			$handle = fopen($logfile, 'r')) {  // если файл сущесвует, доступен для чтения и смогли открыть для чтения, то
    echo "<ul class=\"log-entries\">";
		while(!feof($handle)) { // пока не наступит конец файла
			$entry = fgets($handle); // получаем строки. fgets берет только одну строку.
			if(trim($entry) != "") { // и если получаемая строка в цикле после очищения от лишних пробелов не является пустой строкой, то выводим всё на экран списком
				echo "<li>{$entry}</li>";
			}
		}
		echo "</ul>";
    fclose($handle); // закрываем файл
  } else { // сообщение в случае проблем
    echo "Could not read from {$logfile}.";
  }

?>

<?php include_layout_template('admin_footer.php'); ?>
