<?php 
session_start();
$_SESSION['currentpage'] = $_SERVER['REQUEST_URI'];
require("../include/config.php");
require("../include/functions.php");
?>


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
$getcatnumber = mysql_real_escape_string($_GET['catnumber']);
$getgenre = mysql_real_escape_string($_GET['genre']);
echo "
<div align=\"center\">


<table class=\"welcomebar\">
<tr>
<td class=\"date\">
Hound Dog Asshole Tracker: $getgenre $getcatnumber
</td>
<td class=\"loggedin\">
You're logged in as "; echo $session_username;
echo "
<a href=\"../login/logout.php\">[logout]</a>&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>
<br>

<form>
";


$query="SELECT genres FROM genres ORDER by genres ASC";
$result = mysql_query ($query);
echo "<select name=\"genre\">";
while($nt=mysql_fetch_array($result)){
echo "<option ";

if($nt['genres'] == $getgenre) { echo "selected"; }


echo "
 value=$nt[genres]>$nt[genres]</option>";
}
echo '</select>
  <input name="catnumber" type="text" id="catnumber" size="5" maxlength="4" />
    <input type="submit" name="button" id="button" value="Submit" />
</form>

<span class="edit"> <a href="index.php">[Music Charting]</a></span>
';






echo "



<br><br>
  <table class=\"playlist\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
    <tr>
	  <th class=\"headers\">Date </th>
	  <th class=\"headers\">Time </th>
	  <th class=\"headers\">CD </th>
	  <th class=\"headers\">Artist </th>
	  <th class=\"headers\">Album </th>
	  <th class=\"headers\">Last DJ to Play It </th>
	  <th class=\"headers\"> </th>


	  </tr>
";




$playlist = mysql_query("SELECT date,time,section,artist,album, sectionnumber,djs.name,djs.email FROM playlist, djs WHERE section = '$getgenre' AND sectionnumber = $getcatnumber AND playlist.dj = djs.username ORDER BY date DESC, TIME DESC LIMIT 5");
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
	  <td>".$rows['date']."</td>
	  <td>".$rows['time']."</td>
	  <td>".$rows['section']." ".$rows['sectionnumber']."</td>
	  <td>".$rows['artist']."</td>
	  <td>".$rows['album']."</td>

<td align=\"center\">

".$rows['name']."
</td>
<td class='edit' bgcolor='#000000' align='center'> <A HREF=\"javascript:popUp('biteem.php?name=$rows[name]&email=$rows[email]&date=$rows[date]&time=$rows[time]&section=$rows[section]&sectionnumber=$rows[sectionnumber]&artist=$rows[artist]&album=$rows[album]')\">[send e-mail]</a></td>



	  ";	


		  }
	  echo "</td></tr>";
		  }
	  echo '  <tr><td align="center" colspan="7" class="headers">Powered by '; include("../include/version.php"); echo '. Created by <a href="mailto:dalcock@nmu.edu">Dustin Alcock</a></td></tr> </table>
	  </div> ';
	}
else
{
echo '<span class="chooseadate">Select a genre and starting catalog number<br></span>';
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
<br />
<div class="listfont" align="center">
<?php
require("../include/footer.php");
?></div>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=0,location=0,statusbar=1,menubar=0,resizable=1,width=400,height=100,left = 312,top = 309');");
}
// End -->
</script>
