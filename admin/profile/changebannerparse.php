<?php 
session_start(); 
require_once("../../config.php"); 

header("Cache-control: private"); 
if (!$_SESSION['username']) { 
    echo '<div class="headers">&nbsp;&nbsp;You must be logged in to edit your profile. <a class="headers" href="../login/index.php">Go to login page...</a></div>';
    exit(); 
} 
$username = $_SESSION['username']; 

$maxfilesize = 81920; 
// check if there was a file uploaded 
if (!is_uploaded_file($_FILES['userbanner']['tmp_name'])) { 
    $error = "you didn't select a file to upload or the file size was to large.<br /><br><a href='upload_banner.php'>Try again...</a>"; 
// if it was, go ahead with other checks 
} else { 
    if ($_FILES['userbanner']['size'] > $maxfilesize) { 
        $error = "your image file was too large.<br />"; 
        unlink($_FILES['userbanner']['tmp_name']); 
    } else { 
        $ext = strrchr($_FILES['userbanner']['name'], "."); 
        if ($ext != ".gif" AND $ext != ".jpg" AND $ext != ".jpeg" AND $ext != ".bmp" AND $ext != ".GIF" AND $ext != ".JPG" AND $ext != ".JPEG" AND $ext != ".BMP") { 
            $error = "your file was an unacceptable type.<br />"; 
            unlink($_FILES['userbanner']['tmp_name']); 
        // if it's there, an okay size and type, copy to server and update the banner value in SQL 
        } else { 
            if ($_SESSION['banner'] != "nonpic.jpg" && $_SESSION['banner']!="") { 
                unlink("banners/".$_SESSION['banner']); 
            } 
            $newname = $_SESSION['username'].$ext; 
            move_uploaded_file($_FILES['userbanner']['tmp_name'],"banners/".$newname); 
            mysql_query("UPDATE accounts SET banner='$newname' WHERE username='$username'") or die (mysql_error()); 
            $_SESSION['banner'] = $newname; 
        } 
    } 
} 
?> 
<html><head><style>
body
{
background: #131313;
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
</style><title>Banner Upload Result</title></head><body> 
<h1Banner Upload Result</h1> 
<?php 
if ($error) { 
    echo "Your banner could not be uploaded because ".$error.""; 
} else { 
    echo "Your banner was successfully uploaded."; 
} 
?></body></html> 
