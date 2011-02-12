<?php
session_start();
require("../../config.php");
require("../include/functions.php");
require("../include/header.php");
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
if(isset($_SESSION['dj_logged_in'])){
	echo "<center><table width='55%' ><TR><TD>";
	if(isset($_GET['EDIT'])){
		$postid = mysql_real_escape_string($_GET['EDIT']);	
		$row_query = mysql_query("SELECT * FROM posts WHERE id='".$postid . "'");
		$row = mysql_fetch_array($row_query); 
		if($user_info['permissions'] > 1 || $user_info['id'] == $row['dj_id'] ){
			echo("
			<script type=\"text/javascript\" src=\"/ckeditor/ckeditor.js\"></script>
			<form action=\"post.php?EDIT_POST=1\" method=\"post\">
			<br>

			<b>Blog:</b> 
			<select name=\"type\">
			<option value=\"0\">DJ</option>");
		if($user_info['permissions'] > 1){
			echo("
				<option value=\"1\">E-Staff</option>
			");
		}
		echo("	
			</select>
			<br>
			<b>Title:</b><input type=\"text\" size=\"100\"name=\"title\" value ='" . $row['title'] . "'><br />
			<textarea name=\"post_text\" rows=\"10\" cols=\"40\">". stripslashes($row['text']) ."</textarea>

			<script type=\"text/javascript\">
			CKEDITOR.replace( 'post_text',
			    {
				toolbar : 'MyToolbar',
				uiColor : '#003158'
			    });
			</script>

			<br>
		        <input type=\"hidden\" value=\"" . $row['id'] . "\" name=\"id\">
			<center><input type=\"submit\" value=\"Update\"></center>
			</form>
			<br><br><br>
		");	
		}
		else{
			echo "<font color='red'>You are unauthorized to edit this post</font>";
		}

	}
	else if(isset($_GET['EDIT_POST'])){
		$title = mysql_real_escape_string($_POST['title']);
		$text = mysql_real_escape_string($_POST['post_text']);
		$id = mysql_real_escape_string($_POST['id']);

		$tack = "";
		if(!isset($_POST['type'])){
			$type = 0;
			$tack="AND dj_id='" . $user_info['id'] . "'";
		}
		else{
			$type = mysql_real_escape_string($_POST['type']);
		}
	
		//so that DJs can't post to e-staff blog
		if($user_info['permissions']<1){
			$type=0;
		}

		if($title == ""){
			echo "No Title Supplied!";
		}
		else if($text == ""){
			echo "No Text In Your Post!";
		}
		else if($type == ""){
			echo "No Type Selected!";
		}
		else if($id == ""){
			echo "ID not set!";
		}
		else{
			mysql_query("UPDATE posts SET type='" . $type ."', title='" . $title ."', text='". $text."' WHERE id='" . $id . "'" . $tack)
			or die(mysql_error());

			echo "Your Post has been updated!";
		}
	}
	else if(isset($_GET['DELETE'])){
		$id = mysql_real_escape_string($_GET['DELETE']);

		$tack = "";
		if($user_info['permissions']<=1){
			$set_type=0;
			$tack="AND dj_id='" . $user_info['id'] . "'";
		}
		else{
			$set_type='%';
		}

		if($id == ""){
			echo "ID not set!";
		}	
		else{
			mysql_query("DELETE FROM posts WHERE id='" . $id . "' AND type LIKE '" . $set_type . "'" . $tack)
			or die(mysql_error());

			echo "Post Deleted!";
		}
	}
	else if($_POST['post_text']==""){		
		echo("
			<script type=\"text/javascript\" src=\"/ckeditor/ckeditor.js\"></script>
			<form action=\"post.php\" method=\"post\">
			<b>DJ ID:</b> <input type=\"text\" readonly size=\"4\" name=\"djid\" value=\"" . $user_info['id'] . "\"/>
			<br>

			<b>Blog:</b> 
			<select name=\"type\">
			<option value=\"0\">DJ</option>");
		if($user_info['permissions'] > 1){
			echo("
				<option value=\"1\">E-Staff</option>
			");
		}
		echo("	
			</select>
			<br>
			<b>Title:</b><input type=\"text\" size=\"100\"name=\"title\" /><br />
			<textarea name=\"post_text\" rows=\"10\" cols=\"40\"></textarea>

			<script type=\"text/javascript\">
			CKEDITOR.replace( 'post_text',
			    {
				toolbar : 'MyToolbar',
				uiColor : '#003158'
			    });
			</script>

			<br>
			<center><input type=\"submit\" value=\"Submit\"></center>
			</form>
			<br><br><br>
		");	
	}
	else{
		$dj_id = mysql_real_escape_string($_POST['djid']);
		$title = mysql_real_escape_string($_POST['title']);
		$text = mysql_real_escape_string($_POST['post_text']);

		if(!isset($_POST['type'])){
			$type = 0;
		}
		else{
			$type = mysql_real_escape_string($_POST['type']);
		}
	
		//so that DJs can't post to e-staff blog
		if($user_info['permissions']<=1){
			$type=0;
		}

		if($dj_id == ""){
			echo "Invalid DJ ID";
		}
		else if($title == ""){
			echo "No Title Supplied!";
		}
		else if($text == ""){
			echo "No Text In Your Post!";
		}

		else{
			mysql_query("INSERT INTO blog_posts (dj_id,type,title,text) VALUES ('". $dj_id . "','" . $type . "','". $title . "','" . $text . "')")
			or die(mysql_error());
			echo "Your Post has been posted!";
			
			$idquery = mysql_query("SELECT id FROM blog_posts WHERE dj_id='" . $dj_id . "' and title='" . $title . "'") or die(mysql_error());
			$id = mysql_fetch_array($idquery);

			$djquery = mysql_query("SELECT djname FROM accounts WHERE id=" . $dj_id) or die(mysql_error());
			$djname = mysql_fetch_array($djquery);

		}
	}
	echo "</TD></TR></table>";
}
else{
	echo '<meta HTTP-EQUIV="REFRESH" content="0; url=../login">';
}
	include("../include/footer.php");


?>
