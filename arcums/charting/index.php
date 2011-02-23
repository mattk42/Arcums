<?php
session_start();
$_SESSION['currentpage'] = $_SERVER['REQUEST_URI'];
require_once("../../config.php");
require_once("../include/functions.php");
?>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ARCUMS 2.0</title>
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
if(isset($_SESSION['dj_logged_in']))
{
$session_username = $_SESSION['username'];
// further checking...
if(username_exists($session_username))
{

require("../include/header.php");
$query2="SELECT name FROM djs WHERE djname = '$anotherdj'";
$result2 = mysql_query ($query2);
$anotherdj = mysql_real_escape_string($_GET['anotherdj']);
$getdate = mysql_real_escape_string($_GET['datelist']);
$query="SELECT DISTINCT date FROM playlist WHERE dj = '$anotherdj' ORDER by date DESC";
$result = mysql_query ($query);
$getcatnumber = mysql_real_escape_string($_GET['catnumber']);
$getgenre = mysql_real_escape_string($_GET['genre']);
echo "
<div align=\"center\">


<table class=\"welcomebar\">
<tr>
<td class=\"date\">
Last 7 Days of New Music: $getgenre > $getcatnumber
</td>
<td class=\"loggedin\">
You're logged in as "; echo $session_username;
echo "
<a href=\"../login/logout.php\">[logout]</a>&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>

<table class='searchbox'> 
<tr>
<td class='headers' align=center>Section</td>
<td class='headers' align=center>Section #</td>
<td class='headers'></td>
<td class='headers'></td>


</tr><tr>
<form method='get' action='index.php'>
";

$query="SELECT prefix,name FROM catalog_categories ORDER by genres ASC";
$result = mysql_query ($query);
echo "<td><select name=\"genre\">";
while($nt=mysql_fetch_array($result)){
echo "
<option ";

if($nt['genres'] == $getgenre) { echo "selected"; }


echo "
 value=$nt[prefix]>$nt[name]</option>";
}
echo '</select></td>
 <td> <input name="catnumber" type="text" id="catnumber" maxlength="5" size="5"/></td>
   <td> <input type="submit" name="button" id="button" value="Submit" />
</form></td>
&nbsp;&nbsp;&nbsp;
<td><form action="export.php" method="get">
  <input name="genre" type="hidden" id="genre" value="'; echo $getgenre; echo '"/> 
  <input name="catnumber" type="hidden" id="catnumber" value="'; echo $getcatnumber; echo '" />
    <input type="submit" name="button" id="button" value="Export" />
</form></td></tr>
<tr>
<td colspan="4">Select a starting number.</td>

</table>

';






echo "



<br><br>
  <table class=\"lookupplays\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
    <tr>
	  <th class=\"headers\">CD </th>
	  <th class=\"headers\">Artist </th>
	  <th class=\"headers\">Album </th>
	  <th class=\"headers\">Times Played </th>


	  </tr>
";
$query = "SELECT artist, album, section, sectionnumber, date, COUNT(sectionnumber) AS timesplayed FROM playlist WHERE section = '$getgenre' AND sectionnumber > $getcatnumber AND date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY section, sectionnumber ORDER BY section ASC, timesplayed DESC";
$playlist = mysql_query($query) or die(mysql_error());
$numofrows = mysql_num_rows($playlist);

if(mysql_num_rows($playlist) > 0)
{
//create a loop, because there are rows in the DB

{

for($i = 0; $i < $numofrows; $i++) {
    $rows = mysql_fetch_array($playlist); //get a row from our result set
    if($i % 2) { //this means if there is a remainder
        echo "<TR bgcolor=\"#84C8FF\"  onmouseover=\"style.background='#FFFF00';\" onmouseout=\"style.background='#84C8FF';\" >\n";
    } else { //if there isn't a remainder we will do the else
        echo "<TR bgcolor=\"ededed\" onmouseover=\"style.background='#FFFF00';\" onmouseout=\"style.background='#ededed';\">\n";
    }
    echo "
	  <td>".$rows['section']." ".$rows['sectionnumber']."</td>
	  <td>".$rows['artist']."</td>
	  <td>".$rows['album']."</td>

<td align=\"center\">

".$rows['timesplayed']."
</td>



	  ";	

		  }
	  echo "</td></tr>";
		  }
	 
	}
else
{
echo '<span class="chooseadate">Select a genre and starting catalog number<br><br></span>';
}







}
else
{
echo '<b>Sorry, the username could not be found.</b>.';
}
}
else
{
echo '<meta HTTP-EQUIV="REFRESH" content="0; url=../login">';
}


?>

<?php
require("../include/footer.php");
?>
