<?php
session_start();
require ("../../config.php");
require ("../include/functions.php");

if (isset($_SESSION['dj_logged_in'])) {
    $session_username = $_SESSION['username'];
    $session_id = $_SESSION['djid'];

    // further checking...
    
    if (username_exists($session_username)) {
        
        if (!isset($_POST['do_edit'])) {
            $artist = mysql_real_escape_string($_POST['artist']);
            $song = mysql_real_escape_string($_POST['song']);
            $section = mysql_real_escape_string($_POST['section']);
            $sectionnumber = mysql_real_escape_string($_POST['sectionnumber']);
            $requested = mysql_real_escape_string($_POST['requested']);
            $date = mysql_real_escape_string($_POST['date']);
            $time = mysql_real_escape_string($_POST['time']);
            $datetime = mysql_real_escape_string($_POST['datetime']);
            $album = mysql_real_escape_string($_POST['album']);
            $label = mysql_real_escape_string($_POST['label']);
            $tracknumber = mysql_real_escape_string($_POST['tracknumber']);
            $listeners = mysql_real_escape_string($_POST['listeners']);
            mysql_query("INSERT INTO  playlist SET artist = '$artist', song = '$song', album = '$album', label = '$label', section = '$section', sectionnumber = '$sectionnumber', requested = '$requested', listeners = '$listeners', tracknumber = '$tracknumber', date = '$date', time = '$time', dj_id = '$session_id', datetime='$datetime' ") or die(mysql_error());
        }
        else {
            echo 'Could not recent playlists, please start a new one.';
        }
    }
    else {
        echo '<b>Sorry, your session doesnt exist. Please <a href="../login/index.php">login</a>.</b>';
    }
}
else {
    echo 'You must be logged in to edit your playlist. Please <a href="../login/index.php">login</a>.</b>';
}
header('location:index.php');
?>

