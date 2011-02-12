<?php
echo '
<div id="topbar">
<a href="http://www.wupx.com/arcums/playlist/index.php">	<img src="../images/arcumsheader.png" width="203" height="58" border="0" /></a>
</div>


 <div id="topbarmenuother" class="menu">
<table><tr><td>
';

$session_username = $_SESSION['username'];
$query="SELECT DISTINCT date FROM playlist WHERE dj = '$session_username' ORDER by date DESC";
$result = mysql_query ($query);
echo '
<form action="' . $root_url . 'playlist/viewdate.php">
<u>Past Playlists</u>
<br>
<select name="datelist">
';
while($datelist=mysql_fetch_array($result)){
echo "<option value='$datelist[date]'>$datelist[date]</option>";
}
echo "</select>
<input type='hidden' name='anotherdj' value='$session_username'>
<input type=\"submit\" value=\"View\"></form>";
?></td><td>


<?php
$query="SELECT * FROM djs ORDER by name ASC";
$result = mysql_query ($query);
echo '
<form action="' . $root_url . 'playlist/selectdate.php" method="get">
<u>Other DJs Playlists</u>
<br>
<select name="anotherdj">
';
while($nt=mysql_fetch_array($result)){
echo "<option value=$nt[username]>$nt[name]</option>";
}
echo "</select> <input type=\"submit\" value=\"View\"></form>";
?>

&nbsp;&nbsp;&nbsp;<a href="http://www.wupx.com/arcums/playlist/index.php">Playlist</a> | <a href="http://www.wupx.com/arcums/profile/">My Profile</a>
| <a href="http://www.wupx.com/arcums/downloads/">Record Show</a> | <a href="http://www.wupx.com/arcums/blog/">Blog</a> | <a href="http://www.wupx.com/arcums/catalog/">Catalog</a>


</div></td></tr></table></div>
<br><br><br><br>

<!--
<table width="735" align="center">
<tr><td class="headers" align="center">
Don't forget you can record your show! This is a great way to hear yourself and improve your radio voice. <img src="../images/arrowup.png" height="15" width="20">
</td></tr></table> -->



<?php


$get_info = mysql_query("SELECT * FROM accounts WHERE username = '$session_username' LIMIT 1");
if(mysql_num_rows($get_info) > 0)
{
$user_info = mysql_fetch_assoc($get_info);


//PERMISSIONS
//1=DJ
//2=Genre Director
//3=Staff
//4=Administrator
//

if ($user_info['permissions'] == '2') {
  echo '
<table width="735" align="center">
<tr><td class="headers" align="center">Genre Director Menu: &nbsp;&nbsp;&nbsp;&nbsp;
<a href="../charting/index.php">Arcums Charting </a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../catalog/menu.php">Catalog Admin</a>&nbsp;&nbsp;&nbsp;&nbsp;
</td></tr>
</table>
';}


if ($user_info['permissions'] == '3') {
  echo '
<table width="735" align="center">
<tr><td class="headers" align="center">Staff Menu: &nbsp;&nbsp;&nbsp;&nbsp;
<a href="../profile/staff_edit.php">Staff Profile </a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../charting/index.php">Arcums Charting </a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../charting/search.php">@sshole Tracker </a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../local_bands/index.php">Local Bands[TESTING]</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../webcharting/index.php">WebCharting</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../catalog/menu.php">Catalog Admin</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../events/index.php">Events</a>&nbsp;&nbsp;&nbsp;&nbsp;
</td></tr>
</table>
';}

if ($user_info['permissions'] == '4') {
  echo '
<table width="735" align="center">
<tr><td class="headers" align="center">Staff Menu: &nbsp;&nbsp;&nbsp;&nbsp;
<a href="../profile/staff_edit.php">Staff Profile </a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../charting/index.php">Arcums Charting </a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../charting/search.php">@sshole Tracker </a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../local_bands/index.php">Local Bands[TESTING]</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../webcharting/index.php">WebCharting</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../catalog/menu.php">Catalog Admin</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../events/index.php">Events</a>&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin">Administration </a>
</td></tr>
</table>
';}

}
?>
