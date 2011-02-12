<?php 
include "../header.php";
require("config.php");

echo "<center>";		

//This section checks to see if this page should display band information
	if($_GET[callname]!=""){
		$sql =  "SELECT * FROM bands WHERE callname=\"$_GET[callname]\"";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		echo "<center><h1>" . $row[name]."</h1>";
		echo "<br>";
		echo "<img src=" . $row['image'] . "  height=200></a><br><br>";
		echo "<b>Hometown: </b>" . $row[hometown] . "<br>";
		echo "<b>Genre: </b>" . $row[genre] . "<br>";
		echo "<b>Website: </b><a href=" . $row[website] . ">" . $row[website] . "</a><br>";
		echo "<b>Listen: </b><a href=" . $row[listen] . ">" . $row[listen] . "</a><br><br>";	
		echo "<b>Info: </b><br><literal>" . $row[info] . "</literal>";
		echo "<br></center><br>";
		echo "<a href=../local_bands>Back</a>";
	}
	else{	
		echo "</center><H1>Local Bands: </H1><center>";
		echo "<table border='0' width='75%'>";
		echo "<tr>";
		echo "<td><a href=?char=><b>All Bands</b></a></td>";
		foreach(range('A','Z') as $i)
		{
			echo "<td>";
			echo "<a href=?char=" . $i . "><b>" . $i . "</b></a>";
			echo "</td>";
		}
		echo "</tr>";
		echo "</table>";
		echo "<table border='1' width='75%' BORDERCOLOR='#3C7580' cellpadding='0'>";
		$result = mysql_query("SELECT * FROM bands WHERE name LIKE '$_GET[char]%' ORDER BY name");
		if(mysql_num_rows($result)==0)
		{	
			echo "</table>";
			echo "NO RESULTS";
		}

		else
		{
			echo "<td><center>Image</center></td><td><center>Band Name</center></td><td><center>Listen</center></td>";
			while($row = mysql_fetch_array($result))
			{
				echo "<tr BORDERCOLOR='#3C7580'><td><center>";
				echo "<a href=?callname=" . $row[callname] . ">" . "<img src=" . $row['image'] . " 	height=100 ></a>";
				echo "</center></td><td><center>";
				echo "<a href=?callname=" . $row[callname] . ">" . $row['name'] . "</a>";
				echo "</center></td><td><center>";
				echo "<a href=" . $row[listen] . ">" . "<img src=images/speaker.png height=50></a>";
				echo "</center></td></tr>";
			}
		echo "</table>";
		echo "</center>";
	}
}
echo "</center>";
include("../footer.php"); ?>


