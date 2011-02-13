<?php
require("../../header.php");
require("../../config.php");

$albumid = mysql_real_escape_string($_POST["albumid"]);
$trackid = mysql_real_escape_string($_POST["trackid"]);
$datetime = date("YmdHis");

$query = "INSERT INTO request(album_id,track_id,datetime) VALUES('$albumid','$trackid','$datetime')";
mysql_query($query) or die("There was an error processing your request");

echo "Your request has been recorded.";

include("../../footer.php");	
?>

