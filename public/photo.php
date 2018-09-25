<?php require_once("../includes/initialize.php");?>
<?php
	if(empty($_GET['id'])) {
    $session->message("No photograph ID was provided.");
    redirect_to('index.php');
  }
  
  $photo = Photograph::find_by_id($_GET['id']);
  
	if(!$photo) {
		$session->message("The photo could not be located.");
		redirect_to('index.php');
	}
	
	// обработка формы комментариев
	if(isset($_POST['submit'])) { // если форма отправлена
		$author = trim($_POST['author']);
		$body = trim($_POST['body']);
		
		$new_comment = Comment::make($photo->id, $author, $body);
		
		// если $new_comment существует и метод save() возвращает истину
		if($new_comment && $new_comment->save()) {
			// comment saved
			// Нет необходимости в сообщении $message; видя комментарий, это достаточное доказательство в успешном сохранении комментария.
			
			// Отправка e-mail
			$new_comment->try_to_send_notification();
			
			
	    redirect_to("photo.php?id={$photo->id}");
			// Теперь - это не запрос POST, а запрос GET. Зачем это нужно:
			//В Браузере будут заполнены поля комментария и автора. Если браузер "обновить", он спросит "вы уверены, что хотите повторить отправку формы?". Невнимательный или торопливый пользователь, нажмет "да", и тогда комментарий будет отправлен дважды.
			// За счет редиректа мы сами перезагружаем эту страницу, причем запросом GET, и избавляемся от этой ощибки.
			
		} else {
			// Failed
		$message = "There was an error that prevented the comment from being saved.";	
		}
		
	} else { // если форма не отправлялась
		$author = "";
		$body = "";
	}
	
	// в $comments будут помещены все объекты-комментарии текущей фотографии. Ниже мы их отобразим в html.
	
	$comments = $photo->comments();
	// Это идентично
	// $comments=Comment::find_comments_on($photo->id)
?>
<?php include_layout_template('header.php');?>

<a href="index.php">&laquo; Back</a><br />
<br />


<div style="margin-left: 20px;">
	<img src="<?php echo $photo->image_path(); ?>" />
	<p><?echo $photo->caption; ?></p>
</div>

<!-- список комментариев -->

<div id="comments">
  <?php foreach($comments as $comment): ?>
    <div class="comment" style="margin-bottom: 2em;">
	    <div class="author">
	      <?php echo htmlentities($comment->author); ?> wrote:
	    </div>
        <div class="body">
		  <?php echo strip_tags($comment->body, '<strong><em><p><br>'); 
		  //Второй необязательный параметр для указания тегов, которые не нужно удалять
		  ?>
		</div>
	    <div class="meta-info" style="font-size: 0.8em;">
	      <?php echo datetime_to_text($comment->created); ?>
	    </div>
    </div>
  <?php endforeach; ?>
  <?php if(empty($comments)) { echo "No Comments."; } ?>
</div>


<!-- форма комментария -->

<div id="comment-form">
  <h3>New Comment</h3>
  <?php echo output_message($message); ?>
  <form action="photo.php?id=<?php echo $photo->id; ?>" method="post">
    <table>
      <tr>
        <td>Your name:</td>
        <td><input type="text" name="author" value="<?php echo $author; ?>" /></td>
      </tr>
      <tr>
        <td>Your comment:</td>
        <td><textarea name="body" cols="40" rows="8"><?php echo $body; ?></textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" name="submit" value="Submit Comment" /></td>
      </tr>
    </table>
  </form>
</div>



<?php include_layout_template('footer.php'); ?>