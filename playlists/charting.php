<?php 
session_start(); 
require("../../config.php");
require("../include/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ARCUMS 2.0</title>
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php 
require("../include/header.php");
?>
<?php
<?php

	// Playlists.php
	// -- this file accepts $_GET input and returns a list of songs from the playlist table
	// 		-- if nothing is supplied, it returns a generic list of the last 10 songs played
	// 		-- if type=dj&dj=<djname> is supplied, it will return the last 10 songs played for that dj username
	// 				-- if limit=<number> is supplied, it returns that many songs; default is 10.  Must be between 1 and 25

	$startdate = filter_input(INPUT_GET, 'startdate', FILTER_SANITIZE_SPECIAL_CHARS);
	$enddate = filter_input(INPUT_GET, 'enddate',FILTER_SANITIZE_SPECIAL_CHARS);


//this query also erros out
//SELECT artist, song, label, datetime as date FROM arcums.playlist UNION SELECT artist, title as song, label, date_played as date from automation.historylist where date between '2009-04-14' and '2009-04-16' order by date

//this works
//SELECT dj,artist, song, label, datetime as date FROM arcums.playlist where date between '2009-04-10' and '2009-04-19' UNION SELECT songrights as dj,artist, title as song, label, date_played as date from automation.historylist where automation.historylist.date_played between '2009-04-10' and '2009-04-19' order by date

if($startdate !== false && !empty($startdate) && $enddate !== false && !empty($enddate)){
	$query = "SELECT dj,artist, song, label, datetime as date FROM arcums.playlist where date between '$startdate' and '$enddate' UNION SELECT songrights as dj,artist, title as song, label, date_played as date from automation.historylist where automation.historylist.date_played between '$startdate' and '$enddate' order by date";
	$result = mysql_query($query) or die("Query failed.");
	
	echo "<ul>\n";
	echo "<table border=1><tr><td><b> DJ </b></td><td><b> ARTIST </b></td><td><b> SONG </b></td><td><b> LABEL </b></td><td><b> DATE </b></td></tr>";
	while ($row = mysql_fetch_array($result)) {
		echo "<tr><td> $row[dj] </td><td> $row[artist] </td><td> $row[song] </td><td> $row[label] </td><td>$row[date] </td></tr>";
	}
	echo "</table>";
	echo "</ul>\n";

}
else{
echo "<form name='input' action='charting.php' method='get'>";
echo"Start Date = <input type='text' name='startdate' value='$startdate'><br>";
echo"End Date = <input type='text' name='enddate' value='$enddate'><br>";
echo"<input type='submit' calue='Submit' />";

}

?>
<?php
require("../include/footer.php");
?>


</body>
</html>
