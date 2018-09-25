<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php //если output_buffering не настроен, то во избежание ошибок здесь не могут быть пробелы или строки между открывающими и закрывающими тегами php, т.к. далее в коде есть redirect, до которого ни одного пробела быть выведено не должно.


	// must have an ID
	// должен иметься id
  if(empty($_GET['id'])) {
  	$session->message("No photograph ID was provided.");
    redirect_to('index.php');
  }
  
  $photo = Photograph::find_by_id($_GET['id']);
  // если имеется фото и оно может быть удалено, то
  if($photo && $photo->destroy()) {
	  
	  // Несмотря на удаление файла из файловой системы и записи о нем в базе данных методом $photo->destroy(), тем не менее в памяти php остается объект $photo со всеми своими атрибутами, что позволяет оперировать этими атрибутами на страницах, особенно в html.
	  // Объект удаляется самим php в конце скрипта или если мы сами его удалим.
    $session->message("The photo {$photo->filename} was deleted.");
    redirect_to('list_photos.php');
  } else {
    $session->message("The photo could not be deleted.");
    redirect_to('list_photos.php');
  }
  
 ?>
<?php if(isset($database)) { $database->close_connection(); } ?>