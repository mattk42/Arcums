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
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php

if (isset($_SESSION['dj_logged_in'])) {
    $id = mysql_real_escape_string($_GET['id']);
    
    if (isset($_POST['title'])) {
        $title = mysql_real_escape_string($_POST['title']);
        $start = mysql_real_escape_string($_POST['sdate']);
        $end = mysql_real_escape_string($_POST['edate']);
        $loc = mysql_real_escape_string($_POST['loc']);
        $det = mysql_real_escape_string($_POST['details']);
        $query = "UPDATE events SET title='$title', start='$start', end='$end', location='$loc', details='$det' WHERE id='$id'";
        mysql_query($query) or die(mysql_error());
        echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=http://www.wupx.com/arcums/events/?edit=" . urlencode($title) . "\">";
    }
    else {
        $res = mysql_query("SELECT * FROM events WHERE id='$id'") or die(mysql_error());
        $row = mysql_fetch_row($res);
?>
	<center>
	<table class="welcomebar">
	<tr><td class="date"> Add Events</td>
	<td class="loggedin">
	<?php echo 'You\'re logged in as ';
        echo $session_username; ?>EVENT #2.
	<a href="http://www.wupx.com/arcums/login/logout.php">[logout]</a>&nbsp;&nbsp;&nbsp;&nbsp;
	</td>
	</tr>
	</table>

	<table class='addtablemain'><tr><td align='center'>
	<form method=post >
		Event Title: <input type=text name=title value='<?php echo $row[3]; ?>'></input>
		Location: <input type=text name=loc value='<?php echo $row[4]; ?>'></input><br>
		Start Date: <input type=text name=sdate value='<?php echo $row[1]; ?>'></input>
		End Date: <input type=text name=edate value='<?php echo $row[2]; ?>'></input><br><br>
		Details:<br><textarea rows=20 cols=90 name=details><?php echo $row[5]; ?></textarea><br><br>
		<input type=submit value=Submit name=submit>
	</table>
	<?php
    }
}
else {
    echo '<meta HTTP-EQUIV="REFRESH" content="0; url=../login">';
}
?>
