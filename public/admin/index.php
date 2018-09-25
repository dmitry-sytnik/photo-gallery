<?php
require_once("../../includes/initialize.php");

// в 27 строке, в session.php, я описал этот момент. Если функция is_logged_in() возвращает true, значит была уже задана сессия (на странице есть SESSION), иначе она возвращает false, сессии не было. Эту проверку производит функция check_login() там. 
if (!$session->is_logged_in()) { redirect_to("login.php"); }
 else { // echo $session->is_logged_in(); 
}
?>

<?php include_layout_template('admin_header.php'); ?>

	<h2>Menu</h2>
	
<?php echo output_message($message); 
	// Описание, как работает обращение к $message дано в photo_upload.php
?>	
	  <ul>
		<li><a href="list_photos.php">List Photos</a></li>
		<li><a href="logfile.php">View Log file</a></li>
		<li><a href="logout.php">Logout</a></li>
	  </ul>
		
	</div>
		
<?php include_layout_template('admin_footer.php'); ?>