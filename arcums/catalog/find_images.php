<?php 

include('includes/config.php');
include("includes/aws_signed_request.php");

//get images for the albums you can
$public_key = "AKIAIRXGMW3OVO7DK66Q";
$private_key = "h7ygzBVQ0HjQRR471RjJlJBj5zJlEU/0MhpEYe85";

$result = mysql_query("SELECT * FROM albums WHERE image='' and UPC!=''");
echo mysql_num_rows($result)."\n";
while($row = mysql_fetch_row($result)){
	$pxml = aws_signed_request("com", array("Operation"=>"ItemSearch","SearchIndex"=>"Music","Keywords"=>$row[7],"ResponseGroup"=>"Large"), $public_key, $private_key);
echo $row[7]; 
if (isset($pxml->Items->Item->LargeImage->URL))
    	{
		echo " Found!";
		mysql_query("UPDATE albums SET image='" . $pxml->Items->Item->LargeImage->URL . "' WHERE id='". $row['0'] . "'") or die(mysql_error());
	}
		echo "\n";
}

?>
