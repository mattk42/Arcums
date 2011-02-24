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
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
if(isset($_SESSION['dj_logged_in']))
{
$session_username = $_SESSION['username'];
// further checking...
if(username_exists($session_username))
{
$get_info = mysql_query("SELECT * FROM accounts WHERE username = '$session_username' LIMIT 1");
if(mysql_num_rows($get_info) > 0)
{
$user_info = mysql_fetch_assoc($get_info);
if(!isset($_POST['do_edit']))
{
echo '<center><br /><table width="600"> <tr><td>
<center>

<table class="welcomebar">
<tr>
<td class="date">
Staff Profile Editor
</td>
<td class="loggedin">
You\'re logged in as '; echo $session_username;
echo '
<a href="../login/logout.php">[logout]</a>&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>

<table><tr><td bgcolor="#ededed" valign="top">

<img src="photos/' . $user_info['photo'] . '" width="125" height="150"><br>
<a href="index.php">Refresh Photo</a><br>
<A HREF="javascript:popUp(\'upload_image.php\')">Change Photo</A><br><br>

<a href="javascript:popUp(\'change_password.php\')">Change Password</a> <br>
<br>
';



if ($user_info['permissions'] >= '3') {
  echo '
<a href="index.php">DJ Profile</a>

';
}



echo '
</td><td>
&nbsp;</td><td>
<center>
<form action="staff_edit.php" method="post">
  <table  bgcolor="#ededed" width="550" border="0" cellspacing="3" cellpadding="3">
<tr>
<td class="headers" colspan="2">
Promote Your Show:
</td></tr><tr><td colspan="2">
<center>
Promote your show by uploading a banner that will be shown on every page on our site.<br> Banner should be 425x75 pixels.  No larger then 80KB.<br>
';

if ($user_info['banner'] != '') {
  echo "<img src='banners/$user_info[banner]' alt='Banner Preview Will Be Here After Upload'><br>
<A HREF=\"delete_banner.php\" onclick=\"javascript:return confirm('Are you sure you want to delete your current banner?'); return false;\">Delete Banner</A> |";
}

echo '

<a href="index.php">Refresh Banner</a>
 | <A HREF="javascript:popUp(\'upload_banner.php\')">Upload Banner</A>
</td></tr>
<tr>
<td class="headers" colspan="2">
Personal Information
</td></tr><tr><td bgcolor="#0089bc" colspan="2">
</center>
<div class="textme">If you leave a question blank, it\'ll be hidden on the site.  Fill out only what you want to be shown. Your time <b><u>MUST</u></b> be filled out correctly though. <br><br> You can use "hide account" so that you can use the login for the Playlist application, but not be listed on the DJ profiles part of the site.</div>


</td>
</tr>
  


<tr>
      <td class="headers"> <b>Position:</b> </td>
      <td> &nbsp;<input type="text" name="position"  class="register_box" value="' . $user_info['position'] . '" /></td>
    </tr>

 
    <tr>
      <td bgcolor="06bc00"><b>Goals:</b></td>
      <td> &nbsp;<textarea name="goals"  cols="40" rows="8" class="register_box" />'.$user_info['goals'] . '</textarea></td></tr><tr>
      <td bgcolor="000000"><b>Office Hours:</b></td>
      <td>

<table>
 <tr>
<td bgcolor="06bc00"><b>Monday</b></td>
      <td><input type="text" name="monday" class="register_box"  value="' . $user_info['monday'] . '" /></td>
</tr>
 <tr>
<td bgcolor="06bc00"><b>Tuesday</b></td>
      <td><input type="text" name="tuesday" class="register_box"  value="' . $user_info['tuesday'] . '" /></td>
</tr>
 <tr>
<td bgcolor="06bc00"><b>Wednesday</b></td>
      <td><input type="text" name="wednesday" class="register_box"  value="' . $user_info['wednesday'] . '" /></td>
</tr>
 <tr>
<td bgcolor="06bc00"><b>Thursday</b></td>
      <td><input type="text" name="thursday" class="register_box"  value="' . $user_info['thursday'] . '" /></td>
</tr>
 <tr>
<td bgcolor="06bc00"><b>Friday</b></td>
      <td><input type="text" name="friday" class="register_box"  value="' . $user_info['friday'] . '" /></td>
</tr>
 <tr>
<td bgcolor="06bc00"><b>Saturday</b></td>
      <td><input type="text" name="saturday" class="register_box"  value="' . $user_info['saturday'] . '" /></td>
</tr>

<tr>
<td bgcolor="06bc00"><b>Sunday</b></td>
      <td><input type="text" name="sunday" class="register_box"  value="' . $user_info['sunday'] . '" /></td>
</tr>

</table>
 
<tr>
      <td class="headers"></td>
      <td class="headers"><br></td></tr>

    <tr>
      <td><br></td>      <td><input type="submit" name="do_edit" class="register_box"  value="Update Profile" /></td>
    </tr>
    
  </table></td></tr></table>
</form><br /><br /></td></tr></table></center>
';
}
elseif(isset($_POST['do_edit']))
{


//Staff options
$position = mysql_real_escape_string($_POST['position']);
$goals = mysql_real_escape_string($_POST['goals']);
$monday = mysql_real_escape_string($_POST['monday']);
$tuesday = mysql_real_escape_string($_POST['tuesday']);
$wednesday = mysql_real_escape_string($_POST['wednesday']);
$thursday = mysql_real_escape_string($_POST['thursday']);
$friday = mysql_real_escape_string($_POST['friday']);
$saturday = mysql_real_escape_string($_POST['saturday']);
$sunday = mysql_real_escape_string($_POST['sunday']);


// if array elements is greater than 0,
// then we KNOW there was an error
// else, no error, move on to processing
if(count($errors) > 0)
{
echo '<b>ERRORS:</b><br />';
foreach($errors as $err)
{
echo $err . '<br />';
}
}
else
{
// everything is ok, update the DB
mysql_query("UPDATE djs SET position = '$position', monday = '$monday', tuesday = '$tuesday', wednesday = '$wednesday', thursday = '$thursday', friday = '$friday', saturday = '$saturday', sunday = '$sunday', goals = '$goals' WHERE username = '$session_username'");
echo "<div class='headers'>Profile Updated. <a class='headers' href'index.php'>Continue...</a></div>";

}
}
}
else
{
echo 'Could not find profile info for your username.';
}
}
else
{
echo '<b>Sorry, your session username doesnt exist</b>.';
}
}
else
{
echo '<div class="headers">&nbsp;&nbsp;You must be logged in to edit your profile. <a class="headers" href="../login/index.php">Go to login page...</a></div>';
}
?>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=0,location=0,statusbar=1,menubar=0,resizable=1,width=400,height=225,left = 312,top = 309');");
}
// End -->
</script>
