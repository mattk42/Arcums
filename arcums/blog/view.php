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
	$session_username = $_SESSION['username'];
		echo "<center><table width='55%' bgcolor='#000000' ><TR><TD><font color='white'>";

		//which post to start on
		$start = mysql_real_escape_string($_GET['start']);

		//tags that are allowed to be displayed (used with strip_tags)
		$allowable_post="<b><a><br><br /><strong><sup><sub><p><blockquote><font><img><ol><li><ul><code><cite><em><h1><h2><h3><h4><h5><h6><address><span>";
		$allowable_comment="<a>";	

		//cleanse all of the $_GET variables
		if(!isset($_GET['type'])){
			$type = 1;
		}
		else{
			$type = mysql_real_escape_string($_GET['type']);
		}
	
		//so that DJs can't edit the estaff blog
		if($user_info['permissions']<1){
			$type=0;
		}

		if($start==""){
			$start = 0;
		}

		if(!isset($_GET['start'])){
			$limit=5;
		}
		else{
			$limit = mysql_real_escape_string($_GET['limit']);
		}

		if(isset($_GET['post'])){
			$post_number =  mysql_real_escape_string($_GET['post']);
		}
		if(isset($_GET['hide'])){
			$hide = addslashes($_GET['hide']);
			$hide_query = "UPDATE comments SET hide='1' WHERE id='".$hide."'";
			mysql_query($hide_query) or die(mysql_error());
			echo "Comment Hidden<br><br>";
		}
		if(isset($_GET['unhide'])){
			$unhide = addslashes($_GET['unhide']);
			$unhide_query = "UPDATE comments SET hide='0' WHERE id='".$unhide."'";
			mysql_query($unhide_query) or die(mysql_error());
			echo "Comment Un-Hidden<br><br>";
		}
		//calculate next and prev values.
		$next = $start+$limit;
		$prev = $start-$limit;
		if($prev < 0){
			$prev = 0;
		}

		//get count of posts for calculating prev and next buttons
		$query=mysql_query("SELECT * from blog_posts WHERE type='" . $type ."' ORDER BY post_time");
		$count=mysql_num_rows($query);

		if(!isset($post_number)){
			$query=mysql_query("SELECT * from blog_posts WHERE type='" . $type ."' ORDER BY post_time DESC LIMIT ". $start . "," .$limit  );
			while($row = mysql_fetch_array($query)){
				echo "<br><br>";
	
				//get the name of the dj that posted the post
				$dj_query=mysql_query("select name,djname from accounts where id =" . $row['dj_id']);
				$dj_row = mysql_fetch_array($dj_query);
		
				//get a count of the comments
				$comment_count_query=mysql_query("Select * from blog_comments where post_id =" . $row['id']);
				$comment_count = mysql_num_rows($comment_count_query);				
			
				//display post
				echo "<font size=\"6\"><b>" . $row['title'] . "</b></font>";
				if($user_info['permissions']>1 || $user_info['id'] == $row['dj_id']){
					echo"<a href='post.php?EDIT=".$row['id']."&type=" . $type . "'>Edit</a>" . " <a href=\"post.php?DELETE=".$row['id']."\">Delete</a>";
				}
				echo"<br><b>DJ:</b> <a href=\"../arcums/profile/view_profile.php?member_id=" . $row['dj_id']. "\">". $dj_row['djname'] . "</a>(" . $dj_row['name'] . ")" . " | <b>Time:</b> "  . $row['post_time'] . "<br><br>" . stripslashes(strip_tags($row['text'],$allowable_post));
				echo "<br><a href=\"?post=" . $row['id'] ."&type=" . $type . "\">Comments(" . $comment_count . ")</a><br><br>";
		                echo "<hr width=\"100%\" size=10 color=\"#43727B\">";
			}//end while
				if($start>0){
					echo "<center><a href=\"?type=".$type."&start=". $prev . "\"><-Previous </a></center>";
				}
				if($count > $next){
					echo "<center><a href=\"?type=".$type."&start=". $next . "\">Next-></a></center>";
				}
		}//end if
	       else{
		        $query=mysql_query("Select * from blog_posts where id='" . $post_number . "' AND type='" . $type . "'");
		        $row = mysql_fetch_array($query);
		               
			 //get the name of the dj that posted the post
		         $dj_query=mysql_query("select name,djname from accounts where id =" . $row['dj_id']);
		         $dj_row = mysql_fetch_array($dj_query);

		         //get the comments
		         $comments_query=mysql_query("Select * from blog_comments where post_id =" . $row['id']);

			//display post
			echo "<b><font size=\"6\">" . $row['title'] . "</b></font>";

			if($user_info['permissions']>1 || $user_info['id'] == $row['dj_id']){
				echo"<a href=\"post.php?EDIT=".$row['id']."\">Edit</a>" . " <a href=\"post.php?DELETE=".$row['id']."\">Delete</a>";
			}
			echo"<br><b>DJ:</b> <a href=\"../arcums/profile/view_profile.php?member_id=" . $row['dj_id']. "\">". $dj_row['djname']. " </a>(" . $dj_row['name'] . ")" . " | <b>Time:</b> "  . $row['post_time'] . "<br>" . stripslashes(strip_tags($row['text'],$allowable_post));
			echo "<hr width=\"100%\" size=10 color=\"#43727B\">";			
			echo"<font color='GREEN'>Good</font>|<font color='BLUE'>Not Yet Validated</font>|<font color='RED'>Hidden</font><br><br>";
			//dispay comments
			echo "";
			while($comment_row = mysql_fetch_array($comments_query)){
				if($comment_row['hide']!=0){
					echo "<font color='RED'>";
					$link = " <a href='?unhide=" . $comment_row['id']. "&post=" . $row['id'] . "&type=" . $type ."'>Unhide</a>";
				}
				else if($comment_row['valid']==0){
					echo "<font color='BLUE'>";
					$link = " <a href='?hide=" . $comment_row['id']."&post=" . $row['id'] . "&type=" . $type . "'>Hide</a>";
				}
				else{
					echo "<font color='GREEN'";
					$link = " <a href='?hide=" . $comment_row['id']."&post=" . $row['id'] . "&type=" . $type ."'>Hide</a>";
				}
				echo "<b><font size=\"3\">" . $comment_row['title'] . "</font></b>" . $link . "<font size=\"1\"> Reported " . $comment_row['reported'] . "times.<br>Name: " . $comment_row['name']  . "<br>Email: <a href=\"mailto:" . $comment_row['email'] . "\">". $comment_row['email'] ."</a><br>" . strip_tags($comment_row['text'],$allowable_comment) . "<br><br></font>";
			}

		}//end else 
		echo "</font></TD></TR></table>";
}
else{
	echo '<meta HTTP-EQUIV="REFRESH" content="0; url=../login">';
}
	include("../include/footer.php");
?>
