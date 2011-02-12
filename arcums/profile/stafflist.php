<?php
require("config.php");
require("arcums/include/functions.php");
//echo some styles to spice it up...
echo "<h2>E-Staff</h2><br /><a href=\"".$root."/arcums\">E-Staff Login</a><br \><br \>
";
$all_members = mysql_query("SELECT * FROM accounts WHERE permissions >= 3 ORDER BY stafforder ASC") or die("Error getting staff list.");
if(mysql_num_rows($all_members) > 0)
{
//create a loop, because there are rows in the DB
while($row = mysql_fetch_assoc($all_members))
{


echo "<table><tr><td valign=\"top\"><img src=\"$root/arcums/profile/photos/{$row['photo']}\" width=\"125\" height=\"150\"></td>
<td>
<b>$row[position] - $row[name]</b> 
<br>
[<a href='arcums/profile/view_profile.php?member_id=$row[id]'>View Profile</a>]
";

echo"<table width=\"250\"><tr><td colspan=\"2\" bgcolor='ffffff'><font color='000000'><b> Office Hours</td></tr></font>";

if ($row['monday'] != '') {
  echo "<tr><td valign=\"top\" ><B>Monday</b></td><td valign=\"top\" >{$row['monday']}</td></tr>";
}
if ($row['tuesday'] != '') {
  echo "<tr><td valign=\"top\" ><B>Tuesday</b></td><td valign=\"top\" >{$row['tuesday']}</td></tr>";
}

if ($row['wednesday'] != '') {
  echo "<tr><td valign=\"top\" ><B>Wednesday</b></td><td valign=\"top\" >{$row['wednesday']}</td></tr>";
}

if ($row['thursday'] != '') {
  echo "<tr><td valign=\"top\" ><B>Thursday</b></td><td valign=\"top\" >{$row['thursday']}</td></tr>";
}

if ($row['friday'] != '') {
  echo "<tr><td valign=\"top\" ><B>Friday</b></td><td valign=\"top\" >{$row['friday']}</td></tr>";
}
if ($row['saturday'] != '') {
  echo "<tr><td valign=\"top\" ><B>Saturday</b></td><td valign=\"top\" >{$row['saturday']}</td></tr>";
}
if ($row['sunday'] != '') {
  echo "<tr><td valign=\"top\" ><B>Sunday</b></td><td valign=\"top\" >{$row['sunday']}</td></tr>";
}

echo"<tr><td colspan=\"2\" bgcolor='ffffff'></td></tr></font></table>";



 echo "<br /></td></tr></table><br>";
}
}
else
{
echo "No members to display.";
}
?> 
