<?php session_start();
$_SESSION['currentpage'] = $_SERVER['REQUEST_URI'];
require("../../config.php");
require("../include/functions.php");
require("../include/stream_functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script type="text/javascript">

String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, ''); };

function validate_required(field,alerttxt)
{
	with (field)
{
if (value==null||value=="")
  {alert(alerttxt);return false}
else {return true}
}
}

function validate_form(thisform)
{
	with (thisform)
{
if (validate_required(artist,"The artist must be filled out!")==false)
  {album.focus();return false}
if (validate_required(song,"The song title must be filled out!")==false)
  {label.focus();return false}
if (validate_required(label,"The record label must be filled out!")==false)
  {label.focus();return false}
if (validate_required(album,"The album name must be filled out!")==false)
  {label.focus();return false}
}
}
</script>

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
$session_dj_id = $_SESSION['djid'];
// further checking...
if(username_exists($session_username))
{



echo '

<center>

<!--<Br>
<table class="headers">
<tr>
<td>
NOTICE: We have started charting again which allows us to keep our web stream legal.  ALL FIELDS ARE REQUIRED. Sorry.<br><center> If you don\' t know the information, just google it.</center>
</td>
</tr>
</table>
<br>-->


<table class="welcomebar">
<tr>
<td class="date">
WUPX Playlist for '; echo date("m/d/y");
echo '
</td>
<td class="loggedin">
You\'re logged in as '; echo $session_username;
echo '
<a href="' . $root_url . 'login/logout.php">[logout]</a>&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>


<table><tr><td>





<form action="addparse.php" method="post" name="addsong" onsubmit="return validate_form(this)">
 <input type="hidden" name="date" value="';print date("Y-m-d");echo '" />
<input type="hidden" name="time" value="';print date("H:i:s");echo '" />
<input type="hidden" name="datetime" value="';print date("YmdHis");echo'" />
  <table class="addtablemain">
    <tr>
      <th class="headers">Artist</th>
      <th class="headers">Track#</th>
      <th class="headers">Song</th>
      <th class="headers">Album</th>
      <th class="headers">Record Label</th>
    </tr>
    <tr>
      <td align="center" ><input name="artist" type="text" onChange="javascript:this.value=this.value.toUpperCase().trim();" autocomplete="off"/></td>
      <td align="center" ><input name="tracknumber" type="text" size="1" maxlength="2"  onChange="javascript:this.value=this.value.toUpperCase().trim();" autocomplete="off"/></td>
      <td align="center" ><input name="song" type="text" onChange="javascript:this.value=this.value.toUpperCase().trim();" autocomplete="off"/></td>
	  <td align="center" ><input name="album" type="text" onChange="javascript:this.value=this.value.toUpperCase().trim();" autocomplete="off"/> </td>
	  <td align="center" ><input name="label" type="text" onChange="javascript:this.value=this.value.toUpperCase().trim();" autocomplete="off"/></td>

</tr>
<tr>

<td colspan="5" align="right" >
&nbsp;&nbsp;&nbsp;Web Listeners: <input readonly="readonly" name="listeners" value="'. getCurrentListeners() .'" size="4" maxlength="4" type="text" />



	
	&nbsp;&nbsp;	Section:
	  ';

 $query="SELECT prefix,name FROM catalog_categories ORDER by genres ASC";
$result = mysql_query ($query);
echo "<select name=\"section\">";
while($nt=mysql_fetch_array($result)){
echo "<option ";

if($nt['name'] == "GENERAL") { echo "selected"; }


echo "
 value=$nt[prefix]>$nt[name]</option>";
}
echo "</select>";

	  echo '


&nbsp;Section# <input name="sectionnumber" size="5" maxlength="5" type="text" />&nbsp;&nbsp;&nbsp;Requested <select name="requested">
        <option value="0" selected>No</option>
        <option value="1">Yes</option>
            </select>
	&nbsp;&nbsp;&nbsp;	<input name="submit" value="Add" type="submit" />&nbsp;&nbsp;&nbsp;
    </tr>

  </table>
</form>
</center>
';
echo "

<div align=\"center\">

  <table class=\"playlist\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
    <tr>

	  <th align=\"left\" class=\"headers\"> &nbsp;Artist </th>
	  <th align=\"left\" class=\"headers\"> Song </th>
	  <th align=\"left\" class=\"headers\"> Listeners </th>
	  <th align=\"center\" class=\"headers\"> Section </th>
	  <th align=\"center\" class=\"headers\"> Req. </th>
	  <th align=\"center\" bgcolor=\"#000000\" width=\"40\" class=\"headers\">&nbsp;</th>
	  <th align=\"center\" width=\"40\" bgcolor=\"#000000\" class=\"headers\">&nbsp;</th>

	  </tr>
";
$todaystart = date('Y-m-d 00:00:00');
$todayend = date('Y-m-d 23:59:59');

$query = "SELECT * FROM playlist WHERE dj_id = '$session_djid' AND datetime > '$todaystart' AND datetime < '$todayend' ORDER by id DESC";
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
        echo "<TR bgcolor=\"#ededed\" onmouseover=\"style.background='#FFFF00';\" onmouseout=\"style.background='#ededed';\">\n";
    }
    echo "

	  <td >&nbsp;".$rows['artist']."</td>
	  <td >&nbsp;".$rows['song']."</td>
	  <td >&nbsp;".$rows['listeners']."</td>
	  <td class=\"playlisttable\" align=\"center\">".$rows['section']." ".$rows['sectionnumber']."</td>

<td class=\"playlisttable\" align=\"center\">
	 ";
		  if ($rows['requested'] != '0')  { echo "<img src=\"../images/checkmark.png\">"; }
			else {echo "&nbsp;&nbsp;&nbsp;";}

	  echo " &nbsp;</td>

<td  class=\"edit\" bgcolor=\"#000000\" align=\"center\"><a href=\"edit.php?auto=".$rows['auto']."\">Edit</a> </td>

<td  class=\"delete\" bgcolor=\"#000000\" align=\"center\"> <a href=\"deleteparse.php?auto=".$rows['auto']."\" onclick=\"javascript:return confirm('Delete ".$rows['artist']. "&#8217;s &#8220;".$rows['song']."&#8221; from the playlist?'); return false;\">Delete </a>
</td>
	  ";

		  }
		  }
	 
	}

else
{
echo '<span class="chooseadate"><br>No playlist for today found.<Br><br>If it is midnight and you have already logged a few songs, they won\'t appear as the playlist starts over at midnight.  <br>You can view them under Past Playlists at the top.<br><br></span>';
}
}
else
{
echo '<b>Sorry, you are not logged in. </b>';
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
