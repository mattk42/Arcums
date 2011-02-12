<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require("../include/version.php"); ?></title>
</head>

<body>

<?php 

session_start();

include("includes/aws_signed_request.php");

require("../include/config.php");
require("../include/functions.php");
if(isset($_SESSION['dj_logged_in'])){
	echo '<link href="../css/arcums.css" rel="stylesheet" type="text/css" />';
	require("../include/header.php");
}
else{
	require("../../header.php");
}
include("includes/config.php");
include('includes/functions.php');
echo "<!--";
require("../playlist/listeners.php");
echo "-->";


$page_limit=20;  //how many search results to show per page
$found = false;  //whether or not similar artists were found

//error_reporting(0);
//get listeners for our own server
$main = getCurrentListeners("wupx.nmu.edu","wupx915","8000");

//get Listeners for remote server
//$secondary = getCurrentListeners("wupx5.nmu.edu","r4d10x","8000");

$listeners = $main;

if(isset($_GET['play'])){
	echo "<center>Played</center>";
	addSong($_GET['artist'], $_GET['song'], $_GET['section'], $_GET['sectionnumber'], $_GET['album'], $_GET['label'], $_GET['tracknumber']);
}	
if(isset($_GET['sent'])){
	echo "<center>Report Sent</center>";
}	

if(isset($_GET['id'])){
	$id = mysql_real_escape_String($_GET['id']);
	$result = mysql_query("SELECT * FROM albums WHERE id='" . $id . "'" );

	$row = mysql_fetch_array($result);

	//Update cache data if out of date
	if($row[16] < date('Y-m-d',strtotime('-1 month'))){
		passthru("/usr/bin/php5 /var/www/arcums/catalog/get_similar.php '".$row[5]."' >> /var/www/arcums/catalog/similar.log 2>&1 &");
	}

	if($row[10]=="" && $row[7]!=""){
		$row[10] = findImage($row[7]);
	}
	if($row[10]=="NONE" || $row[10]==""){
		if($row[14]==1){
			$row[10] = '../images/vinyl.png';
		}
		else{
			$row[10] = '../images/CD.PNG';
		}
	}		

	
	echo "<center><table class='catalogtablemain'><tr><td align='center'>";	
	echo '<img width=/"250/" src="' . $row[10] . '"></img><br>';
	if(isset($_SESSION['dj_logged_in'])){
		echo "<br><a href=report.php?id=" . $id . "><img alt='Report' width='20' src='../images/uhoh.png'></a> ";	
	}	
	if(isset($_SESSION['dj_logged_in']) && $user_info['permissions'] >= 2){
		echo " <a href=edit.php?id=" . $id . "><img alt='Edit' width='20' src='../images/edit-icon.png'></a>";
		echo " <a href=remove.php?id=" . $id . "><img alt='Delete' width='20' src='../images/delete-icon.png'></a>";
		echo "<br>";
	}
	echo '</td><td>';	
	echo "<b>Artist: </b>" . $row['artist'] . "<br>";
	echo "<b>Title: </b>" . $row['title'] . "<br>";
	echo "<b>Label: </b>" . $row['label'] . "<br>";
	echo "<b>Release Date: </b>" . $row['release'] . "<br>";

	$cat_query = mysql_query("SELECT prefix FROM category_lookup WHERE id='" . $row[1] . "'") or die(mysql_error());
	$cat_result = mysql_fetch_row($cat_query);

	if($row[14]==1){
		$v = " V-";
	}
	else{
		$v = "";
	}
        echo "<b>Category</b> $cat_result[0] $v$row[3] $row[2]<br>"; 

	$section = $cat_result[0];
	if($section == ""){$section="GENERAL";}	
	$track_result = mysql_query("SELECT * FROM tracks WHERE album_id='" . $id . "' ORDER BY cd_number, position" );
	$cd = '0';
	if(!isset($_SESSION['dj_logged_in'])){
		echo "Click the play arrow to request a song.<br>";
	}
	while($track_row = mysql_fetch_row($track_result)){
		if($cd != $track_row[4]){
			$cd = $track_row[4];
			echo "<br><u><b>Disc " . $cd ."</b></u><br>";
		}
		echo $track_row[2] . ".) ";
		echo $track_row[3];
		if($track_row[5] != "00:00"){
			echo " [" . $track_row[5] . "]";
		}
		//echo " <a href='?id=".$id."&play=1&artist=".$row[5]."&song=".$track_row[3]."&section=".$cat_result[0]."&sectionnumber=".$row[2]."&album=".$row[4]."&label=".$row[6]."&tracknumber=".$track_row[2]."'>" . ">" . "</a>";

		//hidden form for submiting to playlist
		if(isset($_SESSION['dj_logged_in'])){	
			echo "<form action='../playlist/addparse.php' method='post' name='addsong'>";
		}
		else{
			echo "<form class='headers' action='reqpost.php' method='post' name='addsong'>";
		}
		echo "<input type=\"hidden\" name=\"date\" value=\"";print date("Y-m-d");echo"\" />";
		echo "<input type=\"hidden\" name=\"time\" value=\"";print date("H:i:s");echo"\" />";
		echo "<input type=\"hidden\" name=\"datetime\" value=\"";print date("YmdHis");echo"\" />";
		echo "<input type=\"hidden\" name=\"albumid\" value=\"$row[0]\">";
		echo "<input type=\"hidden\" name=\"artist\" value=\"$row[5]\">";
		echo "<input type=\"hidden\" name=\"song\" value=\"$track_row[3]\">";
		echo "<input type=\"hidden\" name=\"section\" value=\"$section\">";
		echo "<input type=\"hidden\" name=\"sectionnumber\" value=\"$row[2]\">";
		echo "<input type=\"hidden\" name=\"album\" value=\"$row[4]\">";
		echo "<input type=\"hidden\" name=\"label\" value=\"$row[6]\">";
		echo "<input type=\"hidden\" name=\"tracknumber\" value=\"$track_row[2]\">";		
		echo "<input type=\"hidden\" name=\"trackid\" value=\"$track_row[0]\">";
		echo "<input type=\"hidden\" name=\"listeners\" value=\"$listeners\"";	
		echo "<input type=\"hidden\" name=\"requested\" value=\"0\">";			
		echo "<input type=\"image\" class=\"smallimg\" width=12 src=\"../images/play.png\">";

		echo "</form>";



		echo "<br>";
	}

/*
	$similar_xml = get_similar($row[5], 10);
	echo "</td></tr><tr class='headers' align='center'><td align='center' colspan='2'><br><b>Similar Artists</b><br>";
	if($similar_xml){
		foreach ($similar_xml->similarartists->artist as $sartist){
			$art = $sartist->name;

			$query = mysql_query("SELECT count(*)  FROM albums WHERE artist='" . mysql_real_escape_string($art) . "'");
			$count = mysql_fetch_row($query);
			if($count[0]>0){
				$found = true;
				echo " <a href=index.php?artist=" . urlencode($art) . ">" . $art . "</a> ";
			}
		}
		if(!$found){
			echo "No similar artists in our collection.";
		}

	}
	else{
		echo "No similar artists found";
	}

	echo "</td></tr></table></center><br>";
*/

        echo "</td></tr><tr class='headers' align='center'><td align='center' colspan='2'><br><b>Similar Artists</b><br>";
	$sim_query = "select sim_artist from similar where artist='$row[5]' and sim_artist in (select distinct(artist) from albums)";
	$res = mysql_query($sim_query) or die("No Similar Artists");
	if(mysql_num_rows($res)>0){
		while($row = mysql_fetch_array($res)){
			echo " <a href=index.php?artist=" . urlencode($row[0]) . ">" . $row[0] . "</a> ";
		}
	}
	else{
		echo "No Similar Artists Found";	
	}
	echo "</td></tr></table></center><br>";

}
else{
		echo "<table border='0' width='75%'>";
		echo "<tr>";
		foreach(range('A','Z') as $i)
		{
			echo "<td>";
			echo "<a href=?letter=" . $i . "><b>" . $i . "</b></a>";
			echo "</td>";
		}
		echo "</tr>";
		echo "</table>";

	if(isset($_GET['letter'])){
		$letter = mysql_real_escape_String($_GET['letter']);
		$total_query = mysql_query("SELECT * FROM albums WHERE artist LIKE '" . $letter . "%'" );
		$total = mysql_num_rows($total_query);	

		if(isset($_GET['start'])){
			$start = mysql_real_escape_string($_GET['start']);
		}		
		else{
			$start=0;
		}

		$album_query = mysql_query("SELECT * FROM albums WHERE artist LIKE '" . $letter . "%' ORDER BY artist ASC LIMIT ". $start ."," . $page_limit  );

		echo "<table>";
		while($row = mysql_fetch_row($album_query)){
			echo "<tr>";
			echo "<td><a href=\"?id=" . $row[0] . "\">" . $row[1] . $row[2] ."</a></td><td>". $row[4]."</td><td>". $row[3]. "</td>";
			echo "</tr>";
		}
		echo "</table>";

		$shown = $start+$page_limit;
		$back = $start-$page_limit;
	
		echo "<center>";
		if($start>0){
			echo "<a href=\"?letter=" . $letter . "&start=" . $back. "\"><-Back </a>";
		}
		if($shown<$total){
			echo "<a href=\"?letter=" . $letter . "&start=" . $shown . "\"> Forward-> </a>";
		}
		echo "</center>";
	
		
	}

}
	if(!isset($_SESSION['dj_logged_in'])){
		include("../../footer.php");	
	}
	
?>
