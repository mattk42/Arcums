<?

$query = "SELECT dj_id FROM playlist ORDER BY datetime DESC LIMIT 1";
$res = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($res);
$djid = $row[0];

$dj_query = "SELECT djname, showname FROM accounts WHERE id='$djid'";
$dj_res = mysql_query($dj_query) or die(mysql_error());
$dj_row = mysql_fetch_array($dj_res);
echo "<center><a href='$root/admin/profile/view_profile.php?member_id=$djid'>$dj_row[0]</a>-$dj_row[1]</center>";

?>
