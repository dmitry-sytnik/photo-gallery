<?php

// Это вспомогательный класс для легкого создания записей пагинации
class Pagination {
	
	public $current_page;
	public $per_page;
	public $total_count;
	
  public function __construct($page=1, $per_page=20, $total_count=0){
  	$this->current_page = (int)$page;
    $this->per_page = (int)$per_page;
    $this->total_count = (int)$total_count;
  }
  
  public function offset() { // смещение
	// метод подхватывает значение текущей страницы и, согласно форумле, возвращает число смещения, которое потом используется для SQL запроса для OFFSET
    // Assuming 20 items per page:
    // page 1 has an offset of 0    (1-1) * 20
    // page 2 has an offset of 20   (2-1) * 20
    //   другими словами, страница 2 начинается с пункта 21
    return ($this->current_page - 1) * $this->per_page;
  }
	
  public function total_pages() {
	  // это общее количество страниц, в каждой из которых будет выводиться набор результатов 
	  // ceil — Округляет дробь в большую сторону
    return ceil($this->total_count/$this->per_page);
  }
	
  public function previous_page() {
    return $this->current_page - 1;
  }
  
  public function next_page() {
    return $this->current_page + 1;
  }
  
	public function has_previous_page() {
		// если предыдущая страница (минус один от текущей) больше или равна 1, то предыдущая страница есть (это любое число, включая один)
		// если предыдущая страница (минус один от текущей) - это ноль и или меньше, то предудущей страницы нет
		// возвращаем true или false
		return $this->previous_page() >= 1 ? true : false;
	}

	public function has_next_page() {
		// если следующая страница (плюс один к текущей) меньше или равна числу всех страниц, то следущая страница имеет место быть
		// иначе, если следущая страница больше, чем число всех возможных страниц, то следующей страницы быть не может
		// возвращаем true или false
		return ($this->next_page() <= $this->total_pages()) ? true : false;
	}
	
	
}




?>