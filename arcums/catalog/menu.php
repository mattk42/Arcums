<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require ("../include/version.php"); ?></title>
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
require ("../../config.php");
require ("../include/functions.php");
require ("../include/header.php");

if (isset($_SESSION['dj_logged_in'])) {
    echo '<center><br /><table width="600"> <tr><td>
	<center>

	<table class="welcomebar">
	<tr>
	<td class="date">
	Catalog
	</td>
	<td class="loggedin">
	You\'re logged in as ';
    echo $user_info['username'];
    echo '
	<a href="../login/logout.php">[logout]</a>&nbsp;&nbsp;&nbsp;&nbsp;
	</td>
	</tr>
	</table>';
    echo '<center><table align="center" width="650"><tr><td class="headers" align="center">
	<a href="index.php">Lookup in our catalog</a>';
    
    if ($user_info['permissions'] >= 2) {
        echo '<br>
		<a href="search.php">Add albums from Amazon</a>
		<br>
		<a href="add.php">Add album manually</a>
		</center></td></table>
		<br>';
    }
}
else {
    echo '<meta HTTP-EQUIV="REFRESH" content="0; url=../login">';
}
?>
