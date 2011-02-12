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

<?php
echo "<center>";
if($user_info['permissions'] > 1)
{
$link = mysql_connect("localhost", "arcums", "arcums123") or die("Could not connect to database");
$startdate = filter_input(INPUT_GET, 'startdate', FILTER_SANITIZE_SPECIAL_CHARS);
$enddate = filter_input(INPUT_GET, 'enddate',FILTER_SANITIZE_SPECIAL_CHARS);

if($startdate !== false && !empty($startdate) && $enddate !== false && !empty($enddate)){
	$query = "SELECT dj,artist, song, label, datetime as date FROM arcums.playlist where date between '$startdate' and '$enddate' UNION SELECT songrights as dj,artist, title as song, label, date_played as date from automation.historylist where automation.historylist.date_played between '$startdate' and '$enddate' order by date";
	$result = mysql_query($query) or die("Query failed.");
	
	echo "<ul>\n";
	echo "<table border=1 width='70%'><tr><td><b> DJ </b></td><td><b> ARTIST </b></td><td><b> SONG </b></td><td><b> LABEL </b></td><td><b> DATE </b></td></tr>";
	while ($row = mysql_fetch_array($result)) {
		echo "<tr><td> $row[dj] </td><td> $row[artist] </td><td> $row[song] </td><td> $row[label] </td><td>$row[date] </td></tr>";
	}
	echo "</table>";
echo "<form action='export.php' method='get'>
  <input name='startdate' type='hidden' id='genre' value='$startdate'/> 
  <input name='enddate' type='hidden' id='catnumber' value='$enddate' />
    <input type='submit' name='button' id='button' value='Export' /></ul>";

}
else{
echo "This isn't completely done yet. But it can be used to get a accurate chart of what has been played between 2 dates. Enter the dates you wish to look between in the format YYYY-MM-DD and click submit. If you want to export that list to an excell sheet, there is a button at the bottom for that.<br><br>";
echo "<form name='input' action='index.php' method='get'>";
echo"Start Date = <input type='text' name='startdate' value='$startdate'><br>";
echo"End Date = <input type='text' name='enddate' value='$enddate'><br>";
echo"<input type='submit' calue='Submit' /><br>";
}
}

require("../include/footer.php");
?>
</center>

</body>
</html>
