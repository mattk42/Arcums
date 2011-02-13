<?php

require_once("../../config.php");
require_once("../include/catalog_functions.php");

if(isset($argv[1])){
        $artist = mysql_real_escape_string($argv[1]);
        $query = "SELECT DISTINCT(artist) FROM albums WHERE artist = '$artist'";
        $res = mysql_query($query) or die(mysql_error());

}
else if(isset($_GET['artist'])){
	$artist = mysql_real_escape_string($_GET['artist']);
	$query = "SELECT DISTINCT(artist) FROM albums WHERE artist = '$artist'";
	$res = mysql_query($query) or die(mysql_error());
	
}
else {
	$query = "SELECT DISTINCT(artist),similar_cache_date FROM albums WHERE similar_cache_date <= SUBDATE(NOW(), INTERVAL 31 DAY)";
	$res = mysql_query($query) or die(mysql_error());
}

while($row = mysql_fetch_array($res)){
	echo $row[0] . " (" . date('Y-m-d') .")\n";
	$art1 = mysql_real_escape_string($row[0]);

        $query = "DELETE FROM similar WHERE artist='$art1'";
        mysql_query($query) or die(mysql_query());


	$similar_xml = get_similar($row[0], 10);
		if($similar_xml){
			foreach ($similar_xml->similarartists->artist as $sartist){
				$art = mysql_real_escape_string($sartist->name);
				echo "\t $art \n";
					$query = "INSERT INTO similar VALUES('$art1','$art')" or die(mysql_error());
					mysql_query($query) or die(mysql_error());
			}
		}

	$query = "UPDATE albums SET similar_cache_date=NOW() WHERE artist = '$art1'";
	mysql_query($query) or die(mysql_error());
}


?>

