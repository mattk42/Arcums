<?php
session_start();
require("../include/config.php");
     
      $auto = $_GET['auto'];
      $sql = "DELETE FROM playlist
              WHERE auto=$auto";
      if (@mysql_query($sql)) {
 			 header( 'location:index.php' );
      } 
	  else {
        echo('<p>Error deleting entry: ' .
             mysql_error() . '</p>');

      }

?>
