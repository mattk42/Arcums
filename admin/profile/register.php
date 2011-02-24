<?php
require("../../config.php");
require("../include/functions.php");
echo '
<head>
<title>ARCUMS 2.0</title>
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
</head>
';
switch($_GET['action'])
{
case "new":

//--------------------------------------
// [New Registration]
//--------------------------------------
if(!isset($_POST['register']))
{
echo "<br><br><br><br><br>
<table align='center' width=\"400\" bgcolor=\"000000\" style=\"border:3px solid #aaeeee\"> <tr><td><br />
<img src=\"images/djreg.jpg\"><br /><br />
<form action='register.php?action=new' method='POST'>
<table class='register'>
<tr><td>
Login Name: </td><td>
<input type='text' name='username' class='register_box'> !!NO SPACES!!
</td></tr>
<tr><td colspan='2'>Your login name will not be shown anywhere on your profile. It is strickly for logging in only. <Br><br><Br></td></tr>
<tr><td>
Real Name: </td><td>
<input type='text' name='name' class='register_box'>
</td></tr><tr><td>
E-mail:</td><td>
<input type='text' name='email' class='register_box'>
(MUST BE REAL)</td></tr><tr><td>
Password: </td><td>
<input type='password' name='password1' class='register_box'>
</td></tr><tr><td>
Password Again: </td><td>
<input type='password' name='password2' class='register_box'>
</td></tr>
<tr><td>Show Time</td><td>

<select name='showday' class='register_box'>
            <option selected>Sunday</option>
            <option>Monday</option>
            <option>Tuesday</option>
            <option>Wednesday</option>
            <option>Thursday</option>
            <option>Friday</option>
            <option>Saturday</option>
          </select>


          <select name='showtime' class='register_box'>
            <option selected>PLEASE SELECT</option>
			<option>12AM-3AM</option>
            <option>3AM-6AM</option>
            <option>6AM-9AM</option>
            <option>9AM-Noon</option>
            <option>Noon-3PM</option>
            <option>3PM-6PM</option>
            <option>6PM-9PM</option>
            <option>9PM-12AM</option>
          </select>

       </td></tr>


<tr><td>
</td><td>
<input type='hidden' value='wupx' name='djscode' class='register_box'>
</td></tr><tr><td colspan='2'>Please make sure your e-mail, show time, and name are correct. The administrator will use it to verify and approve your account. <font color='#ff0000'><br><br>Your profile will not show up on the website until it has been verified that you are a DJ at Radio-X.</b><br><br>
<input type='submit' name='register' value='Register!' class='register_box'>
</td></tr></table>
</form>
</td></tr></table>
";
}
elseif(isset($_POST['register']))
{
//put errors into an array
$errors = array();
$set_djscode = "wupx";

if(!empty($_POST['djscode']))
{
if($_POST['djscode']==$set_djscode)
{
$name = mysql_real_escape_string($_POST['name']);
$username = mysql_real_escape_string($_POST['username']);
$password1 = mysql_real_escape_string($_POST['password1']);
$password2 = mysql_real_escape_string($_POST['password2']);
$showtime = mysql_real_escape_string($_POST['showtime']);
$showday = mysql_real_escape_string($_POST['showday']);
$email = mysql_real_escape_string($_POST['email']);
$activation_code = generateCode(25);
$userq = "SELECT username FROM accounts WHERE username = '$username' LIMIT 1";
$emailq = "SELECT email FROM accounts WHERE email = '$email' LIMIT 1";
}
else
{
$errors[] = "<br><br><br><br><br>
<table class=\"text\" align='center' width=\"400\" bgcolor=\"000000\" style=\"border:3px solid #44ff22\"> <tr><td><br /><img src=\"images/djreg.jpg\"><br /><br />See there errors below?  Thats because of the following, way to go!<br /><br />E-Staff Authorization Code not Valid!<br /><br /><a href=\"http://wupx.com/arcums/djs/register.php?action=new\">LET ME TRY AGAIN!!</a>";
}
}
else
{
$errors[] = "<br><br><br><br><br>
<table class=\"text\"  align='center' width=\"400\" bgcolor=\"000000\" style=\"border:3px solid #44ff22\"> <tr><td><br /><img src=\"images/djreg.jpg\"><br /><br />See there errors below?  Thats because of the following, way to go!<br /><br /><center><a href=\"http://wupx.com/arcums/djs/register.php?action=new\">LET ME TRY AGAIN!!</a></center><br /><br />E-Staff Authorization Code was blank! <br />";
}
if(empty($name))
{
$errors[] = "The name field was blank! <br />";
}
if(empty($username))
{
$errors[] = "The username field was blank! <br />";
}
if(mysql_num_rows(mysql_query($userq)) > 0)
{
$errors[] = "The username given is already in use! Please try another one! <br />";
}
if(empty($password1))
{
$errors[] = "The password field was blank! <br />";
}


if ($password1 != $_POST['password2'])
{
$errors[] = " Your passwords don't match.  Please go back and try again.<br />";
}


if(empty($email))
{
$errors[] = "The email field was blank! <br />";
}
if(mysql_num_rows(mysql_query($emailq)) > 0)
{
$errors[] = "The email given is already in use! Please try another one! <br />";
}
if(count($errors) > 0)
{
foreach($errors as $err)
{
echo $err;
}
}
else
{
$sqlq = "INSERT INTO accounts (username, name, encryptpass, showtime, showday, email, is_activated, activation_code)";
$sqlq .= "VALUES ('$username', '$name', '".md5($password2)."', '$showtime', '$showday', '$email', '0', '$activation_code')";
mysql_query($sqlq) or die(mysql_error());
echo "<h3>Thanks for registering!
You may now login and update your profile. However you will not be visable on the webiste until the Webmaster has approved you, verifying you are a DJ at Radio-X.<br>
<a href=\"..\">Login</a>
";
mail($admin_email, "New Registration, www.WUPX.com","
The following user has tried to register as a DJ. Please verify the user and accept if they are infact a Radio-X DJ.
Here are their login details:
Name: ".$name."
Username: ".$username."
E-Mail: ".$email."
Show Day: ".$showday."
Show Time: ".$showtime."
In order for the DJ to appear on the website, you must validate their account.
Click here to validate after checking the schedule to verify them.:
http://www.wupx.com/arcums/profile/register.php?action=activate&user=".$username."&code=".$activation_code."&email=".$email."
");
}
}
break;

case "activate":
//--------------------------------------
// [Activate Account]
//--------------------------------------
if(isset($_GET['user']) && isset($_GET['code']) && isset($_GET['email']))
{
$username = mysql_real_escape_string($_GET['user']);
if(mysql_num_rows(mysql_query("SELECT id FROM accounts WHERE username = '$username'")) == 0)
{
echo "That username is not in the database!";
}
else
{
$activate_query = "SELECT is_activated FROM accounts WHERE username = '$username'";
$is_already_activated = mysql_fetch_object(mysql_query($activate_query)) or die(mysql_error());
if($is_already_activated->is_activated == 1)
{
echo "The user $username is already activated!";
}
else
{
$code = mysql_real_escape_string($_GET['code']);
$code_query = "SELECT activation_code FROM accounts WHERE username = '$username' LIMIT 1";
$check_code = mysql_fetch_object(mysql_query($code_query)) or die(mysql_error());
if($code == $check_code->activation_code)
{
$update = "UPDATE djs SET is_activated = '1' WHERE username = '$username'";
mysql_query($update) or die(mysql_error());
echo "User $username has been activated! They will now appear on the site! <Br>An E-mail has been sent to them to let them know.</a>";
$getemail = mysql_real_escape_string($_GET['email']);
mail($getemail, "Radio-X Profile Approved","
The Webmaster has approved your account. You will now be listed on the website.
Login at http://www.wupx.com/arcums/djs
This link also appears on the DJ listing page for easy access.
Thank You! Radio-X
");
}
else
{
echo "The activation code was wrong! Please try again!";
}
}
}
}
else
{
echo "No ID or user given to activate!";
}
break;

}
?>
