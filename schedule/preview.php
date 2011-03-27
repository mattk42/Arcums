<?

require_once("../config.php");

$query = "SELECT djid FROM playlist ORDER BY datetime DESC LIMIT 1";
$res = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($res);
$djid = $row[0];

$dj_query = "SELECT djname, showname FROM accounts WHERE id='$djid'";
$res = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($res);
echo $row[0]."-".$row[1];

?>
