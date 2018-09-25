<?php // моя версия, самостоятельное задание
require_once("../../includes/initialize.php"); ?>
<?php if (!$session->is_logged_in()) { redirect_to("login.php"); } ?>
<?php

	if(empty($_GET['id'])) {
		//$session->message("No comments for this ID.");
		redirect_to('list_photos.php');
	}
	
	$photo = Photograph::find_by_id($_GET['id']);

	if($photo) {
		// в $comments будут помещены все объекты-комментарии текущей фотографии. Ниже мы их отобразим в html.
		$comments = $photo->comments();
		
		// Это идентично
		// $comments=Comment::find_comments_on($photo->id)
	} else {
		redirect_to("list_photos.php");
	}
	
	if(!$comments) {
		$message.="<br>";
		$message.=" Нет комментариев.";
	}
?>
<?php include_layout_template('admin_header.php'); ?>

<a href="list_photos.php">&laquo; Back</a><br />
<br />

<h2>My vers. Comments</h2>

<div>
<img src="../<?php echo $photo->image_path(); ?>" width="100" />
<p><?echo $photo->caption; ?></p>
</div>

<?php echo output_message($message); 
	// Описание, как работает обращение к $message дано в photo_upload.php
?>

<table class="bordered">
  <tr>
    <th>Author</th>
    <th>Created</th>
    <th>Body</th>
	<th>&nbsp;</th>
  </tr>
<?php foreach($comments as $comment): ?>
  <tr>
	<td><?php echo $comment->author; ?></td>
    <td><?php echo $comment->created; ?></td>
	<td><?php echo $comment->body; ?></td>
	<td><a href="delete_comment_mv.php?id=<?php echo $comment->id; ?>">Delete</a></td>
  </tr>
<?php endforeach; ?>
</table>
<br />


<?php include_layout_template('admin_footer.php'); ?>