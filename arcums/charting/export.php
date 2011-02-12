<?php
$DB_Server = "localhost";		//your MySQL Server 
$DB_Username = "arcums";				 //your MySQL User Name 
$DB_Password = "arcums123";				//your MySQL Password 
$DB_DBName = "arcums";				//your MySQL Database Name 
$DB_TBLName = "playlist";				//your MySQL Table Name 
$getcatnumber = $_GET['catnumber'];
$getgenre = $_GET['genre'];

$sql = "SELECT artist, album, section, sectionnumber, date, COUNT(sectionnumber) AS timesplayed FROM playlist WHERE section = '$getgenre' AND sectionnumber > $getcatnumber AND date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY section, sectionnumber ORDER BY section ASC, timesplayed DESC";
$Use_Title = 1;
$now_date = date('m-d-Y H:i');
$title = "Dump For Table $DB_TBLName from Database $DB_DBName on $now_date";


$Connect = @mysql_connect($DB_Server, $DB_Username, $DB_Password)
	or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());

$Db = @mysql_select_db($DB_DBName, $Connect)
	or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());

$result = @mysql_query($sql,$Connect)
	or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());

if (isset($w) && ($w==1))
{
	$file_type = "msword";
	$file_ending = "doc";
}else {
	$file_type = "vnd.ms-excel";
	$file_ending = "xls";
}

header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=WUPX_Charting.$file_ending");
header("Pragma: no-cache");
header("Expires: 0");


if (isset($w) && ($w==1)) //check for $w again
{

	if ($Use_Title == 1)
	{
		echo("$title\n\n");
	}
	$sep = "\n"; //new line character

	while($row = mysql_fetch_row($result))
	{
		//set_time_limit(60); // HaRa
		$schema_insert = "";
		for($j=0; $j<mysql_num_fields($result);$j++)
		{
		//define field names
		$field_name = mysql_field_name($result,$j);
		//will show name of fields
		$schema_insert .= "$field_name:\t";
			if(!isset($row[$j])) {
				$schema_insert .= "NULL".$sep;
				}
			elseif ($row[$j] != "") {
				$schema_insert .= "$row[$j]".$sep;
				}
			else {
				$schema_insert .= "".$sep;
				}
		}
		$schema_insert = str_replace($sep."$", "", $schema_insert);
		$schema_insert .= "\t";
		print(trim($schema_insert));
		//end of each mysql row
		//creates line to separate data from each MySQL table row
		print "\n----------------------------------------------------\n";
	}
}else{
	/*	FORMATTING FOR EXCEL DOCUMENTS ('.xls')   */
	//create title with timestamp:
	if ($Use_Title == 1)
	{
		echo("$title\n");
	}
	//define separator (defines columns in excel & tabs in word)
	$sep = "\t"; //tabbed character

	//start of printing column names as names of MySQL fields
	for ($i = 0; $i < mysql_num_fields($result); $i++)
	{
		echo mysql_field_name($result,$i) . "\t";
	}
	print("\n");
	//end of printing column names

	//start while loop to get data
	while($row = mysql_fetch_row($result))
	{
		//set_time_limit(60); // HaRa
		$schema_insert = "";
		for($j=0; $j<mysql_num_fields($result);$j++)
		{
			if(!isset($row[$j]))
				$schema_insert .= "NULL".$sep;
			elseif ($row[$j] != "")
				$schema_insert .= "$row[$j]".$sep;
			else
				$schema_insert .= "".$sep;
		}
		$schema_insert = str_replace($sep."$", "", $schema_insert);	
		$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
		$schema_insert .= "\t";
		print(trim($schema_insert));
		print "\n";
	}
}

?>