<?php 
include('config.php');
$banner = getRandomBanner();
if ($banner != "") {
?>
  <img width="425" height="75" src="<?php echo $root?>/arcums/profile/banners/<?php echo getRandomBanner(); ?>" alt="DJ Show Banner" class="imgBorder" />
<?php
}

function getRandomBanner() {

	$query = "SELECT banner FROM djs WHERE banner != ''";

	$result = mysql_query($query) or die("Error selecting banner.");

	$banners = array();

	while ($row = mysql_fetch_assoc($result)) {
		$banners[] = $row["banner"];
	}

	mysql_free_result($result);

	mysql_close($link);

	return (sizeof($banners) > 0) ? randomImage($banners) : "";
}

function randomImage($array) {
	$total = sizeof($array);
	$random = rand(0, $total-1);
	return $array[$random];
}

?>
