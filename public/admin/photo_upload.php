<?php
require_once('../../includes/initialize.php');
if (!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php    // если здесь между закрывающим и открывающим тегом php будет пробел или пустая строка, то это может создать проблему нижеследующему редиректу и вызвать ошибку "заголовки уже отправлены", т.к. этот пробел уже будет выведен с помощью html. 
	$max_file_size = 1048576;   // expressed in bytes
	                            //     10240 =  10 KB
	                            //    102400 = 100 KB
	                            //   1048576 =   1 MB
	                            //  10485760 =  10 MB
	
	
	if(isset($_POST['submit'])) {
		$photo = new Photograph();
		$photo->caption = $_POST['caption'];
		$photo->attach_file($_FILES['file_upload']);
		// attach_file() можно было бы предварить условием if с целью проверки, прикреплен ли файл успешно и нет ли ошибок. Ошибки добавляются в массив errors объекта и возвращается false. Иначе прописываются все переменные объекта и возвращается true.
		// Но это не обязяательно. Если будут ошибки, то следующее условное выражение с методом save(), покажет нам массив ошибок errors. 		
		
		// Если метод save выполняется(возвращает true), то
		if($photo->save()){
			// Success
			$session->message("Photograph uploaded successfully.");
			redirect_to('list_photos.php');
		} else {
			// Failure
			$message = join("<br />", $photo->errors);
		}
		
	}

?>

<?php include_layout_template('admin_header.php'); ?>

<a href="list_photos.php">&laquo; Back</a><br />
<br />

<h2>Photo Upload</h2>

<?php echo output_message($message); 
	// переменная $message задаётся благодаря /initialize.php в session.php строкой $message = $session->message(), где метод message() либо присваивает ей то, что уже есть в $message у объекта $session (вызван строкой выше в session.php) (это, видимо, может быть и пустая строка, если изначально ничего не задано в объекте), либо, если кто-то помещает строку в скобки $session->message(" "), как здесь выше, эта строка помещается в переменную $_SESSION['message'] согласно методу message(). Все эти действия присваивают значения переменной $message, которая определена строкой $message = $session->message() за пределами объекта и класса. Поэтому ее можно спокойно вызвать здесь, просто обратившись к ней прямо: echo output_message($message). То есть $message в объекте и классе и $message за их пределами - это две разные вещи. Кстати, и обращение к ним было бы соответственно $session-message и $message.

?>

<form action="photo_upload.php" enctype="multipart/form-data" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>" />
	<p><input type="file" name="file_upload" /></p>
    <p>Caption: <input type="text" name="caption" value="" /></p>
    <input type="submit" name="submit" value="Upload" />
  </form>


<?php include_layout_template('admin_footer.php'); ?>
		
