<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require("../include/version.php"); ?></title>
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
session_start();
require("../include/header.php");
require("../include/config.php");
require("../include/functions.php");
if(isset($_SESSION['dj_logged_in']))
{
$session_username = $_SESSION['username'];
// further checking...
if(username_exists($session_username))
{
$get_info = mysql_query("SELECT djs.username, settings.stationid, settings.rooturl, settings.playedlimit FROM djs, settings WHERE djs.username = '$session_username' LIMIT 1");
if(mysql_num_rows($get_info) > 0)
{
$user_info = mysql_fetch_assoc($get_info);
if(!isset($_POST['do_edit']))
{
echo '




<table>
 <tr>
<td bgcolor="06bc00"><b>Station ID</b></td>
      <td><input type="text" name="stationid" class="register_box"  value="' . $user_info['username'] . '" /></td>
</tr>
 <tr>
<td bgcolor="06bc00"><b>Root URL: (ex: http://www.wupx.com)</b></td>
      <td><input type="text" name="rooturl" class="register_box"  value="' . $user_info['rooturl'] . '" /></td>
</tr>
 <tr>
<td bgcolor="06bc00"><b>Recently Played Limit:</b></td>
      <td><input type="text" name="playedlimit" class="register_box"  value="' . $user_info['playedlimit'] . '" /></td>
</tr>


</table>

';
}}}}
?>
