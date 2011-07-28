<?php
	include("../header.php");
	require_once("../config.php");	
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
	
	//GET variables to determine what page we are on and how many posts to show.
	if(!isset($_GET['start'])){
		$start = 0;
	}
	else{
		$start = mysql_real_escape_string($_GET['start']);
	}
	if(!isset($_GET['limit'])){
		$limit=$blog_display_limit;
	}
	else{
		$limit = mysql_real_escape_string($_GET['limit']);
	}
	
	if(isset($_GET['post'])){
		$post_number =  mysql_real_escape_string($_GET['post']);
	}

	//set limits for the next and prev buttons
	$next = $start+$limit;
	$prev = $start-$limit;
	if($prev < 0){
		$prev = 0;
	}


	function display_post($row,$comments){
		global $allowable_post, $root;
		//get the name of the dj that posted the post
                 $dj_query=mysql_query("select name,djname from accounts where id =" . $row['dj_id']);
                 $dj_row = mysql_fetch_array($dj_query);

                //display post
                echo "<b><font size=\"5\">" . $row['title'] . "</b></font><br>Posted by <a href=\"$root/admin/profile/view_profile.php?member_id=" . $row['dj_id']. "\">". $dj_row['djname']. " </a>  at "  . $row['post_time'] . "<br><br>" . stripslashes(strip_tags($row['text'],$allowable_post));
		
		if($comments){
                	echo "<hr width=\"100%\" size=5 color=\"#183e6f\">";
			$comments_query=mysql_query("Select * from blog_comments where valid='1' and hide='0' and post_id =" . $row['id']);
			while($comment_row = mysql_fetch_array($comments_query)){
				//handles the reporting of comments, emails if first report, and incriments the "reported" column
				if($report_id == $comment_row['id']){
					if($comment_row['reported']==0){
               				mail($admin_email,"Comment Reported","The following comment has been reported:\nTitle: " . $comment_row['title'] . "\nName: ". $comment_row['name']."\nEmail: ". $comment_row['email']."\nComment: ". $comment_row['text']."\n\nPlease look into this comment.") or die("Failed to send email.");
					}
					mysql_query("UPDATE blog_comments SET reported=reported+1 where id='".$comment_row['id']."'") or die(mysql_error());
					echo "<br><font color=\"red\">The Following Comment has been reported</font>";

		        	}
				echo "<br><b><font size=\"2\">" . $comment_row['title'] . "</font></b><font size=\"1\"> <a href =\"?post=" . $post_number . "&report=". $comment_row['id'] ."\">Report</a>"  ."</font><br>By: " . $comment_row['name']  . "<br>" . strip_tags($comment_row['text'],$allowable_comment) . "<br>";
			}
		}
		else{
			$comment_count_query=mysql_query("Select * from blog_comments where valid='1' and hide ='0' and post_id =" . $row['id']) or die(mysql_error());
			$comment_count = mysql_num_rows($comment_count_query);
			echo "<br><br><a href=\"?post=" . $row['id']. "\">Comments(" . $comment_count . ")</a><br><br>";
                        echo "<hr width=\"100%\" size=5 color=\"#183e6f\">";		
		}	
	}
	
	//get a count of the posts, for calculating prev and next buttons
	$query=mysql_query("SELECT * from blog_posts WHERE type='" . $type ."' ORDER BY post_time") or die("Post Query Failed");
	$count=mysql_num_rows($query);
	
	if(isset($_GET['report'])){
		$report_id = mysql_real_escape_string($_GET['report']);
	}
	if(empty($post_number)){
		$query=mysql_query("SELECT * from blog_posts WHERE hide='0' and type='" . $type ."' ORDER BY post_time DESC LIMIT ". $start .",". $limit  ) or die(mysql_error());
		while($row = mysql_fetch_array($query)){
			echo "<br><br>";
			display_post($row,False);	
		}//end while
			if($start>0){
				echo "<center><a href=\"?type=".$type."&start=". $prev . "\"><-Previous </a></center>";
			}
			if($count > $next){
				echo "<center><a href=\"?type=".$type."&start=". $next . "\">Next-></a></center>";
			}
	}//end if
       else{
                $query=mysql_query("Select * from blog_posts where id=" . $post_number) or die(mysql_error());
                $row = mysql_fetch_array($query);
		display_post($row,True);	

        }//end else
	include("../footer.php");
