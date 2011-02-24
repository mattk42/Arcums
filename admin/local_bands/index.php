<?php
session_start();
require("../../config.php");
require("../include/functions.php");
require("../include/header.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require("../include/version.php"); ?></title>
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
//include "../../testheader.php";
if($user_info['permissions'] > 1)
{

// further checking...

if($_GET[callname]!=NULL){
	$result = mysql_query("SELECT * FROM bands WHERE callname = '$_GET[callname]' ORDER BY name");
	if(mysql_num_rows($result)==0){
		echo "Invalid callname";
	}
	else{
		$row = mysql_fetch_array($result);
		echo "<center><H1>Editing " . $row[name] . "</H1></center>";
		echo "<center><table bgcolor='#FFFFFF' width=45% ><tr bgcolor=#FFFFFF><td><form action=edit.php enctype='multipart/form-data' method=POST>
		<b><center>	<br><br>
		Band Name:<input type=text name=name value='" . $row[name] . "'>
		<br>
		Hometown:<input type=text name=hometown value='" . $row[hometown] ."'>
		<br>
		Genre:<input type=text name=genre value='" . $row[genre] . "'>
		<input type=hidden name=callname value='" . $_GET[callname] . "'>
		<br>
		Website:<input type=text name=website value='" . $row[website] . "'>
		<br>
		<br>
		Band Info: <br>
		<textarea name=info cols=40 rows=5>". $row[info] . "
		</textarea><br>
		<br>
		<img src=../../local_bands/" . $row[image] . "></a><br>
		Image:<input name=uploaded type=file /><br />
		Check to change image: <input name=image type=checkbox><br>
		<br>
		Listen:<input type=text name=listen value='" . $row[listen] . "'><br><br>
		<input type=submit />
		</center></form></td></tr></table>
		<br><br><br><a href=./index.php><font color=#FFFFFF>Go Back</font></a>
		</center>";


	}

}
else{
echo "<center>";

		echo "<H1>Local Bands: </H1>";
		echo "<table border='0' width='45%'bgcolor='#FFFFFF'>";
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
		echo "<table border='1' width='45%' BORDERCOLOR='#3C7580' cellpadding='0' bgcolor='#FFFFFF'>";
		$result = mysql_query("SELECT * FROM local_bands WHERE name LIKE '$_GET[char]%' ORDER BY name");
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
				echo "<tr BORDERCOLOR='#3C7580' bgcolor='#FFFFFF' ><td><center>";
				echo "<a href=?callname=" . $row[callname] . ">" . "<img src=../../local_bands/" . $row['image'] . " 	height=100 ></a>";
				echo "</center></td><td><center>";
				echo "<a href=?callname=" . $row[callname] . ">" . $row['name'] . "</a>";
				echo "</center></td><td><center>";
				echo "<a href=" . $row[listen] . ">" . "<img src=../../local_bands/images/speaker.png height=50></a>";
				echo "<a href=./remove.php?id=" . $row[id] . ">Remove</a>";
				echo "</center></td></tr>";
			}
		echo "</table>";
	}

			echo "<br><br><br><a href=./addband.php><font color=#FFFFFF>Add Band</font></a>";

echo "</center>";
}
}
else{
echo "<br><br><br><br><center><font color=white><b>You are not authorized for this section.</b></font>";
}
//include("../../footer.php") ?>


