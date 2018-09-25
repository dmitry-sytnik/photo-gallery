<?php
require_once("../includes/initialize.php");
/*
require_once("../includes/functions.php");
require_once("../includes/database.php");
require_once("../includes/user.php");
*/
?>

<?php include_layout_template('header.php');?>

<?php
$user = User::find_by_id(1); // find_by_id возвращает объект с массивом переменных

echo $user->full_name();

echo "<hr />";

$users = User::find_all(); // find_all благодаря find_by_sql  возвращает сложный массив объектов с массивами своих переменных у каждого объекта.
foreach($users as $user) {
	// здесь для массива объектов users, для каждого его элемента (а элементами служат отдельные объекты), выводим

	// для каждого объекта его переменную username
	echo "User: ".$user->username."<br/>"; 
	// для каждого объекта выполняем присущую его классу публичную функцию full_name
	echo "Name: ".$user->full_name()."<br/><br/>";
}
?>

<?php include_layout_template('footer.php'); ?>