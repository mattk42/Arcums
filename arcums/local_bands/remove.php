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
if($user_info['permissions'] == 4)
{
//include "../../testheader.php";
require("config.php");
echo "<center><table width=50%><tr><td bgcolor=#FFFFFF><center>";
if($_GET[id]!=NULL){
$result = mysql_query("SELECT * FROM bands where id=$_GET[id]");
$bandinfo = mysql_fetch_array($result);
$id='"' .mysql_real_escape_string($_GET[id]) . '"';
mysql_query("DELETE FROM bands WHERE id=$id")
or die(mysql_error());
if(mysql_affected_rows()==0){
	echo "That ID Does not exsist!";
}
else{
	echo $bandinfo[name] . " Has been removed!	";
	unlink($bandinfo[image]);

}
}
echo "<br><br><a href=" . "index.php" . ">" . "GO BACK" . "</a><br>";
echo "</td></tr></table>";
}

else{
echo "<br><br><br><br><center><font color=white><b>You are not authorized for this section.</b></font>";
}
?>

