<?php

	// current.php
	//used for the yahoo widget

	$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS);

	$options = array("options" =>	array("min_range" => 1, "max_range" => 25));
	$limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT, $options);

	$link = mysql_connect("localhost", "arcums", "arcums123") or die("Could not connect to database");

	if ($type == "artist") {
		  $query = "(SELECT artist as artist, datetime as date FROM arcums.playlist ORDER BY date DESC limit 1) UNION (SELECT artist as artist, date_played as date from automation.historylist ORDER BY date DESC limit 1) order by date desc limit 1";
	}
	else if($type == "song"){
		  $query = "(SELECT song as song, datetime as date FROM arcums.playlist ORDER BY date DESC limit 1) UNION (SELECT title as song, date_played as date from automation.historylist ORDER BY date DESC limit 1) order by date desc limit 1";
	}



	$result = mysql_query($query) or die("Query failed.");

	while ($row = mysql_fetch_array($result)) {
		echo $row[0];
	}

	mysql_free_result($result);
	mysql_close($link);

?>
