<?
require("../../config.php");

	$id = mysql_real_escape_string($_GET['id']);
	
	$query = "DELETE FROM events WHERE id=$id";

	mysql_query($query) or die(mysql_error());

echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"0; url=http://www.wupx.com/arcums/events/?del=" . urlencode($id) . "\">";

?>
