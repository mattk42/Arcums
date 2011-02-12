<?php

	// Playlists.php
	// -- this file accepts $_GET input and returns a list of songs from the playlist table
	// 		-- if nothing is supplied, it returns a generic list of the last 10 songs played
	// 		-- if type=dj&dj=<djname> is supplied, it will return the last 10 songs played for that dj username
	// 				-- if limit=<number> is supplied, it returns that many songs; default is 10.  Must be between 1 and 25

	$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS);

	$options = array("options" =>	array("min_range" => 1, "max_range" => 25));
	$limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT, $options);

	$link = mysql_connect("localhost", "arcums", "arcums123") or die("Could not connect to database");

/*
//This updates automation, making sure all entries ahve a datetime
	$query = "SELECT date_played, replace(replace(replace(date_played,':',''),'-',''),' ','') as datetimen FROM automation.historylist where datetime IS NULL ORDER BY date_played DESC";
	$result = mysql_query($query) or die("Automation Update Query failed.");
	$queries = array();
	while($first_arc = mysql_fetch_array($result)){
			$queries[] = "UPDATE automation.historylist SET datetime='" . $first_arc['datetimen'] . "' WHERE date_played = '" . $first_arc['date_played'] . "'";
	}
	foreach($queries as $query2){
				mysql_query($query2) or die("Failed Query ");
	}
*/


	$limit = ($limit !== false && !empty($limit)) ? $limit : 6;


	if ($type == "dj") {
		$dj = mysql_real_escape_string(filter_input(INPUT_GET, 'dj', FILTER_SANITIZE_SPECIAL_CHARS));
		$query = "SELECT artist, song, date, time FROM arcums.playlist WHERE date != '' ";
		$query.= " AND dj = '$dj'";
	}
	else{
		  $query = "(SELECT artist as artist, song as song, label as label, datetime as date FROM arcums.playlist ORDER BY date DESC limit $limit) UNION (SELECT artist as artist, title as song, label as label, date_played as date from automation.historylist ORDER BY date DESC limit $limit) order by date desc limit $limit";
		//$query = "SELECT artist as artist, song as song, label as label, datetime as date FROM arcums.playlist UNION SELECT artist as artist, title as song, label as label, date_played as date from automation.historylist ";
		//$query = "SELECT artist as artist, song as song, label as label, concat(replace(date,'-',''),replace(time,':','')) as date FROM arcums.playlist UNION SELECT artist as artist, title as song, label as label, replace(replace(replace(date_played,'-',''),':',''),' ','') as date from automation.historylist ";
	}

		//$query.= "ORDER BY date DESC LIMIT $limit";

	$result = mysql_query($query) or die("Query failed.");

	while ($row = mysql_fetch_array($result)) {
		echo  $row['song'] . " - ". $row['artist']. "\n";
	}

	mysql_free_result($result);
	mysql_close($link);

?>
