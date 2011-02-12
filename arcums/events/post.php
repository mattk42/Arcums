<?php
session_start();
require("../include/functions.php");
require("../include/header.php");
require("../../config.php");
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

if(isset($_SESSION['dj_logged_in'])){
	if(isset($_POST['title'])){
		$title = mysql_real_escape_string($_POST['title']);
		$start = strtotime($_POST['sdate']);
		$end = strtotime($_POST['edate']);
		$loc = mysql_real_escape_string($_POST['loc']);
		$details = mysql_real_escape_string($_POST['details']);

		$start = date("Y-m-d H:i:s", $start);
		if($end == ""){
			$end = NULL;
		}
		else{
			$end = date("Y-m-d H:i:s", $end);
		}
	
		echo $title . "<br>";
		echo $start . "<br>";
		echo $end . "<br>";
		echo $loc . "<br>";
		echo $details . "<br>";

		mysql_query("INSERT INTO events (start,end,title,location,details) VALUES ('$start','$end','$title','$loc','$details')")
		or die(mysql_error());

		echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=http://www.wupx.com/arcums/events/?post=" . urlencode($title) . "\">";
		
	}
	else{
	?>
	<center>
	<table class="welcomebar">
	<tr><td class="date"> Add Events</td>
	<td class="loggedin">
	<?php echo 'You\'re logged in as '; echo $session_username;?>
	<a href="http://www.wupx.com/arcums/login/logout.php">[logout]</a>&nbsp;&nbsp;&nbsp;&nbsp;
	</td>
	</tr>
	</table>

	<table class='addtablemain'><tr><td align='center'>
	<form method=post>
		Event Title: <input type=text name=title></input>
		Location: <input type=text name=loc></input><br>
		Start Date: <input type=text name=sdate></input> 
		End Date: <input type=text name=edate></input><br><br>
		Details:<br><textarea rows=20 cols=90 name=details></textarea><br><br>
		<input type=submit value=Submit name=submit>
	</form>	
	</tr></td></table>
	</center>
	<?php
	}
}
else{
	echo '<meta HTTP-EQUIV="REFRESH" content="0; url=../login">';
}

?>
