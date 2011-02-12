<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require("../include/version.php"); ?></title>
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
</head>
<body>

<?
session_start();
require("../include/config.php");
require("../include/functions.php");
require("../include/header.php");
require('includes/config.php');
echo '<center>';
if(isset($_SESSION['dj_logged_in']) && $user_info['permissions'] >= 2){
	if(!isset($_GET['add'])){ //show the form?>
	<table  class='catalogtablemain'>
	<tr><td>
			<form method='POST' action='add.php?add=1'>
			<input type='HIDDEN' name='Edit' value=1>
			<b>Title:</b><input type='TEXT' name='Title'  value=''><br>
			<b>Artist:</b><input type='TEXT' name='Artist'  value=''><br>
			<b>Label:</b><input type='TEXT' name='Label'  value=''><br>
			<b>UPC:</b><input type='TEXT' name='UPC'  value=''><br>
			<b>Release:</b><input type='TEXT' size=10 name='Release'  value=''><br>
			<b>Image:</b><input type='TEXT' name='Image'  value=''><br>
			<b>Description</b><input type='TEXT' name='Description'  value=''><br>
			<b>Comments:</b><input type='TEXT' name='Comments'  value=''><br>
	</td><td>
	<b>Tracks:</b><br>
	<textarea name='Tracks' cols='40' rows='10'></textarea>
	</td></tr><tr><td colspan=2 align='center'>
	Category: <select name='cat'>
	<?
	$res = mysql_query("SELECT DISTINCT(name), id FROM category_lookup ORDER BY name ASC") or die(mysql_error());
	while($row= mysql_fetch_row($res)){
		echo "<option value='$row[1]'>$row[0]</option>";
	}//end while
	?>
	</select>
	<input type='hidden' name='submit_add' value='1'>
	<input type='hidden' name='UPC' value=''>
	Vinyl?<select name='isVinyl'><option value='0'>No</option><option value='1'>Yes</option></select> 
	Alphabetical Character: <input type='text' size='1' maxlength='1' name='char'><br>
	<input type='submit' value='Add'>
	</form>
	</tr></td></table></form>
	
	<?}//end !isset($add)
	else{ //Do the adding!
		$title = mysql_real_escape_string($_POST['Title']);
		$artist = mysql_real_escape_string($_POST['Artist']);
		$label = mysql_real_escape_string($_POST['Label']);
		$upc = mysql_real_escape_string($_POST['UPC']);
		$release = mysql_real_escape_string($_POST['Release']);
		$image = mysql_real_escape_string($_POST['Image']);
		$description = mysql_real_escape_string($_POST['description']);
		$comments = mysql_real_escape_string($_POST['Comments']);
		$cat = mysql_real_escape_string($_POST['cat']);
		$cat_letter = mysql_real_escape_string($_POST['char']);
		$vinyl = mysql_real_escape_string($_POST['isVinyl']);

		if($vinyl != 1){
			$res= mysql_query("SELECT MAX(Category_Number) FROM albums WHERE Category_ID='" . $cat . "'")or die(mysql_error());
			$cat_letter='';
		}
		else{
			$res= mysql_query("SELECT MAX(Category_Number) FROM albums WHERE Category_ID='" . $cat . "' AND vinyl_letter='" . $cat_letter . "'")or die(mysql_error());
		}
		$row = mysql_fetch_row($res);
		$cat_num=$row[0];				
		$cat_num++;
		
		$query="INSERT INTO albums VALUES('','" . $cat . "','" .  $cat_num . "','". $cat_letter . "','" .  $title . "','" .  $artist . "','" .  $label . "','" .  $upc . "','" .  $release . "','" .  $discs . "','" .  $image ."','" .  $desc . "','',NOW(),'" . $vinyl . "','" . $user_info['username'] . "[MANUAL_ADD];','')";
		mysql_query($query) or die ("Error Adding Album");
	echo $query;

//get album id
$query ="SELECT ID FROM albums WHERE Category_ID='" . $cat . "' AND Category_Number='" . $cat_num . "' AND vinyl_letter LIKE '" . $cat_letter . "'";
echo $query;
$result = mysql_query($query)or die(mysql_error());
$row = mysql_fetch_row($result);
print_r($row);
$album_id = $row[0];
echo $album_id;

		//mysql_query($query) or die(mysql_error());
		//add tracks
		$discs = split(";",$_POST['Tracks']);
			$disc_pos=1;
			$track_pos=1;
		if(sizeof($discs)>0){
			foreach($discs as $disc){
				$disc_number = $disc_pos;
				$tracks = split(",",$disc);	
				foreach($tracks as $track){
					$lb = strrpos($track,"[");
					if($lb){
						$rb = strrpos("]",$track);
						$title= substr($track,0,$lb);
						$len = substr($track,$lb+1,$rb-1);				
					}
					else{
						$title = $track;
						$len = "00:00";
					}
					if($title!=""){
						$track_query="INSERT INTO tracks(album_id, position, title, cd_number, length) VALUES('" . $album_id ."','" .$track_pos. "','" . $title . "','" . $disc_pos . "','" . $len . "')";
						echo $track_query;
						mysql_query($track_query) or die(mysql_error());

						$track_pos++;
					}
				}
				$disc_pos++;
			}
		}
echo '<meta HTTP-EQUIV="REFRESH" content="0; url=view.php?id=' . $album_id . '">';
	}//end else

echo '</center>';
}//end if logged it
else{
	echo '<meta HTTP-EQUIV="REFRESH" content="0; url=../login">';
}

?>
