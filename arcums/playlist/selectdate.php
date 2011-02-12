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
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
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

$anotherdj = $_GET['anotherdj'];
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
Select playlist date for '; echo $displayname[name];
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
<table width="600">
<?php
require("../include/footer.php");
?></table>


</body>
</html>



