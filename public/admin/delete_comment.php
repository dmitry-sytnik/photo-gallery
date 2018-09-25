<?php require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php //если output_buffering не настроен, то во избежание ошибок здесь не могут быть пробелы или строки между открывающими и закрывающими тегами php, т.к. далее в коде есть redirect, до которого ни одного пробела быть выведено не должно.


	// must have an ID
	// должен иметься id
  if(empty($_GET['id'])) {
  	$session->message("No comment ID was provided.");
    redirect_to('index.php');
  }
  
  $comment = Comment::find_by_id($_GET['id']);
  // если имеется comment и оно может быть удалено, то
  if($comment && $comment->delete()) {
	  
	  // Несмотря на удаление в памяти php остается объект $comment со всеми своими атрибутами, что позволяет оперировать этими атрибутами на страницах, особенно в html.
	  // Объект удаляется самим php в конце скрипта или если мы сами его удалим.
    $session->message("The comment was deleted.");
    redirect_to("comments.php?id={$comment->photograph_id}");
  } else {
    $session->message("The comment could not be deleted.");
    redirect_to('list_photos.php');
  }
  
 ?>
<?php if(isset($database)) { $database->close_connection(); } ?>