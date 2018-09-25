<?php
require_once("../../includes/initialize.php");


if($session->is_logged_in()) {
  redirect_to("index.php");
}

// Remember to give your form's submit tag a name="submit" attribute! - Помните, что нужно давать вашему тегу submit в форме атрибут name="submit"
if (isset($_POST['submit'])) { // Form has been submitted. - Форма была отправлена.

  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  
  //$found_user = new User();
  // инициализировать объект не нужно, т.к. User::authenticate сама будет возвращать объект (благодаря другим, вызванным в ней функциям), который будет со своими переменными, согласно заданным username и password. Это описано к описании этой функции в user.php
  
  // Check database to see if username/password exist.
  $found_user = User::authenticate($username, $password);
  
  if ($found_user) {
    $session->login($found_user); // описание этой функции дано в session.php
	// она - задает свою переменную logged_in как true, 
	//     - переменную $found_user->id помещает в _SESSOIN и также запоминает в себе в качестве $session->user_id . 
	
	// var_dump($found_user); так можно посмотреть, что за объект получился .
	
	////
	// делается запись в лог-файл
	////
	// log_action() находится в functions.php
	log_action('Login', "{$found_user->username} logged in.");
    redirect_to("index.php");
  } else {
    // username/password combo was not found in the database
    $message = "Username/password combination incorrect.";
  }
  
} else { // Form has not been submitted. - Форма не была отправлена.
  $username = "";
  $password = "";
}

?>

<?php include_layout_template('admin_header.php'); ?>

		<h2>Staff Login</h2>
		<?php echo output_message($message); ?>

		<form action="login.php" method="post">
		  <table>
		    <tr>
		      <td>Username:</td>
		      <td>
		        <input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" />
		      </td>
		    </tr>
		    <tr>
		      <td>Password:</td>
		      <td>
		        <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
		      </td>
		    </tr>
		    <tr>
		      <td colspan="2">
		        <input type="submit" name="submit" value="Login" />
		      </td>
		    </tr>
		  </table>
		</form>
    
<?php include_layout_template('admin_footer.php'); ?>