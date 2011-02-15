<?php
session_start();
require_once ("../../config.php");
$username = mysql_real_escape_string($_SESSION['username']);
$sql = "UPDATE accounts SET banner='' WHERE username='$username'";
mysql_query($sql) or die(mysql_error());
header('location:index.php');
?>
