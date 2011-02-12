<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require("../include/version.php"); ?></title>
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php 

session_start();
require("../include/config.php");
require("../include/functions.php");
require("../include/header.php");
include("includes/config.php");
include('includes/functions.php');
if(isset($_GET['submit'])){
	$message = "DJ:" . $_POST['dj'] . "\n" ."Artist:" . $_POST['artist'] . "\n" ."Album:" . $_POST['album'] . "\n" ."Catalog Number:" . $_POST['cat'] . "\n" ."\nMessage:\n" . $_POST['comments'] . "\n";

	$sent = mail ( "itdirector@wupx.com" , "Catalog Report" , $message  );
	//mail ( "musicdirector@wupx.com" , "Catalog Report" , $message  );

	if($sent==1){
		echo '<meta HTTP-EQUIV="REFRESH" content="0; url=view.php?id='.$_POST['id'].'&sent=1">';
	}
	else{
		echo "<center>Report Failed</center>";
	}
	
}
else if(isset($_GET['id'])){
		$id = mysql_real_escape_String($_GET['id']);
		$result = mysql_query("SELECT artist,title,category_number,vinyl_letter,category_id FROM albums WHERE id='" . $id . "'" ) or die(mysql_error());
		$row = mysql_fetch_row($result);

		$category_result = mysql_query("SELECT prefix FROM category_lookup WHERE id='".$row[4]."'") or die(mysql_error);
		$category = mysql_fetch_row($category_result);
?>
<center>
<form  action="report.php?submit=1" method="POST" enctype="multipart/form-data" name="Catalog_Issue">
<input type="hidden" name="id" value="<?echo $id;?>">
DJ: <input class='searchbox' type="text" name="dj" value="<?echo $user_info['djname'];?>"readonly><br>
Artist: <input class='searchbox' type="text" name="artist" value="<?echo $row[0];?>"readonly><br>
Album: <input class='searchbox' type="text" name="album" value="<?echo $row[1];?> "readonly><br>
Catalog Number: <input class='searchbox' type="text" name="cat" value="<?echo $category[0] . $row[3] . $row[2];?> "readonly><br>
<textarea name="comments" cols="70" rows="25"> Describe your issue here. </textarea><br>
<input type='submit' value='Submit'>
</form>
</center>
<?
}
?>
