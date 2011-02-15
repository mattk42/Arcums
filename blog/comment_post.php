<?php
include ("../header.php");
include ("../config.php");
include ("../arcums/include/functions.php");
$vcode = mysql_real_escape_string($_GET['code']);
$cid = mysql_real_escape_string($_GET['id']);

if ($vcode != "" && $cid != "") {
    $c_query = mysql_query("SELECT validation_code, valid FROM blog_comments where id='" . $cid . "'");
    $c_row = mysql_fetch_array($c_query);
    
    if ($c_row['validation_code'] == $vcode && $c_row['valid'] == 0) {
        mysql_query("UPDATE blog_comments SET valid='1' WHERE id='" . $cid . "'") or die(mysql_error());
        echo "Comment Validated";
    }
    else 
    if ($c_row['valid'] == 1) {
        echo "Comment already validated!";
    }
    else {
        echo "Validation Failed!";
    }
}
else {
    
    if ($_POST['name'] == "") {
        echo "You must supply your name in order to submit a comment!";
    }
    
    if ($_POST['email'] == "" || !is_valid_email($_POST['email'])) {
        echo "A valid email address is required to submit a comment!";
    }
    else 
    if ($_POST['comments'] == "") {
        echo "You supplied no comments!";
    }
    else {
        
        if ($_POST['title'] == "") {
            $_POST['title'] = "General Comment";
        }
        $title = mysql_real_escape_string($_POST['title']);
        $email = mysql_real_escape_string($_POST['email']);
        $name = mysql_real_escape_string($_POST['name']);
        $comments = mysql_real_escape_string($_POST['comments']);
        $valid_code = generateCode(25);
        $post = mysql_real_escape_string($_POST['post']);
        mysql_query("INSERT INTO blog_comments (post_id, name, email, validation_code , valid ,title, text, hide) VALUES ('" . $post . "','" . $name . "','" . $email . "','" . $valid_code . "', '0' , '" . $title . "','" . $comments . "', '0')") or die(mysql_error());
        echo "An email has been sent to " . $email . " for verifcation.<br>";
        echo "<a href=\"$root/blog/view.php?post=" . $post . "\">Return to Post</a>";
        $results = mysql_query("SELECT id FROM blog_comments WHERE valid='0' AND validation_code='" . $valid_code . "' AND text='" . $comments . "'") or die(mysql_error());
        $row = mysql_fetch_array($results);
        mail($email, "[WUPX] Comment Verification", "
	The following comment has been posted using your e-mail address:
	Here are the comment details:
	Name: " . $name . "
	Title: " . $title . "
	Comment: " . $comments . "

	If this comment was made by you, follow this link to activate it:
	http://$root/blog/comment_post.php?code=" . $valid_code . "&id=" . $row['id'] . "

	If there is an issue with the comment system or someone is trying to post using your email, you can reach me at itdirector@wupx.com
	");
    }
}
include ("../footer.php");
?>
