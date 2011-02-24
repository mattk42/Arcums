<?php
session_start();
require ("../../config.php");
require ("../include/functions.php");
require ("../include/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require ("../include/version.php"); ?></title>
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>


<?php

if (isset($_SESSION['dj_logged_in'])) {
?>

	<center>
	<table class="welcomebar">
	<tr><td class="date"> Events List</td>
	<td class="loggedin">
	<?php echo 'You\'re logged in as ';
    echo $session_username; ?>
	<a href="http://www.wupx.com/arcums/login/logout.php">[logout]</a>&nbsp;&nbsp;&nbsp;&nbsp;
	</td>
	</tr>
	</table>

	<?php
    echo "<table class='eventtablemain'><tr>";
    
    if ($user_info['permissions'] >= 2) {
        echo '<td><a href=\'post.php\'>Add Events</a></td>';
    }
    
    if ($_GET['all'] == '1') {
        echo '<td><a href=\'?all=0\'>Show Current</a>';
    }
    else {
        echo '<td><a href=\'?all=1\'>Show All</a>';
    }
    echo "</td></table>";
    
    if ($_GET['all'] == '1') {
        $result = mysql_query("SELECT title, start, end, location, details, id FROM events ORDER BY start ASC, end DESC") or die("Databse Error");
    }
    else {
        $result = mysql_query("SELECT title, start, end, location, details, id FROM events WHERE end > Now() or (start>=Now() and end='0000-00-00 00:00:00') ORDER BY start ASC, end DESC") or die("Database Error");
    }
    
    if (isset($_GET['post'])) {
        echo "<font color='white'><b>" . $_GET['post'] . " was posted succesfully.</b></font><br>";
    }
    
    if (isset($_GET['edit'])) {
        echo "<font color='white'><b>" . $_GET['edit'] . " was succesfully edited.</b></font><br>";
    }
    
    if (isset($_GET['del'])) {
        echo "<font color='white'><b> Event succesfully deleted.</b></font><br>";
    }
    
    while ($row = mysql_fetch_array($result)) {
        $start = date("M d Y h:i A", strtotime($row[1]));
        
        if ($row[2] == '0000-00-00 00:00:00') {
            $end = "";
        }
        else {
            $end = " - " . date("M d Y h:i A", strtotime($row[2]));
        }
        echo "<center><table class=\"eventtablemain\">";
        echo "<tr><td class=\"headers\"><b><font size=\"4\">" . $row[0] . "</b></font></td>";
        
        if ($user_info['permissions'] >= 2) {
            echo "<td class='headers' align='right'><a href='edit.php?id=" . $row[5] . "'><img alt='Edit' width='20' src='../images/edit-icon.png'></a></td>";
        }
        echo "</tr>";
        echo "<tr><td><b>When: </b>" . $start . $end . "</td>";
        
        if ($user_info['permissions'] >= 2) {
            echo "<td class='headers' align='right'><a href='delete.php?id=" . $row[5] . "'><img alt='Delete' width='20' src='../images/delete-icon.png'></a></td>";
        }
        echo "</tr>";
        echo "<tr><td><b>Where: </b>" . $row[3] . "</td></tr>";
        echo "<tr><td>" . $row[4] . "</td></tr>";
        echo "<td></table></center><br>";
    }
}
else {
    echo '<meta HTTP-EQUIV="REFRESH" content="0; url=../login">';
}
?>
