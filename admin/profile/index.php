<?php
session_start();
require ("../../config.php");
require ("../include/header.php");
require ("../include/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require ("../include/version.php"); ?></title>
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php

if (isset($_SESSION['dj_logged_in'])) {
    $session_username = $_SESSION['username'];

    // further checking...
    if (username_exists($session_username)) {
        $get_info = mysql_query("SELECT * FROM accounts WHERE username = '$session_username' LIMIT 1");
        
        if (mysql_num_rows($get_info) > 0) {
            $user_info = mysql_fetch_assoc($get_info);
            
            if (!isset($_POST['do_edit'])) {
                echo '<center><br /><table width="600"> <tr><td>
<center>

<table class="welcomebar">
<tr>
<td class="date">
DJ Profile Editor
</td>
<td class="loggedin">
You\'re logged in as ';
                echo $session_username;
                echo '
<a href="../login/logout.php">[logout]</a>&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>

<table><tr><td bgcolor="#ededed" valign="top">

<img src="photos/' . $user_info['photo'] . '" width="125" height="150"><br>
<a href="index.php">Refresh Photo</a><br>
<A HREF="javascript:popUp(\'upload_image.php\')">Change Photo</A><br><br>

<a href="javascript:popUp(\'change_password.php\')">Change Password</a> <br>
<br>
';
                echo '
</td><td>
&nbsp;</td><td>
<center>
<form action="index.php" method="post">
  <table  bgcolor="#ededed" width="550" border="0" cellspacing="3" cellpadding="3">
<tr>
<td class="headers" colspan="2">
Promote Your Show:
</td></tr><tr><td colspan="2">
<center>
Promote your show by uploading a banner that will be shown on every page on our site.<br> Banner should be 425x75 pixels.  No larger then 80KB.<br>
';
                
                if ($user_info['banner'] != '') {
                    echo "<img src='banners/$user_info[banner]' alt='Banner Preview Will Be Here After Upload'><br>
<A HREF=\"delete_banner.php\" onclick=\"javascript:return confirm('Are you sure you want to delete your current banner?'); return false;\">Delete Banner</A> |";
                }
                echo '

<a href="index.php">Refresh Banner</a>
 | <A HREF="javascript:popUp(\'upload_banner.php\')">Upload Banner</A>
</td></tr>
<tr>
<td class="headers" colspan="2">
Personal Information
</td></tr><tr><td bgcolor="#0089bc" colspan="2">
</center>
<div class="textme">If you leave a question blank, it\'ll be hidden on the site.  Fill out only what you want to be shown. Your time <b><u>MUST</u></b> be filled out correctly though. <br><br> You can use "hide account" so that you can use the login for the Playlist application, but not be listed on the DJ profiles part of the site.</div>


</td>
</tr>
   <tr>
      <td class="headers"><b>Hide Account:</b></td>
      <td>
';
?>
          <select name="hideaccount" class="register_box" />
            <option value="0" <?php 
                if ($user_info['hideaccount'] == "0") {
                    echo "selected";
                } ?>>No</option>
            <option value="1" <?php 
                if ($user_info['hideaccount'] == "1") {
                    echo "selected";
                } ?>>Yes</option>
                      </select>
       <?php
                echo '
</td>
    </tr>
    <tr>
      <td width="200" ><b>Real Name:</b></td>
      <td><input type="text" name="name"   class="register_box" value="' . $user_info['name'] . '" /></td>
    </tr>
   <tr>
      <td class="headers"><b>DJ Name:</b></td>
      <td><input type="text" name="djname"   class="register_box" value="' . $user_info['djname'] . '" /></td>
    </tr>
    <tr>
      <td> <b>Show Name:</b></td>
      <td><input type="text" name="showname"  class="register_box" value="' . $user_info['showname'] . '" /></td>
    </tr>
<tr>
      <td class="headers"><b>Time Slot: </b></td>
      <td align="left">
';
?>
          <select name="showday" class="register_box" />
            <<option <?php 
                if ($user_info['showday'] == "Sunday") {
                    echo "selected";
                } ?>>Sunday</option>
            <option <?php 
                if ($user_info['showday'] == "Monday") {
                    echo "selected";
                } ?>>Monday</option>
            <option <?php 
                if ($user_info['showday'] == "Tuesday") {
                    echo "selected";
                } ?>>Tuesday</option>
           <option <?php 
                if ($user_info['showday'] == "Wednesday") {
                    echo "selected";
                } ?>>Wednesday</option>
             <option <?php 
                if ($user_info['showday'] == "Thursday") {
                    echo "selected";
                } ?>>Thursday</option>
          <option <?php 
                if ($user_info['showday'] == "Friday") {
                    echo "selected";
                } ?>>Friday</option>
          <option <?php 
                if ($user_info['showday'] == "Saturday") {
                    echo "selected";
                } ?>>Saturday</option>
          </select>
      
          <select name="showtime" class="register_box" />
            <option <?php 
                if ($user_info['showtime'] == "12AM-3AM") {
                    echo "selected";
                } ?>>12AM-3AM</option>
            <option <?php 
                if ($user_info['showtime'] == "3AM-6AM") {
                    echo "selected";
                } ?>>3AM-6AM</option>
            <option <?php 
                if ($user_info['showtime'] == "6AM-9AM") {
                    echo "selected";
                } ?>>6AM-9AM</option>
            <option <?php 
                if ($user_info['showtime'] == "9AM-Noon") {
                    echo "selected";
                } ?>>9AM-Noon</option>
            <option <?php 
                if ($user_info['showtime'] == "Noon-3PM") {
                    echo "selected";
                } ?>>Noon-3PM</option>
            <option <?php 
                if ($user_info['showtime'] == "3PM-6PM") {
                    echo "selected";
                } ?>>3PM-6PM</option>
            <option <?php 
                if ($user_info['showtime'] == "6PM-9PM") {
                    echo "selected";
                } ?>>6PM-9PM</option>
            <option <?php 
                if ($user_info['showtime'] == "9PM-12AM") {
                    echo "selected";
                } ?>>9PM-12AM</option>
          </select>
       <?php
                echo '
</td>
    </tr>

    <tr>
      <td><b>Show Genre:</b> </td>
      <td><input type="text" name="showgenre" class="register_box"  value="' . $user_info['showgenre'] . '" /></td>
    </tr>
    <tr>
      <td class="headers"> <b>E-mail:</b> </td>
      <td><input type="text" name="email" class="register_box"  value="' . $user_info['email'] . '" />
';
?>hide?
          <select name="hideemail" class="register_box" />
           <option  value="0" <?php 
                if ($user_info['hideemail'] == "0") {
                    echo "selected";
                } ?>>No</option>
           <option value="1" <?php 
                if ($user_info['hideemail'] == "1") {
                    echo "selected";
                } ?>>Yes</option>
                      </select>
       <?php
                echo '
</td>
    </tr>


    <tr>
      <td><b>AIM:</b> </td>
      <td><input type="text" name="aim" class="register_box"  value="' . $user_info['aim'] . '" /></td>
    </tr>
    <tr>
      <td class="headers"><b>Yahoo:</b> </td>
      <td><input type="text" name="yahoo" class="register_box"  value="' . $user_info['yahoo'] . '" /></td>
    </tr>
    <tr>
      <td><b>MSN:</b></td>
      <td><input type="text" name="msn"  class="register_box" value="' . $user_info['msn'] . '" /></td>
    </tr>
    <tr>
      <td class="headers"><b>Website:</b></td>
      <td><input type="text" name="website"  class="register_box" value="' . $user_info['website'] . '" /></td>
    </tr>
    <tr>
      <td><b>MySpace:</b></td>
      <td><input type="text" name="myspace" class="register_box"  value="' . $user_info['myspace'] . '" /></td>
    </tr>
    <tr>
      <td class="headers"><b>Major:</b></td>
      <td><input type="text" name="major"  class="register_box" value="' . $user_info['major'] . '" /></td>
    </tr>
    <tr>
      <td><b>Minor:</b></td>
      <td><input type="text" name="minor" class="register_box"  value="' . $user_info['minor'] . '" /></td>
    </tr>
    <tr>
      <td class="headers"><b>Home:</b></td>
      <td><input type="text" name="home" class="register_box"  value="' . $user_info['home'] . '" /></td>
    </tr>
    <tr>
      <td><b>Favorite Genre: </b></td>
      <td><input type="text" name="favgenre" class="register_box"  value="' . $user_info['favgenre'] . '" /></td>
    </tr>
    <tr>
      <td class="headers"><b>Favorite Artist:</b> </td>
      <td><input type="text" name="favartist" class="register_box"  value="' . $user_info['favartist'] . '" /></td>
    </tr>
    <tr>
      <td ><b>Favorite CD: </b></td>
      <td><input type="text" name="favcd" class="register_box"  value="' . $user_info['favcd'] . '" /></td>
    </tr>
    <tr>
      <td class="headers"><b>Favorite Movie: </b></td>
      <td><input type="text" name="favmovie"  class="register_box" value="' . $user_info['favmovie'] . '" /></td>
    </tr>
    <tr>
      <td><b>Biography:</b></td>
      <td><textarea name="bio"  cols="40" rows="8" class="register_box" />' . $user_info['bio'] . '</textarea></td></tr>
    </tr>
<tr>
      <td class="headers"></td>
      <td class="headers"><br></td></tr>

    <tr>
      <td><br></td>      <td>Current show time is set to: <b>' . $user_info['showday'] . ' @ ' . $user_info['showtime'] . ' </b></b><br><br><input type="submit" name="do_edit" class="register_box"  value="Update Profile" /></td>
    </tr>
    
  </table></td></tr></table>
</form><br /><br /></td></tr></table></center>
';
            }
            elseif (isset($_POST['do_edit'])) {
                $email = mysql_real_escape_string($_POST['email']);
                $name = mysql_real_escape_string($_POST['name']);
                $hideemail = mysql_real_escape_string($_POST['hideemail']);
                $hideaccount = mysql_real_escape_string($_POST['hideaccount']);
                $djname = mysql_real_escape_string($_POST['djname']);
                $showname = mysql_real_escape_string($_POST['showname']);
                $showtime = mysql_real_escape_string($_POST['showtime']);
                $showday = mysql_real_escape_string($_POST['showday']);
                $showgenre = mysql_real_escape_string($_POST['showgenre']);
                $aim = mysql_real_escape_string($_POST['aim']);
                $yahoo = mysql_real_escape_string($_POST['yahoo']);
                $msn = mysql_real_escape_string($_POST['msn']);
                $website = mysql_real_escape_string($_POST['website']);
                $myspace = mysql_real_escape_string($_POST['myspace']);
                $major = mysql_real_escape_string($_POST['major']);
                $minor = mysql_real_escape_string($_POST['minor']);
                $home = mysql_real_escape_string($_POST['home']);
                $favgenre = mysql_real_escape_string($_POST['favgenre']);
                $favartist = mysql_real_escape_string($_POST['favartist']);
                $favcd = mysql_real_escape_string($_POST['favcd']);
                $favmovie = mysql_real_escape_string($_POST['favmovie']);
                $bio = mysql_real_escape_string($_POST['bio']);

                // assign all errors to an array
                $errors = array();
                
                if (empty($email)) {
                    $errors[] = 'Your email was empty.';
                }
                
                if (!is_valid_email($email)) {
                    $errors[] = 'Your email was not in a valid email format.';
                }

                // if array elements is greater than 0,
                // then we KNOW there was an error

                // else, no error, move on to processing

                
                if (count($errors) > 0) {
                    echo '<b>ERRORS:</b><br />';
                    
                    foreach ($errors as $err) {
                        echo $err . '<br />';
                    }
                }
                else {

                    // everything is ok, update the DB
                    mysql_query("UPDATE accounts SET email = '$email', bio = '$bio', hideemail = '$hideemail', hideaccount = '$hideaccount', name = '$name', showname = '$showname', showtime = '$showtime', aim = '$aim', yahoo = '$yahoo', msn = '$msn', website = '$website', myspace = '$myspace', major = '$major', djname = '$djname', minor = '$minor', home = '$home', favgenre = '$favgenre', favartist = '$favartist', favcd = '$favcd', favmovie = '$favmovie', showgenre = '$showgenre', showday = '$showday' WHERE username = '$session_username'") or die(mysql_error());
                    echo "<div class='headers'>Profile Updated. <a class='headers' href'index.php'>Continue...</a></div>";
                }
            }
        }
        else {
            echo 'Could not find profile info for your username.';
        }
    }
    else {
        echo '<b>Sorry, your session username doesnt exist</b>.';
    }
}
else {
    echo '<div class="headers">&nbsp;&nbsp;You must be logged in to edit your profile. <a class="headers" href="../login/index.php">Go to login page...</a></div>';
}
?>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=0,location=0,statusbar=1,menubar=0,resizable=1,width=400,height=225,left = 312,top = 309');");
}
// End -->
</script>
