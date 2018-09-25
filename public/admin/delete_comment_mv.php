<?php // mv - моя версия, самостоятельное задание
 		require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php //если output_buffering не настроен, то во избежание ошибок здесь не могут быть пробелы или строки между открывающими и закрывающими тегами php, т.к. далее в коде есть redirect, до которого ни одного пробела быть выведено не должно.


	// must have an ID
	// должен иметься id
  if(empty($_GET['id'])) {
  	$session->message("No comment ID was provided.");
    redirect_to('list_photos.php');
  }
  
  $comment=Comment::find_by_id($_GET['id']);
  
  // если имеется комментарий и он может быть удален, то
  if($comment && $comment->delete()) {
	  $session->message("The comment of {$comment->author} was deleted.");
    redirect_to("comment_photo.php?id={$comment->photograph_id}");
    } else {
		$session->message("The comment of {$comment->author} could not be deleted.");
    redirect_to("comment_photo.php?id={$comment->photograph_id}");
		
		
	}  





?>
<?php if(isset($database)) { $database->close_connection(); } ?>