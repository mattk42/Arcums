<?php include "header.php" ?>

	<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />
	
	<script src="js/prototype.js" type="text/javascript"></script>
	<script src="js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
<h2>Media</h2>

<table cellpadding="10" cellspacing="10" style="margin: 0 auto;">
  <tr>
    <td colspan="4"><b>Wallpapers</b></td>
  </tr>
  <?php
 			$i = 0;
			$handle = opendir("media/wallpapers/");
			while($img = readdir($handle)){
				if(preg_match('/.*\.[(jpg),(png)]/',$img)){
					if($i==3){
						echo "</tr><tr>";
						$i = 0;
					}
					echo "<td><a href='media/wallpapers/$img'><img src='media/wallpapers/$img' width='200px'></a></td>";
					$i=$i+1;
				}
			}
?>



</table>

 <?php include("footer.php") ?>
