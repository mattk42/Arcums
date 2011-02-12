<?php 
include "../testheader.php";
require("config.php");

echo "<center>";	
$result = mysql_query("SELECT *  FROM events");
	if(mysql_num_rows($result)!=0){
		echo "</center><H1>Upcoming Events: </H1><center>";
		echo "<table border='1' width='75%' BORDERCOLOR='#3C7580' cellpadding='0'>";
		echo "<tr><td><center><b>What</b></center></td><td><center><b>When</b></center></td><td><center><b>Where</b></center></td><td><center><b>Link</b></center></td></tr>";
		while($row = mysql_fetch_array($result)){
			echo "<tr>";
			echo "<td><center>" . $row[what] . "</center></td>";
			$when = strtotime($row[when]);
			echo "<td><center>" . date('D M d g:iA ',$when) . "</center></td>";
			echo "<td><center>" . $row[where] . "</center></td>";
			echo "<td><center><a href=" . $row[link] . ">More Info</a></center></td>";
			echo "</tr>";
	}
		echo "</table>";	
		echo "<br><br>";		
	}
include "../footer.php";
?>
