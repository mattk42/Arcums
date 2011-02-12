<?php
$root_url = "http://www.wupx.com/local_bands/";
$username2 = "local";
$password = "local915";
$database = "local";
$server = "localhost";
mysql_connect($server, $username2, $password) or die(mysql_error());
mysql_select_db($database) or die(mysql_error()); 
?>
