
<?php
session_start();
require ("../../config.php");
require ("../include/functions.php");

if (isset($_SESSION['dj_logged_in'])) {
    $session_username = $_SESSION['username'];

    // further checking...
    
    if (username_exists($session_username)) {
        $autoget = mysql_real_escape_string($_GET['auto']);
        $get_info = mysql_query("SELECT * FROM playlist WHERE auto=$autoget");
        
        if (mysql_num_rows($get_info) > 0) {
            $playlist_edit = mysql_fetch_assoc($get_info);
            
            if (!isset($_POST['do_edit'])) {
                require ("../include/header.php");
                echo '

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ARCUMS 2.0</title>
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
</head>

<body>
<center><br>
<table class="welcomebar">
<tr>
<td class="date">
Editing
</td>
<td class="loggedin">
You\'re logged in as ';
                echo $session_username;
                echo '
<a href="../login/logout.php">[logout]</a>&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>

<table><tr>

<td>
<center>
<form action="edit.php?auto=' . $playlist_edit['auto'] . '" method="post">
  <table width="600" border="0" cellspacing="5" cellpadding="" class="edittable">
    <tr>
      <td class="headers" width="" ><b>Artist:</b></td>
      <td><input type="text" name="artist" onChange="javascript:this.value=this.value.toUpperCase();"   class="register_box" value="' . $playlist_edit['artist'] . '" /></td>
    </tr>
	<tr>
      <td class="headers" width="" ><b>Track Number:</b></td>
      <td><input type="text" name="tracknumber"  onChange="javascript:this.value=this.value.toUpperCase();" class="register_box" value="' . $playlist_edit['tracknumber'] . '" /></td>
    </tr>
	<tr>
      <td class="headers" width="" ><b>Song:</b></td>
      <td><input type="text" name="song"  onChange="javascript:this.value=this.value.toUpperCase();" class="register_box" value="' . $playlist_edit['song'] . '" /></td>
    </tr>
	<tr>
      <td class="headers" ><b>Album Title:</b></td>
	  <td><input type="text" name="album" onChange="javascript:this.value=this.value.toUpperCase();"  class="register_box" value="' . $playlist_edit['album'] . '" /></td>
	</tr>
	<tr>
      <td class="headers" ><b>Record Label:</b></td>
	  <td><input type="text" name="label"  onChange="javascript:this.value=this.value.toUpperCase();" class="register_box" value="' . $playlist_edit['label'] . '" /></td>
	</tr>
	
	    <tr>
      <td class="headers" width="" ><b>Section:</b></td>
      <td>
	  
	  
	  ';
                $query = "SELECT genres FROM genres ORDER by genres ASC";
                $result = mysql_query($query);
                echo "<select name=\"section\">";
                
                while ($nt = mysql_fetch_array($result)) {
                    echo "<option ";
                    
                    if ($playlist_edit['section'] == $nt['genres']) {
                        echo "selected";
                    }
                    echo " value=$nt[genres]>$nt[genres]</option>";
                }
                echo '
	  
	  </select>
	  
	  

	  
	  
	  
	  </td>
    </tr>
	    <tr>
      <td class="headers" width="" ><b>Section Number:</b></td>
      <td><input type="text" maxlength="5"  name="sectionnumber"   class="register_box" value="' . $playlist_edit['sectionnumber'] . '" /></td>
    </tr>
   
    <tr>
      <td class="headers" width="" ><b>Requested:</b></td>
      <td><select name="requested">
        <option
		';
                
                if ($playlist_edit['requested'] == "0") {
                    echo "selected";
                }
                echo ' value="0" selected>No</option>
        <option 		';
                
                if ($playlist_edit['requested'] == "1") {
                    echo "selected";
                }
                echo ' value="1">Yes</option>
            </select></td>
    </tr>




    <tr>
      <td  class="headers" colspan="2" align="center"><br><input type="submit" name="do_edit" class="register_box"  value="Update Song Details" /><br><br></td>
    </tr>
    

';
                require ("../include/footer.php");
                echo '









  </table></td></tr></table>
</form><br /><br /></td></tr></table></center>
';
            }
            elseif (isset($_POST['do_edit'])) {
                $artist = mysql_real_escape_string($_POST['artist']);
                $song = mysql_real_escape_string($_POST['song']);
                $section = mysql_real_escape_string($_POST['section']);
                $sectionnumber = mysql_real_escape_string($_POST['sectionnumber']);
                $requested = mysql_real_escape_string($_POST['requested']);
                $album = mysql_real_escape_string($_POST['album']);
                $label = mysql_real_escape_string($_POST['label']);
                $tracknumber = mysql_real_escape_string($_POST['tracknumber']);
                mysql_query("UPDATE playlist SET artist = '$artist', song = '$song', album = '$album', label = '$label', section = '$section', sectionnumber = '$sectionnumber', tracknumber = '$tracknumber', requested = '$requested' WHERE auto=$autoget");
                echo '<meta http-equiv="REFRESH" content="0;url=http://www.wupx.com/arcums/playlist/index.php"></HEAD>';
                exit;
            }
        }
        else {
            echo 'Could not find profile info for your $autoget username.';
        }
    }
    else {
        echo '<b>Sorry, your session username doesnt exist</b>.';
    }
}
else {
    echo '<meta HTTP-EQUIV="REFRESH" content="0; url=../login">';
}
?>

</center>
</body>
</html>

