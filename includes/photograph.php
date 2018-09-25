<?php
require_once(LIB_PATH.DS.'database.php');

class Photograph extends DatabaseObject {
	
	protected static $table_name="photographs";
	protected static $db_fields=array('id', 'filename', 'type', 'size', 'caption');
	
	public $id;
	public $filename;
	public $type;
	public $size;
	public $caption;
	
	private $temp_path;
	protected $upload_dir="images";
	
	// переменная для сбора всех ошибок в одном массиве
	public $errors=array();
	
	protected $upload_errors=array(
		//http://www.php.net/manual/en/features.file-upload.errors.php
		UPLOAD_ERR_OK 			=> "No errors.",
		UPLOAD_ERR_INI_SIZE  	=> "Larger than upload_max_filesize.",
		UPLOAD_ERR_FORM_SIZE 	=> "Larger than form MAX_FILE_SIZE.",
		UPLOAD_ERR_PARTIAL 		=> "Partial upload.",
		UPLOAD_ERR_NO_FILE 		=> "No file.",
		//$five_error				=> "Устаревшая ошибка №5",
		UPLOAD_ERR_NO_TMP_DIR	=> "No temporary directory.",
		UPLOAD_ERR_CANT_WRITE	=> "Can't write to disk.",
		UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
	);
	
	// Берем $_FILE['uploaded_file'] в качестве аргумента ($file)
	// $_FILE['uploaded_file'] может принимать 5 видов согласно PHP:
	// $_FILE['uploaded_file']['name']
	// $_FILE['uploaded_file']['type']
	// $_FILE['uploaded_file']['size']
	// $_FILE['uploaded_file']['tmp_name']
	// $_FILE['uploaded_file']['error']
	// Само название ['uploaded_file'] опционально, оно берется из установленной HTML-формы, могло быть и другое.
	public function attach_file($file) {
		// Выполните проверку ошибок в параметрах формы
		// Если нет $_FILE['uploaded_file'] или он пуст или он не массив, то
		if(!$file || empty($file) || !is_array($file)) {
		  // error: nothing uploaded or wrong argument usage
		  $this->errors[] = "No file was uploaded.";
		  return false;
		  
		  // Или же есть ошибка, но ошибка PHP, и она не ноль (0=ОК, "всё загрузилось уcпешно"), то помещаем название этой ошибки в наш массив errors и возвращаем false.
		} elseif($file['error'] != 0) {
		  // error: report what PHP says went wrong
		  $this->errors[] = $this->upload_errors[$file['error']];
		  return false;
		  
		  // Иначе всё загрузилось успешно
		} else { 		
		// Установите атрибуты объекта в параметры формы.
		  $this->temp_path  = $file['tmp_name'];
		  $this->filename   = basename($file['name']);
		  $this->type       = $file['type'];
		  $this->size       = $file['size'];
		
		// Не беспокойтесь пока о сохранении чего-либо в базе данных.
		  return true;
		}
	}
	
	// Эта функция перезапишет родительскую публичную функцию save()
	// Тем не менее к родительской будет доступ посредством parent::save()
	public function save() {
		// A new record won't have an id yet.
		// У новой записи еще нет идентификатора.
		
		// Если задан в текущем объекте id, то Update
		if(isset($this->id)) {
			// Really just to update the caption
			// Совершается просто обновление подписи(заголовка)
			$this->update();
			
		// Иначе, Create
		} else {
			// 1. Make sure there are no errors
			// 1. Удостоверьтесь, что ошибок нет
			
			// Can't save if there are pre-existing errors
			// Не удается сохранить, если есть ранее существовавшие ошибки
		  if(!empty($this->errors)) { return false; }
		  
			// Make sure the caption is not too long for the DB
		  if(strlen($this->caption) > 255) {
				$this->errors[] = "The caption can only be 255 characters long.";
				return false;
			}
			
			// Can't save without filename and temp location
		  if(empty($this->filename) || empty($this->temp_path)) {
				$this->errors[] = "The file location was not available.";
				return false;
			}
		  
		  // Determine the target_path
		  // Определяем полный целевой путь
		  $target_path = SITE_ROOT .DS. 'public' .DS. $this->upload_dir .DS. $this->filename;
		  
		   // Дополнительная проверка ошибок, чтобы убедиться, что такой файл еще не существует по полному целевому пути.
		   // Make sure a file doesn't already exist in the target location
		  if(file_exists($target_path)) {
				$this->errors[] = "The file {$this->filename} already exists.";
				return false;
			}
			
			// 2. Attempt to move the file 
			// 2. Попытка перемещения файла
		  if(move_uploaded_file($this->temp_path, $target_path)) {
				// Success
				// 3. Save a corresponding entry to the database
				// 3. Сохранение соответствующей записи в базе данных
				
				// И если создание create() проходит успешно, то
				if($this->create()) {
					// удалим временный путь, он больше не нужен; тем более после create() файла там больше нет.
					unset($this->temp_path);
					// возвращаем истину, во всех остальных случаях ложь и только здесь истину
					return true;					
				}
			} else {
				// Failure
				// File was not moved.
				$this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
				return false;				
			}
			
		}
					
	}
	
	public function destroy() {
		// Наперво будем удалять запись в базе данных о фото
		if($this->delete()) { // это метод из DatabaseObject. Он удаляет запись и возвращает либо true (когда успешно), либо false (когда не успешно)
			// Если успешно, то Далее будем удалять фото из файловой системы
			$target_path = SITE_ROOT.DS.'public'.DS.$this->image_path();
			return unlink($target_path) ? true : false;
			// Несмотря на удаление файла из файловой системы и записи о нем в базе данных, тем не менее в памяти php остается объект $photo со всеми своими атрибутами, что позволяет оперировать этими атрибутами на страницах, особенно в html.
			// Кстати, $this->image_path() работает именно благодаря этой особенности, ведь объект остался только в php.
			// Объект удаляется самим php в конце скрипта или если мы сами его удалим.
			
		} else { // иначе, если удаление из базы данных не успешно
			return false;
			
		}
		
	}
	
	
	// Определяем путь директории изображений для кода, даже если эту директорию мы будем менять на другую в будущем.
	public function image_path() {
		return $this->upload_dir.DS.$this->filename;
	}

	public function size_as_text() {
		if($this->size < 1024) {
			return "{$this->size} bytes";
		} elseif($this->size < 1048576) {
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		} else {
			// Тут округляем до 0,1
			$size_mb = round($this->size/1048576, 1);
			return "{$size_mb} MB";
		}
	}

	public function comments() {
		return Comment::find_comments_on($this->id);
	}



	
}
?>