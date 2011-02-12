<?php
session_start();
require("../../config.php");
require("../include/functions.php");
require("../include/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require("../include/version.php"); ?></title>
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
if($user_info['permissions'] > 1)
{
//include "../../testheader.php";

?>
<center>
<h1>Add A Local Band:</h1><br>
<center>
<form enctype="multipart/form-data" action="addband2.php" method="post">
Band Name:<input type="text" name="name">
<br>
Hometown:<input type="text" name="hometown">
<br>
Genre:<input type="text" name="genre">
<br>
Website:<input type="text" name="website">
<br>
Info: <br>
<textarea name="info" cols="40" rows="5">
Band Info Here
</textarea><br>
<br>
Image:<input name="uploaded" type="file" /><br />
<br>
Callname:<input type="text" name="callname">
<br>
Listen:<input type="text" name="listen">
<input type="submit" />
</form>
</center>
<?php }
else{
echo "<br><br><br><br><center><font color=white><b>You are not authorized for this section.</b></font>";
}
 ?>


