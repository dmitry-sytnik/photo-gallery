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
$photos = Photograph::find_all(); // возвращает объекты с массивами переменных
foreach($photos as $photo) {
?>
<div style="float: left;">
<a href="photo_mine.php?id=<?php echo $photo->id; ?>">
<img src="<?php echo $photo->image_path(); ?>" width="200" /></a>
<br />
<?php
echo $photo->caption;
echo "</div>";
}
?>


<?php include_layout_template('footer.php'); ?>