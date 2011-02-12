<?php include("../header.php") ?>
<?php
require("../../config.php");
//echo some styles to spice it up...
echo "<h2>Radio X DJs</h2><br \>
<a href=\"http://www.wupx.com/arcums\">DJ Login</a><br />";
if(empty($_GET['day']){
	$_GET['day']="All";
}
?>
<form name='dayselect' method='get'>
	<table width='100%'><tr><td valign='top'>
				
		<b>Now Viewing: <? echo $_GET['day'];?></b></td><td align='right' valign='top'>
		Select Day: <select style="font-size:12px;color:#006699;font-family:verdana;background-color:#ffffff;" name="menu">
			<option value="All">All</option>
			<option value="Monday">Monday</option>
			<option value="Tuesday">Tuesday</option>
			<option value="Wednesday">Wednesday</option>
			<option value="Thursday">Thursday</option>
			<option value="Friday">Friday</option>
			<option value="Saturday">Saturday</option>
			<option value="Sunday">Sunday</option>
		</select>
	<input style="font-size:12px;color:#ffffff;font-family:verdana;background-color:#006699;" type="button" value="click here">
	</td></tr></table>
<hr>
</form>

<?php
if($_GET['day']=="All"){
	$_GET['day']="%";
}
$all_members = mysql_query("SELECT * FROM djs WHERE is_activated=1 AND showday LIKE  '$_GET['day']%' ORDER BY showtime ASC") or die(mysql_error());
if(mysql_num_rows($all_members) > 0)
{
//create a loop, because there are rows in the DB
while($row = mysql_fetch_assoc($all_members))
{
echo "<table><tr><td><img src=\"http://www.wupx.com/arcums/profile/photos/{$row['photo']}\" width=\"75\" height=\"100\"></td><td><b>$row[name] - $row[djname]</b><br />$row[showday] @ $row[showtime]<br />Genre: $row[showgenre]<br />[<a href='http://www.wupx.com/arcums/profile/view_profile.php?member_id=$row[id]'>View Profile</a>] <br /><br /></td></tr></table>";
}
}
else
{
echo "No djs to display.";
}
?> 
 <hr>

<?php include("../footer.php") ?>
