<?php 

include('config.php');


	$query = "SELECT banner FROM accounts WHERE banner != ''";

	$result = mysql_query($query) or die("Error selecting banner.");

	$banners = array();

	while ($row = mysql_fetch_assoc($result)) {
		$banners[] = $row["banner"];
	}

	$banner = (sizeof($banners) > 0) ? randomImage($banners) : "";
	
	if ($banner!=""){
		echo '<img width="425" height="75" src="'.$root.'/arcums/profile/banners/'.$banner.'" alt="DJ Show Banner" class="imgBorder" />';
	}


function randomImage($array) {
	$total = sizeof($array);
	$random = rand(0, $total-1);
	return $array[$random];
}

?>
