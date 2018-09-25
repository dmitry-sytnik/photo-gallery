<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class Comment extends DatabaseObject {

  protected static $table_name="comments";
  protected static $db_fields=array('id', 'photograph_id', 'created', 'author', 'body');

  public $id;
  public $photograph_id;
  public $created;
  public $author;
  public $body;
  
// "new" - это зарезервированное слово, поэтому вместо него используйте "make" (или "build")
  public static function make($photo_id, $author="Anonymous", $body="") {
	if(!empty($photo_id) && !empty($author) && !empty($body)) {
	// если аргументы метода не пусты, то метод вернет объект
		$comment = new Comment();
		$comment->photograph_id = (int)$photo_id;
		$comment->created = strftime("%Y-%m-%d %H:%M:%S", time());
		$comment->author = $author;
		$comment->body = $body;
		return $comment;
		} else {
			return false;
		}
  }
  
  public static function find_comments_on($photo_id=0) {
    global $database;
	//выбираем все комментарии, относящиеся к определенной фотографии
	$sql = "SELECT * FROM " . self::$table_name;
    $sql .= " WHERE photograph_id=" .$database->escape_value($photo_id);
    $sql .= " ORDER BY created ASC";
	// возвращаем результаты запроса
	// это будут объекты-комментарии, каждый со своим массивом переменных
    return self::find_by_sql($sql);	
  }

  public function try_to_send_notification() {
	// эта функция просто отправляет е-мэйл. она не делает ничего в html и не возвращает ничего.
	$to = "Admin Photo-Gallery <recipient@mail.ru>";  // получатель письма
	
	$subject = "New Photo Gallery Comment at ".strftime("%H:%M:%S", time());

	$created = datetime_to_text($this->created);
	$message =<<<EMAILBODY
	
A new comment has been received in Photo Gallery.

Photograph: {$this->photograph_id}

 At {$created}, {$this->author} wrote:

{$this->body}
	
EMAILBODY;

	// Необязательно: перенос строк для старых почтовых программ
	// на символе 70/72/75/78
	$message = wordwrap($message,70);

	$from = "Free Man <sender@mail.ru>";	// отправитель письма
	$headers = "From: {$from}\r\n";
    // для корректного переноса строки нужно использовать двойные кавычки 	
	$headers .= "Reply-To: {$from}\r\n";	
	$headers .= "X-Mailer: PHP/".phpversion()."\r\n";
	$headers .= "MIME-Version: 1.0\r\n";	
	$headers .= "Content-Type: text/plain; charset=iso-8859-1\r\n";
	
	$result = mail($to, $subject, $message, $headers);
	}
}
?>