<?php require_once("../includes/initialize.php");?>
<?php
	if(!isset($_GET[id])){redirect_to("index_mine.php");}
	// при photo_mine.php?id= даст Database query failed.
	// надо использовать if(empty($_GET['id']))
?>
<?php 

$photo = Photograph::find_by_id($_GET[id]);
// чтобы при несуществующем id совершить редирект, надо положиться на то, что мы получаем в $photo: if(!$photo) {redirect_to('index_mine.php');}

?>
<?php include_layout_template('header.php');?>
<a href="index_mine.php">&laquo; Back</a><br />
<br />

<img src="<?php echo $photo->image_path(); ?>" width="800" />
<br />
<br />
<?php echo $photo->caption;?>



<?php include_layout_template('footer.php'); ?>