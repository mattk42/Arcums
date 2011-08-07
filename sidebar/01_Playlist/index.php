<?php
	// Playlists.php
	// -- this file accepts $_GET input and returns a list of songs from the playlist table
	// 		-- if nothing is supplied, it returns a generic list of the last 10 songs played
	// 		-- if type=dj&dj=<djname> is supplied, it will return the last 10 songs played for that dj username
	// 				-- if limit=<number> is supplied, it returns that many songs; default is 10.  Must be between 1 and 25

	if(isset($_GET['type'])){
		$type = mysql_real_escape_string($_GET['type']);
	}
	else{
		$type="";
	}
	$options = array("options" =>	array("min_range" => 1, "max_range" => 25));
	$limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT, $options);


	$limit = ($limit !== false && !empty($limit)) ? $limit : 6;


	if ($type == "dj") {
		$dj = mysql_real_escape_string(filter_input(INPUT_GET, 'dj', FILTER_SANITIZE_SPECIAL_CHARS));
		$query = "SELECT artist, song, date, time FROM playlist WHERE date != '' ";
		$query.= " AND dj = '$dj'";
	}
	else{
		  $query = "SELECT artist as artist, song as song, label as label, datetime as date FROM playlist ORDER BY date DESC limit $limit";
	}


	$result = mysql_query($query) or die("Query failed.");

	echo "<ul>\n";
	while ($row = mysql_fetch_array($result)) {
		echo "<li><strong>". stripslashes($row['song']). "</strong></li>\n";
		echo "<li style=\"padding-bottom: 10px;\">". stripslashes($row['artist']). "</li>\n";
	}
	echo "</ul>\n";


?>
