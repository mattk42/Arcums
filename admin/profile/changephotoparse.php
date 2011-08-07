<?php 
session_start(); 
header("Cache-control: private"); 
if (!$_SESSION['username']) { 
    echo "You're not logged in!"; 
    include("login.php"); 
    exit(); 
} 
$username = $_SESSION['username']; 
include("../include/config.php"); 
$maxfilesize = 81920; 
// check if there was a file uploaded 
if (!is_uploaded_file($_FILES['userphoto']['tmp_name'])) { 
    $error = "you didn't select a file to upload or the file size was over 80kb.<br />"; 
// if it was, go ahead with other checks 
} else { 
    if ($_FILES['userphoto']['size'] > $maxfilesize) { 
        $error = "your image file was too large.<br />"; 
        unlink($_FILES['userphoto']['tmp_name']); 
    } else { 
        $ext = strrchr($_FILES['userphoto']['name'], "."); 
        if ($ext != ".gif" AND $ext != ".jpg" AND $ext != ".jpeg" AND $ext != ".bmp" AND $ext != ".GIF" AND $ext != ".JPG" AND $ext != ".JPEG" AND $ext != ".BMP") { 
            $error = "your file was an unacceptable type.<br />"; 
            unlink($_FILES['userphoto']['tmp_name']); 
        // if it's there, an okay size and type, copy to server and update the photo value in SQL 
        } else { 
            if ($_SESSION['photo'] != "nonpic.jpg") { 
                unlink("photos/".$_SESSION['photo']); 
            } 
            $newname = $_SESSION['username'].$ext; 
            move_uploaded_file($_FILES['userphoto']['tmp_name'],"photos/".$newname); 
            mysql_query("UPDATE djs SET photo='$newname' WHERE username='$username'") or die (mysql_error()); 
            $_SESSION['photo'] = $newname; 
        } 
    } 
} 
?> 
<html><head><style>
body
{
background: #131313;
background-image: url('../images/bg.jpg');
font-family: Verdana, Arial;
font-weight: bold;
font-size: 9px;
color: #FFFFFF;
}
.register_box
{
border: 1px solid #323232;
background: #202020;
font-family: Verdana, Arial;
font-weight: bold;
font-size: 9px;
color: #FFFFFF;
}
a:link
{
color: c0c0c0;
}
a:visited
{
color: c0c0c0;
}
a:hover
{
color: 00ff00;
}
</style><title>Change Photo Result</title></head><body> 
<h1>Change Photo Result</h1> 
<?php 
if ($error) { 
    echo "Your photo could not be changed because ".$error.""; 
} else { 
    echo "Your photo was successfully uploaded."; 
} 
?></body></html> 