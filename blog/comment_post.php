<?php

function generateCode($length = 10)
{
	$password="";
	$chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
	srand((double)microtime()*1000000);
	for ($i=0; $i<$length; $i++)
{
	$password = $password . substr ($chars, rand() % strlen($chars), 1);
}
	return $password;
}

function check_email_address($email) {
  // First, we check that there's one @ symbol, 
  // and that the lengths are right.
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
    // Email invalid because wrong number of characters 
    // in one section or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if
(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
$local_array[$i])) {
      return false;
    }
  }
  // Check if domain is IP. If not, 
  // it should be valid domain name
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if
(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
↪([A-Za-z0-9]+))$",
$domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}

include("../header.php");
include("config.php");

        $vcode = addslashes($_GET['code']);
        $cid = addslashes($_GET['id']);	
	
	if ($vcode!="" && $cid!=""){
		$c_query = mysql_query("SELECT validation_code, valid FROM comments where id='" . $cid . "'");
		$c_row = mysql_fetch_array($c_query);
		
		if($c_row['validation_code'] == $vcode && $c_row['valid']==0){
			mysql_query("UPDATE comments SET valid='1' WHERE id='" . $cid . "'") or die(mysql_error());
			echo "Comment Validated";
		}
		else if($c_row['valid']==1){
			echo "Comment already validated!";
		}
		else{
			echo "Validation Failed!";
		}
	}
	else{
		if($_POST['name']==""){
		        echo "You must supply your name in order to submit a comment!";
		}
		if($_POST['email']=="" || !check_email_address($_POST['email'])){
		        echo "A valid email address is required to submit a comment!";
		}
		else if($_POST['comments']==""){
		        echo "You supplied no comments!";
		}
		else{   
			if($_POST['title']==""){   
			        $_POST['title'] = "General Comment";
			}

		        $title = addslashes($_POST['title']);
		        $email = addslashes($_POST['email']);
		        $name = addslashes($_POST['name']);
		        $comments = addslashes($_POST['comments']);

		        $valid_code = generateCode(25);

		        $post=addslashes($_POST['post']);

		        mysql_query("INSERT INTO comments (post_id, name, email, validation_code , valid ,title, text, hide) VALUES ('" . $post . "','" . $name . "','" . $email . "','" . $valid_code . "', '0' , '" .  $title . "','" .  $comments . "', '0')")
			or die(mysql_error());
			echo "An email has been sent to " . $email . " for verifcation.<br>";
			echo "<a href=\"http://www.wupx.com/blog/view.php?post=" . $post . "\">Return to Post</a>";

			$results = mysql_query("SELECT id FROM comments WHERE valid='0' AND validation_code='" . $valid_code . "' AND text='". $comments ."'");
			$row = mysql_fetch_array($results);

			mail($email, "[WUPX] Comment Verification","
	The following comment has been posted using your e-mail address:
	Here are the comment details:
	Name: ".$name."
	Title: ".$title."
	Comment: ".$comments."

	If this comment was made by you, follow this link to activate it:
	http://www.wupx.com/blog/comment_post.php?code=".$valid_code."&id=".$row['id']."

	If there is an issue with the comment system or someone is trying to post using your email, you can reach me at itdirector@wupx.com
	");
		
		}
	}





include("../footer.php");

?>
