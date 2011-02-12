<?php
session_start();
$username = $_SESSION['username']; 
require("../include/config.php");

      $sql = "UPDATE djs SET banner='' WHERE username='$username'";
      if (@mysql_query($sql)) {
 			 header( 'location:index.php' );
      } 
	  else {
        echo('<p>Error deleting entry: ' .
             mysql_error() . '</p>');

      }

?>
