<?php 
session_start(); 
header("Cache-control: private"); 
if (!$_SESSION['username']) { 
    echo "You're not logged in!"; 
    include("login.html"); 
    exit(); 
} 
$username = $_SESSION['username']; 
$password = md5($_POST['pass']); 
$pass1 = mysql_real_escape_string($_POST['pass1']); 
include("../include/config.php"); 
// start with passwords 
if (($password) AND ($pass1) AND ($_POST['pass2'])) { 
// check if the authentication is right 
    if ($encryptpass != $_SESSION['encryptpass']) { 
        $error = " your current password is incorrect.</p>"; 
    } else { 
// otherwise, check if new passwords match 
        if ($pass1 != $_POST['pass2']) { 
            $error = " your new password selections don't match.</p>"; 
        } else { 
// otherwise, check if the password's legit 
            $legit = ereg("^[a-zA-Z0-9]{6,16}$", $pass1); 
            if (!$legit) { 
                $error = " your new password selections match, but aren't an appropriate length/form.</p>"; 
            } else { 
// authentication, match, legit, put the new pass in 
                $encryptpass = md5($pass1); 
                mysql_query("UPDATE djs SET encryptpass='$encryptpass' WHERE username='$username'") or die(mysql_error()); 
                $cpass = "<li>your password</li>"; 
                $_SESSION['encryptpass'] = $encryptpass; 
            } 
        } 
    } 
}  
?> 
<html><head><title>Change Password Results</title></head><body> 
<style>
body
{
background: #131313;
background-image: url('../images/bg.jpg');
font-family: Verdana, Arial;
font-weight: bold;
font-size: 9px;
color: #FFFFFF;
}
.text
{
font-family: Verdana, Arial;
font-weight: bold;
font-size: 11px;
color: #FFFFFF;
}
.header
{
font-family: Verdana, Arial;
font-weight: bold;
font-size: 14px;
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
</style>
<div class="header">Change Password Results</div> 
<?php 
if (($cpass)) { 
    echo "<p>The following things have been changed:</p><ul>"; 
    if ($cpass) { 
        echo $cpass;	
    } 
    echo "</ul>To close this window, <a href=\"javascript:window.close();\">Close Window</a>.</p>"; 
} 
if ($error) { 
    echo "<p>Your password could not be changed because".$error; 
} elseif ((!$cpass) AND (!$error)) { 
    echo "None of your account settings were changed."; 
} 
?> 
</body></html> 
