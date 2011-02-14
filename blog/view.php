<?php
	include("../header.php");
	
	//used when displaying a specific post
	$post_number =  mysql_real_escape_string($_GET['post']);
	//0 = E-Staff, 1 = DJ
	$type =  mysql_real_escape_string($_GET['type']);
	//Number of posts to display
	$limit = mysql_real_escape_string($_GET['limit']);
	//which post to start on
	$start = addslashes($_GET['start']);

	//tags that are allowed to be displayed (used with strip_tags)
	$allowable_post="<b><a><br><br /><strong><sup><sub><p><blockquote><font><img><ol><li><ul><code><cite><em><h1><h2><h3><h4><h5><h6><address><span>";
	$allowable_comment="<a>";	

	//cleanse all of the $_GET variables
	if(!isset($type)){
		$type = 1;
	}
	else{
		$type = mysql_real_escape_string($type);
	}
	if($start==""){
		$start = 0;
	}
	if(!isset($limit)){
		$limit=5;
	}
	else{
		$limit = mysql_real_escape_string($limit);
	}
	if(isset($post_number)){
		$post_number =  mysql_real_escape_string($post_number);
	}

	//set limits for the next and prev buttons
	$next = $start+$limit;
	$prev = $start-$limit;
	if($prev < 0){
		$prev = 0;
	}
	
	//get a count of the posts, for calculating prev and next buttons
	$query=mysql_query("SELECT * from blog_posts WHERE type='" . $type ."' ORDER BY post_time") or die("Post Query Failed");
	$count=mysql_num_rows($query);

	$report_id = addslashes($_GET['report']);

	if(!isset($post_number)){
		$query=mysql_query("SELECT * from blog_posts WHERE hide='0' and type='" . $type ."' ORDER BY post_time DESC LIMIT ". $start .",". $limit  );
		while($row = mysql_fetch_array($query)){
			echo "<br><br>";
	
			//get the name of the dj that posted the post
			$dj_query=mysql_query("select name,djname from accounts where id =" . $row['dj_id']);
			$dj_row = mysql_fetch_array($dj_query);
		
			//get a count of the comments
			$comment_count_query=mysql_query("Select * from blog_comments where valid='1' and hide ='0' and post_id =" . $row['id']);
			$comment_count = mysql_num_rows($comment_count_query);				
			
			//display post
			echo "<font size=\"5\"><b>" . stripslashes($row['title']) . "</b></font><br><b>DJ:</b> <a href=\"../arcums/profile/view_profile.php?member_id=" . stripslashes($row['dj_id']). "\">". stripslashes($dj_row['djname']) . "</a>(" . stripslashes($dj_row['name']) . ")" . " | <b>Time:</b> "  . stripslashes($row['post_time']) . "<br><br>" . stripslashes(strip_tags($row['text'],$allowable_post));
			echo "<br><br><a href=\"?post=" . $row['id']. "\">Comments(" . $comment_count . ")</a><br><br>";
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
                $query=mysql_query("Select * from blog_posts where id=" . $post_number);
                $row = mysql_fetch_array($query);
                       
		 //get the name of the dj that posted the post
                 $dj_query=mysql_query("select name,djname from accounts where id =" . $row['dj_id']);
                 $dj_row = mysql_fetch_array($dj_query);

                 //get the comments
                 $comments_query=mysql_query("Select * from blog_comments where valid='1' and hide='0' and post_id =" . $row['id']);

		//display post
		echo "<b><font size=\"5\">" . $row['title'] . "</b></font><br><b>DJ:</b> <a href=\"../arcums/profile/view_profile.php?member_id=" . $row['dj_id']. "\">". $dj_row['djname']. " </a>(" . $dj_row['name'] . ")" . " | <b>Time:</b> "  . $row['post_time'] . "<br><br>" . stripslashes(strip_tags($row['text'],$allowable_post));
		echo "<hr width=\"100%\" size=10 color=\"#43727B\">";			

		//dispay comments
		echo "";
	        while($comment_row = mysql_fetch_array($comments_query)){
			
			//handles the reporting of comments, emails if first report, and incriments the "reported" column
			if($report_id == $comment_row['id']){
				if($comment_row['reported']==0){
               			mail("itdirector@wupx.com","Comment Reported","The following comment has been reported:\nTitle: " . $comment_row['title'] . "\nName: ". $comment_row['name']."\nEmail: ". $comment_row['email']."\nComment: ". $comment_row['text']."\n\nPlease look into this comment.") or die("Failed to send email.");
				}
				mysql_query("UPDATE comments SET reported=reported+1 where id='".$comment_row['id']."'");
				echo "<br><font color=\"red\">The Following Comment has been reported</font>";

		        }

			echo "<br><b><font size=\"2\">" . $comment_row['title'] . "</font></b><font size=\"1\"> <a href =\"?post=" . $post_number . "&report=". $comment_row['id'] ."\">Report</a>"  ."</font><br>By: " . $comment_row['name']  . "<br>" . strip_tags($comment_row['text'],$allowable_comment) . "<br>";
		}?>

		<!-- comment form --!>
		<br><br>Submit Comments:<br>
		<form action="comment_post.php" method="post">
		<b>Your Name:</b> <input type="text" name="name" /><br />
		<b>E-mail:</b><input type="text" name="email" />
		<br>
		<b>Comment Title:</b><input type="text" name="title" /><br />
		<b>Your comments:</b><br />
		<textarea name="comments" rows="10" cols="40"></textarea>
		<br>
                <input type="hidden" value="<?php echo $post_number ?>" name="post">
		<input type="submit" value="Submit">
		</form>

<?php

        }//end 
	include("../footer.php");
?>
