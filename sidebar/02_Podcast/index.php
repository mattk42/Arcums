<?php
$podcast = simplexml_load_file($root.'/rss/podcast.xml');
$item = $podcast->channel->item;

echo "<ul style=\"list-style: none; margin: 5px; padding: 0;\">\n";
for ($i = 0; $i < 4; $i++) {
	// format bytes as megabytes
	$item[$i]->enclosure['length'] /= pow(2, 20);
	$length = strpos($item[$i]->enclosure['length'], ".");
	$item[$i]->enclosure['length'] = substr($item[$i]->enclosure['length'], 0, $length+2);
	// re-format date string
	$item[$i]->pubDate = date("F d, Y", strtotime($item[$i]->pubDate)); 
	if($item[$i]->title!=''){
		echo "<li><a href=\"".$root. $item[$i]->enclosure['url']. "\" title=\"". $item[$i]->description. "\">";
		echo $item[$i]->title. "</a> -- <span class=\"tinyText\"><i>";
		echo $item[$i]->pubDate. " -- ". $item[$i]->enclosure['type']. " (". $item[$i]->enclosure['length']. " MB)</i></span></li>";
	}
}
echo "</ul>\n";

?>
