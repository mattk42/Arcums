<?php 
session_start(); 
require("../../config.php");
require("../include/functions.php");
require("../include/header.php");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ARCUMS 2.0</title>
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
echo "<center>";
if($user_info['permissions'] > 1)
{

if(isset($_GET['startdate'])){

	$startdate = date("Y-m-d",strtotime($_GET['startdate']));
	$enddate = date("Y-m-d",strtotime($_GET['enddate']));

	echo $startdate;
	$query = "SELECT dj,artist, song, label, datetime as date FROM playlist where date between '$startdate' and '$enddate'";
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
