<?php
session_start();
require("../..//config.php");
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

echo '<center><br /><table width="600"> <tr><td>
<center>

<table class="welcomebar">
<tr>
<td class="date">
Blog Administration
</td>
<td class="loggedin">
You\'re logged in as '; echo $user_info['username'];
echo '
<a href="'.$root.'/arcums/login/logout.php">[logout]</a>&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>';


	echo "<center><table class='addtablemain' bgcolor='black' align=\"center\" width=\"650\"><tr class=\"headers\" align=\"center\"><td>";
	echo "<br><font size=\"5\"> WUPX BLOG</font><br>";
	echo "<font size=\"2\">The blog is up and ready to post to. You are responsible for the content of your own posts.</td></tr><tr bgcolor='black' align='center'><td>";
	echo "<a href=\"post.php\">Add New Blog Post</a><br>";
	echo "<a href=\"view.php?type=0\">Edit DJ Blog</a><br>";
if($user_info['permissions'] > 1){
	echo "<a href=\"view.php?type=1\">Edit E-staff Blog</a><br>";
}
	echo "</td></tr></table></center>";


