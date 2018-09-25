<?php require_once("../includes/initialize.php");?>
<?php // эту часть можно считать контроллером (controller)
// Код по иерархии (если он повторяется или если это возможно)обычно должен перемещаться из view в controller, и затем в model. Порядок букв по идее: VCM. По нисходящей данные поступают из базы данных в Model, отсюда в Controller, отсюда в Вид пользователю.

	// 1. текущий номер страницы ($ current_page)
	// Если $page не пустая, то берем значение из $_GET['page'], иначе это просто первая страница
	
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	// Любопытно, что мы будем прописывать ['page'] в адресной строке как ?page=3, например. Это значение будет попадать в $_GET. Его мы будем присваивать переменной $page и испольовать для дальнейшего построения веб-страницы, как нам нужно.
	// Вместо этого можно было бы использовать, что угодно, например, $_GET['subj']. Тогда в адресной строке мы должны прописывать ?subj=4, например. И присваивать это значение переменной $page, которую мы по-прежнему можем использовать точно так же.
	
	// 2. записи на страницу ($ per_page)
	$per_page = 2;

	// 3. общее количество записей ($ total_count)
	$total_count = Photograph::count_all();
	// тестировка:
	// echo $total_count;
	
	
	// Находим все фото
	// Будем использовать пагинацию вместо этого
	//$photos = Photograph::find_all(); // возвращает объекты с массивами переменных
	
	$pagination = new Pagination($page, $per_page, $total_count);
	
	// Вместо того, чтобы находить все записи, просто найдите записи
	// для этой страницы
	$sql = "SELECT * FROM photographs ";
	$sql .= "LIMIT {$per_page} ";
	$sql .= "OFFSET {$pagination->offset()}"; // этот метод возвращает число, основанное на числе текущей страницы и формуле, возвращающей число смещения, которое здесь необходимо для OFFSET
	
	$photos = Photograph::find_by_sql($sql);
	
	// Необходимо добавить ?page=$page ко всем ссылкам, которые мы хотим сохранить на текущей странице (или сохранить $page в $session)
	
	
?>
<?php // эту часть можно считать видом (view)
 include_layout_template('header.php');?>

<?php foreach($photos as $photo): ?>
	<div style="float: left; margin-left: 20px;">
	<a href="photo.php?id=<?php echo $photo->id; ?>">
	<img src="<?php echo $photo->image_path(); ?>" width="200" /></a>
	<p><?echo $photo->caption; ?></p>
	</div>
<?php endforeach; ?>

<div id="pagination" style="clear: both;">
<?php
    // тестировка:
	/*
	echo $pagination->total_pages();
	echo $pagination->previous_page();
	echo $pagination->next_page();
	echo $pagination->has_next_page();
	*/
	
	// общее количество страниц, в каждой из которых будет выводиться набор результатов, больше, чем одна, то значит, у нас не помещается все на одну страницу, а значит, нам нужна пагинация (перемещение вперед и назад)
	if($pagination->total_pages() > 1) {
		
		// если может быть предыдущая страница (т.к. нам возвращается false или true методом has_previous_page), то печатаем в html код с возможностью перехода на предыдущую страницу
		if($pagination->has_previous_page()) { 
		  echo "<a href=\"index.php?page=";
		  echo $pagination->previous_page();
		  // previous_page возвращает число
		  echo "\">&laquo; Previous</a> "; 
		}

		// Между ссылками "предыдущая" и "следующая" выведем все внутренние ссылки
		for($i=1; $i <= $pagination->total_pages(); $i++) {
			// а если мы уже на данной странице, то ее номер вывоводится без ссылки в качестве выбранной
			if($i == $page) {
				echo " <span class=\"selected\">{$i}</span> ";
			} else {
			echo " <a href=\"index.php?page={$i}\">{$i}</a> "; 
			}
		}
		
		// если может быть следуюшая страница (т.к. нам возвращается false или true методом has_next_page), то печатаем в html код с возможностью перехода на следующую страницу
		if($pagination->has_next_page()) { 
			echo " <a href=\"index.php?page=";
			echo $pagination->next_page();
			// next_page возвращает число
			echo "\">Next &raquo;</a> "; 
		}
		
	}

?>
</div>

<?php include_layout_template('footer.php'); ?>