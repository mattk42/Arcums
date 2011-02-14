<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require("../include/version.php"); ?></title>
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
require_once("../../config.php");
require_once("../include/functions.php");
require_once('../include/functions.php');
include("../include/header.php");


	if(isset($_GET['id'])){
		if($user_info['permissions'] >= 2){
			if(isset($_GET['confirm'])){
				$id = mysql_real_escape_string($_GET['id']);
				$track_removal = "DELETE FROM catalog_tracks WHERE album_id='" . $id . "'";
				$album_removal = "DELETE FROM catalog_albums WHERE id='" . $id . "'";

				mysql_query($track_removal) or die(mysql_error());
				mysql_query($album_removal) or die(mysql_error());

				echo "<center>
				<table class='headers'><td align='center'>
				Album Removed<br>
				<a href='index.php'>Go Back</a>
				</tr>";
			}
			else{
				echo "<center>
				<table class='headers'><td align='center'>
				Are you sure you want to remove this album?<br>
				<a href='?id=" . $_GET['id'] . "&confirm=1'>Yes</a>
				<a href='view.php?id=" . $_GET['id'] . "'>No</a> </center></td>"
				;
			}
		}
		else{
			echo "You are unauthorized to remove albums!";
		}

	}
	else{
		echo "<center><table class='headers'><td align='center'>Invalid album ID</td></table></center>";
	}


?>
