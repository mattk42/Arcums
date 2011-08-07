<?php
session_start();
include("../../header.php");
require("../../config.php");
require("../include/functions.php");

//echo some styles to spice it up...
echo "
";
// if the variable member_id has been set, or there is a value assigned to it...
if(isset($_GET['member_id']))
{
$member_id = (INT)$_GET['member_id'];
$member_info = mysql_query("SELECT * FROM accounts WHERE is_activated = '1' AND id = '$member_id' LIMIT 1") or die(mysql_error());
if(mysql_num_rows($member_info) > 0)
{
// we can go ahead and assign it to an array because this user exists
$profile_info = mysql_fetch_assoc($member_info);



echo "
<table><tr><td width=\"438\">
<h1>$profile_info[djname]</h1>";
echo "
<table width=\"100%\" height=\"160\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
    <td width=\"125\" height=\"150\" valign=\"top\" rowspan=\"3\"><img src=\"photos/{$profile_info['photo']}\" width=\"125\" height=\"150\"></td>
    <td width=\"90%\" valign=\"top\" align=\"center\"><table width=\"95%\" height=\"0\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";

if ($profile_info['name'] != '') {
  echo "<tr><td valign=\"top\" ><B>Real Name:</b></td> <td valign=\"top\" >{$profile_info['name']}</td></tr>";
}

if ($profile_info['showname'] != '') {
  echo "<tr><td valign=\"top\" ><B>Show Name:</b></td><td valign=\"top\" >{$profile_info['showname']}</td></tr>";
}

if ($profile_info['showday'] != '') {
  echo "<tr><td valign=\"top\" ><B>Show Day: </b></td><td valign=\"top\" >{$profile_info['showday']}</td></tr>";
}

if ($profile_info['showtime'] != '') {
  echo "<tr><td valign=\"top\" ><B>Show Time: </b></td><td valign=\"top\" >{$profile_info['showtime']}</td></tr>";
}

if ($profile_info['showgenre'] != '') {
  echo "<tr><td valign=\"top\" ><B>Show Genre: </b></td><td valign=\"top\" > {$profile_info['showgenre']}</td></tr>";
}

echo "</table>
</td></tr></table>
  <tr>
    <td colspan=\"2\" valign=\"top\">";


if ($profile_info['hideemail'] == '0') {
  echo "<B>E-Mail: </b><a href='mailto:$profile_info[email]'>$profile_info[email]</a><br>";
}

if ($profile_info['aim'] != '') {
  echo "<B>AIM: </b>{$profile_info['aim']}<br>";
}

if ($profile_info['yahoo'] != '') {
  echo "<B>Yahoo: </b>{$profile_info['yahoo']}<br>";
}

if ($profile_info['msn'] != '') {
  echo "<B>MSN: </b>{$profile_info['msn']}<br>";
}

if ($profile_info['website'] != '') {
  echo "<B>Website: </b><a href='$profile_info[website]' target=\"_blank\">{$profile_info['website']}</a><br>";
}

if ($profile_info['myspace'] != '') {
  echo "<B>MySpace: </b><a href=\"$profile_info[myspace]\" target=\"_blank\">{$profile_info['myspace']}</a><br>";
}

if ($profile_info['major'] != '') {
  echo "<B>Major: </b>{$profile_info['major']}<br>";
}

if ($profile_info['minor'] != '') {
  echo "<B>Minor: </b>{$profile_info['minor']}<br>";
}

if ($profile_info['home'] != '') {
  echo "<B>Home Town: </b>{$profile_info['home']}<br>";
}

if ($profile_info['favgenre'] != '') {
  echo "<B>Favorite Genre: </b>{$profile_info['favgenre']}<br>";
}

if ($profile_info['favartist'] != '') {
  echo "<B>Favorite Artist: </b>{$profile_info['favartist']}<br>";
}

if ($profile_info['favcd'] != '') {
  echo "<B>Favorite Album: </b>".stripslashes($profile_info['favcd'])."<br>";
}

if ($profile_info['favmovie'] != '') {
  echo "<B>Favorite Movie: </b>" . stripslashes($profile_info['favmovie']) . "<br>";
}
if ($profile_info['bio'] != '') {
  echo "<B>Biography: </b>" . stripslashes($profile_info['bio']) . "<br>";
}

echo "
</td>
  </tr>
</table>";



}
else
{
echo "That member does not exist, or is not activated yet!";
} 
}
else
{
echo "There was no member ID to view a profile for!";
}
?> 
<?php include("../../footer.php");?>
