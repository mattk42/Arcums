
<?php
mysql_connect("localhost", "arcums", "arcums123") or die(mysql_error());
echo "Connected to MySQL<br />";
mysql_select_db("arcums") or die(mysql_error());
echo "Connected to Database";
?>
<br><br>

<?php
// Make a MySQL Connection

$query = "SELECT djs.name, djs.djname, playlist.dj, playlist.artist, (SELECT artist, djname FROM playlist GROUP BY djname) AS total_plays FROM djs, playlist GROUP BY name ORDER by name ASC"; 
	 
$result = mysql_query($query) or die(mysql_error());

// Print out result
while($row = mysql_fetch_array($result)){
	echo $row['name'] ." played ". $row['total_plays'] ." songs on their last show.";
	echo "<br />";
}
?>


