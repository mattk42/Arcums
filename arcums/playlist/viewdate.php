<?php 
session_start(); 
require("../include/config.php");
require("../include/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ARCUMS 2.0</title>
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php 
require("../include/header.php");
?>
<?php


if(isset($_SESSION['dj_logged_in']))
{
$session_username = $_SESSION['username'];
// further checking...
if(username_exists($session_username))
{

$query2="SELECT name FROM djs WHERE djname = '$anotherdj'";
$result2 = mysql_query ($query2);
$anotherdj = mysql_real_escape_string($_GET['anotherdj']);
$getdate = mysql_real_escape_string($_GET['datelist']);
$query="SELECT DISTINCT date FROM playlist WHERE dj = '$anotherdj' ORDER by date DESC";
$result = mysql_query ($query);

$findname="SELECT * FROM djs WHERE username = '$anotherdj'";
$nameresult = mysql_query ($findname);
$displayname=mysql_fetch_array($nameresult);

echo '

<center>
<Br>

<table class="welcomebar">
<tr>
<td class="date">
';
echo $displayname[name]; echo'\'s playlist for '; echo $getdate;
echo '
</td>
<td class="loggedin">
You\'re logged in as '; echo $session_username;
echo '
<a href="../login/logout.php">[logout]</a>
</td>
</tr>
</table>
';

echo "
<form name=\"jump\" class=\"chooseadate\">
<u>Choose a Date:</u>
<select name=\"menu\">
";
while($datelist=mysql_fetch_array($result)){
echo "<option value='../playlist/viewdate.php?datelist=$datelist[date]&anotherdj=$anotherdj'>$datelist[date]</option>";
}
echo "</select>
<input type=\"button\" onClick=\"location=document.jump.menu.options[document.jump.menu.selectedIndex].value;\" value=\"GO\">
</form>";



echo "

<br>

<div align=\"center\">

  <table class=\"playlist\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
    <tr>

	  <th align=\"left\" class=\"headers\"> &nbsp;Artist </th>
	  <th align=\"left\" class=\"headers\"> Song </th>
	  <th align=\"center\" class=\"headers\"> Section </th>
	  <th align=\"center\" class=\"headers\"> Req. </th>
	  <th align=\"center\" bgcolor=\"#000000\"  class=\"headers\">Est. Time Played</th>

	  </tr>
";

$datelist = mysql_real_escape_string($_GET['datelist']);
$playlist = mysql_query("SELECT * FROM playlist WHERE date = '$datelist' AND dj = '$anotherdj' ORDER by auto ASC");
$numofrows = mysql_num_rows($playlist);

if(mysql_num_rows($playlist) > 0)
{
//create a loop, because there are rows in the DB

{

for($i = 0; $i < $numofrows; $i++) {
    $rows = mysql_fetch_array($playlist); //get a row from our result set
    if($i % 2) { //this means if there is a remainder
        echo "<TR  bgcolor=\"#84C8FF\">\n";
    } else { //if there isn't a remainder we will do the else
        echo "<TR  bgcolor=\"#ededed\">\n";
    }
    echo "
	  
	  <td >&nbsp;".$rows['artist']."</td>
	  <td >&nbsp;".$rows['song']."</td>
	 
	  <td class=\"playlisttable\" align=\"center\">".$rows['section']." ".$rows['sectionnumber']."</td>

<td class=\"playlisttable\" align=\"center\">
	 ";
		  if ($rows['requested'] != '0')  { echo "<img src=\"../images/checkmark.png\">"; }
			else {echo "&nbsp;&nbsp;&nbsp;";}
	
	  echo " &nbsp;</td>


  <td align='center'>".$rows['time'].""; 





echo "
</td>
	  ";	


		  }

		  }

	}
else
{
echo 'Playlist not found.';
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


</body>
</html>
