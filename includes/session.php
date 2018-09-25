<?php
// Класс для работы с сеансами
// В нашем случае, в основном чтобы управлять входом и выходом пользователей

// Имейте в виду, что при работе с сессиями это обычно
// нецелесообразно хранить объекты, связанные с БД, в сессиях

// Лучше хранить в сессии только id объекта, а не весь объект.

class Session {

	private $logged_in=false; // по умолчанию будет стоять false
	public $user_id;
	public $message;
	

	function __construct() {
		session_start();
		
		$this->check_message(); // автоматически проверяем, не было ли сообщений
		$this->check_login(); // check_login присвоит переменным, которые выше, различные значения.

	    if($this->logged_in) { // если logged_in есть как true
	      // actions to take right away if user is logged in
		  
	    } else { // иначе, если logged_in есть как false
	      // actions to take right away if user is not logged in
    }

	}
	
	
	// я так понял, что эта публичная функция вернет false или true в зависимости от того, какое значение имеет приватная переменная $logged_in. С помощью этой функции можно будет проверять на страницах (с помощью $session->is_logged_in()), была ли задана на странице $_SESSION['user_id'] и, следовательно $logged_in принимает true (это устанавливается здесь функцией check_login) или, наоборот, SESSION не было и эта перменная есть как false.
	public function is_logged_in() {
		return $this->logged_in;
	}

	// В $user помещается объект класса User, скажем, некий $found_user со своими переменными
	public function login($user) {
    // датабаза должна к этому моменту найти пользователя по имени и паролю
    if($user){ // Если $found_user есть, то
	  // помещаем его переменную id в _SESSION, а также, далее, в $session->user_id
      $this->user_id = $_SESSION['user_id'] = $user->id;
	  // и задаём logged_in как true ($session->logged_in = true;)
      $this->logged_in = true;
    }
  }

    public function logout() {
    unset($_SESSION['user_id']);
    unset($this->user_id);
    $this->logged_in = false;
  }
  
    public function message($msg="") {
	  if(!empty($msg)) {
	    // then this is "set message"
	    // make sure you understand why $this->message=$msg wouldn't work
		// если $msg не пустая, то задаем $msg в сессию
		// убедитесь, что вы понимаете, почему $this->message=$ msg не будет работать. - Это потому, что мы стремимся поместить $msg в сессию, а не просто присвоить значение атрибуту объекта.
	    $_SESSION['message'] = $msg;
	  } else {
	    // then this is "get message"
		// если $msg пустая, то возвращаем $message
			return $this->message;
	  }
	}
  
	// Эта функция вызывается в конструкторе (а значит и в $session как объекте, который вызван ниже). Она присваивает переменным объекта те или иные значения. Например, исправляет переменную $logged_in на true, если на странице существует $_SESSION['user_id'] . В этом случае можно говорить о существовании на странице переменной $this->logged_in или $session->logged_in (т.к. она просто задаётся как true).
	private function check_login() {
    if(isset($_SESSION['user_id'])) {
      $this->user_id = $_SESSION['user_id'];
      $this->logged_in = true;
    } else {
      unset($this->user_id);
      $this->logged_in = false;
    }
  }

	private function check_message() {
		// Если сообщение было задано в сессии, то
		if(isset($_SESSION['message'])) {
		  // делаем атрибут message равным тому, что было задано в сессии.
		  $this->message = $_SESSION['message'];
		  // затем очищаем сообщение
		  unset($_SESSION['message']);
		} else {
			// Иначе пустая строка
		  $this->message = "";
		}
	}

}


$session = new Session();
$message = $session->message();


?>