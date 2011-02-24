<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require("../include/version.php"); ?></title>
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
session_start();
require("../include/config.php");
require("../include/functions.php");
require("../include/header.php");
if($user_info['permissions'] > 1)
{
//include "../../testheader.php";
require("config.php");
//print_r($_FILES);;
echo "<center><table width=50%><tr><td bgcolor=#FFFFFF><center>";
$fail = 0;
$target = "../../local_bands/images/";
$target = $target . basename( $_FILES['uploaded']['name']) ;
//echo $target . "<br>";

if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))
{
	echo "The file ". basename( $_FILES['uploaded_file']['name']). " has been uploaded<br>";
}
else {
	echo "Sorry, there was a problem uploading your file.";
	$fail=1;
}

$image = "images/" . $_FILES['uploaded']['name'];
if($fail==0){
$name=mysql_real_escape_string($_POST[name]);
$hometown=mysql_real_escape_string($_POST[hometown]);
$genre=mysql_real_escape_string($_POST[genre]);
$website=mysql_real_escape_string($_POST[website]);
$info=mysql_real_escape_string($_POST[info]);
$callname=mysql_real_escape_string($_POST[callname]);
$listen=mysql_real_escape_string($_POST[listen]);


	mysql_query("INSERT INTO bands(name,hometown,genre,website,info,image,callname,listen) VALUES('$name','$hometown','$genre','$website','$info','$image','$callname','$listen')")
or die(mysql_error());
echo $name . "Has Been Added!<br>";

}
echo "<br><br><a href=" . "index.php" . ">" . "GO BACK" . "</a><br>";
}
else{
echo "<br><br><br><br><center><font color=white><b>You are not authorized for this section.</b></font>";
}
echo "</td></tr></table>";
?>



