<?php
session_start();
require ("../../config.php");
$auto = mysql_real_escape_string($_GET['auto']);
$sql = "DELETE FROM playlist WHERE auto=$auto";

mysql_query($sql) or die ('<p>Error deleting entry: ' . mysql_error() . '</p>');
echo(header('location:index.php'));

?>
