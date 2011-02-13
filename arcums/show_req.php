<?php
echo '<link href="css/arcums.css" rel="stylesheet" type="text/css" />';
include ('../config.php');
$limit = 10;
$query = "SELECT catalog_albums.artist, catalog_tracks.title, catalog_request.datetime, catalog_albums.id FROM catalog_request,catalog_albums,catalog_tracks WHERE catalog_albums.id=catalog_request.album_id AND catalog_tracks.id=catalog_request.track_id ORDER BY datetime DESC LIMIT $limit";
$res = mysql_query($query) or die(mysql_error());
echo "<table cellspacing=0 class='reqtablemain'><tr class='headers'><td><b>Artist</b></td><td><b>Track</b></td><td><b>Requested Time</b></td></tr>";

while ($row = mysql_fetch_array($res)) {
    echo "<tr  bgcolor=\"#ededed\"  onclick=\"document.location.href='/arcums/catalog/view?id=$row[3]';\" onmouseover=\"style.background='#FFFF00';\" onmouseout=\"style.background='#ededed';\"><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr><br>";
}
echo "</table>";
?>
