<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require("../include/version.php"); ?></title>
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
</head>
<body>

<?php
require_once("../..//config.php");
require_once("../include/functions.php");
include("../include/header.php");
echo '<center>';
if(isset($_SESSION['dj_logged_in']) && $user_info['permissions'] >= 2){
	if(isset($_GET['id'])){
		$id = mysql_real_escape_string($_GET['id']);
	}
	else{
		echo "ID not set";
		die();
	}

	if(!isset($_POST['Edit'])){ //show the form
		$query=mysql_query("SELECT * FROM catalog_albums WHERE id='" . $id . "'");

		if(mysql_num_rows($query) == 1){
			$row=mysql_fetch_array($query);
			$track_query = mysql_query("SELECT * FROM catalog_tracks WHERE album_id='" .$id . "' ORDER BY cd_number ASC , position ASC") or die(mysql_error());
			$cat = $row['category_id'];
		?>
	<table  class='catalogtablemain'>
	<tr><td>
			<form method='POST' action='edit.php?id=<? echo $id ?>'>
			<input type='HIDDEN' name='Edit' value=1>
			<input type='HIDDEN' name='history' value='<? echo$row['history']; ?>'>
			<b>Title:</b><input type='TEXT' name='Title'  value='<?echo $row['title']; ?>'><br>
			<b>Artist:</b><input type='TEXT' name='Artist'  value='<?echo $row['artist']; ?>'><br>
			<b>Label:</b><input type='TEXT' name='Label'  value='<?echo $row['label']; ?>'><br>
			<b>UPC:</b><input type='TEXT' name='UPC'  value='<?echo $row['upc']; ?>'><br>
			<b>Release:</b><input type='TEXT' size=10 name='Release' ReadOnly  value='<? echo $row['release']; ?>'><br>
			<b>Discs:</b><select name="Discs">
				<option value='1' <? if($row['discs']==1){echo "SELECTED";} ?>>1</option>
				<option value='2' <? if($row['discs']==2){echo "SELECTED";} ?>>2</option>
				<option value='3' <? if($row['discs']==3){echo "SELECTED";} ?>>3</option>
				<option value='4' <? if($row['discs']==4){echo "SELECTED";} ?>>4</option>
				<option value='5' <? if($row['discs']==5){echo "SELECTED";} ?>>5</option>
			</select><br>
			<b>Image:</b><input type='TEXT' name='Image'  value='<?echo $row['image']; ?> '><br>
			<b>Category:</b><select name='cat'>
			<?
				$selected = "";
				$res = mysql_query("SELECT DISTINCT(name), id FROM catalog_categories ORDER BY name ASC") or die(mysql_error());
				while($r= mysql_fetch_row($res)){
					echo $cat;
					if($cat == $r[1]){
						$selected = "SELECTED";
					}	
					else{
						$selected="";
					}
					echo "<option value='$r[1]' $selected>$r[0]</option>";
				}//end while
			?>				
	</select><br>			
			<b>Category Number: </b><input type='TEXT' name='cat_number' value='<?echo $row['category_number']; ?>'?<br>
			<!--<b>Description</b><input type='TEXT' name='Description'  value='<? echo $row[11]; ?>'><br>-->
			<!--<b>Comments:</b><input type='TEXT' name='Comments'  value='<? echo $row[12]; ?>'><br>-->
	</td><td>
	<b>Tracks:</b><br>
	<?
	echo "<textarea name='Tracks' cols='40' rows='10'>";
	$num=0;
	$cd_num=1;
	while($track_row = mysql_fetch_array($track_query)){
		if($num!=0){
			echo ",";
		}
		if($cd_num != $track_row['cd_number']){
			echo ";";
			$cd_bum=$track_row[4];
		}	
		echo $track_row['title'] . "[" . $track_row['length'] . "]";
		$num++;

	}	
	echo "</textarea>";
	if($row['vinyl']==1){
		$sel = "SELECTED";
	}
	else{
		$sel = "";
	}
	?>
	</td></tr><tr><td colspan=2 align='center'>
	Vinyl?<select name="vinyl">
		<option value=0>No</option>
		<option value=1 <? echo $sel ?>>Yes</option>
	</select>
	Alphabetical Character: <input type='text' size='1' maxlength='1' name='char' value="<? echo $row['vinyl_letter'] ?>"><br>
			<input type='Submit' value='Update'>
	</tr></td></table></form>
		<?	
		}
		else{
			echo "Invalid ID";
			die();
		}
	}
	else{ //Do the updating!
		$a_title = trim(mysql_real_escape_string($_POST['Title']));
		$history = trim(mysql_real_escape_string($_POST['history']));
		$history = $history . mysql_real_escape_string($user_info['username']."[EDIT];");
		$artist = trim(mysql_real_escape_string($_POST['Artist']));
		$label = trim(mysql_real_escape_string($_POST['Label']));
		$upc = trim(mysql_real_escape_string($_POST['UPC']));
		$release = trim(mysql_real_escape_string($_POST['Release']));
		$disc_count = trim(mysql_real_escape_string($_POST['Discs']));
		$image =  trim(mysql_real_escape_string($_POST['Image']));
		$description = trim(mysql_real_escape_string($_POST['Description']));
		$comments = trim(mysql_real_escape_string($_POST['Comments']));
		$vinyl = trim(mysql_real_escape_string($_POST['vinyl']));
		$char = trim(mysql_real_escape_string($_POST['char']));
		$catn = trim(mysql_real_escape_string($_POST['cat_number']));
		$cat = mysql_real_escape_string($_POST['cat']);

	//echo "DELETE FROM tracks WHERE album_id='".$id."'";
	mysql_query("DELETE FROM catalog_tracks WHERE album_id='".$id."'");
	$discs = explode(";",$_POST['Tracks']);
	$disc_pos=1;
	$track_pos=1;
	foreach($discs as $disc){
		$disc_number = $disc_pos;
		$tracks = explode(",",$disc);	
		foreach($tracks as $track){
			$lb = strrpos($track,"[");
			//echo "<br>" . $lb . "<br>";
			if($lb>0){
				$rb = strrpos($track,"]");
				//echo($rb);
				$title= substr($track,0,$lb);
				//echo $title;
				$len_size = $rb - $lb - 1;
				$len = substr($track,$lb+1,$len_size);	
				//echo " " . $len . "<br>";			
			}
			else{
				$title = $track;
				$len = "00:00";
			}
			//echo 	"INSERT INTO tracks(album_id, position, title, cd_number, length) VALUES('" . $id ."','" .$track_pos. "','" . $title . "','" . $disc_pos . "','" . $len . "')";
	
			mysql_query("INSERT INTO catalog_tracks(album_id, position, title, cd_number, length) VALUES('" . $id ."','" .$track_pos. "','" . $title . "','" . $disc_pos . "','" . $len . "')") or die(mysql_error());
			$track_pos++;
		}
		$disc_pos++;
	}


	echo "<br><br>";
	mysql_query("UPDATE catalog_albums SET Title=\"" . $a_title . "\",Artist=\"" . $artist . "\",category_id=\"" . $cat . "\",category_number=\"" . $catn . "\",Label=\"" . $label . "\" ,UPC=\"" . $upc . "\",Discs=\"" . $disc_count . "\",Image=\"" . $image . "\",Description=\"" . $description . "\",Comments=\"" . $comments . "\",Vinyl=\"" . $vinyl . "\", vinyl_letter=\"" . $char . "\",History=\"" . $history . "\" WHERE id=\"" . $id . "\"") or die(mysql_error());
echo '<meta HTTP-EQUIV="REFRESH" content="0; url=view.php?id=' . $id . '">';

	}

	echo '</center>';
}
else{
	echo '<meta HTTP-EQUIV="REFRESH" content="0; url=../login">';
}

?>
