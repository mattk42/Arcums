<script type="text/javascript">
function DoNav(theUrl)
  {
  document.location.href = theUrl;
  }
</script>
<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

 function parse($data, $delimiter = ',', $enclosure = '"', $newline = "\n"){
        $pos = $last_pos = -1;
        $end = strlen($data);
        $row = 0;
        $quote_open = false;
        $trim_quote = false;

        $return = array();

        // Create a continuous loop
        for ($i = -1;; ++$i){
            ++$pos;
            // Get the positions
            $comma_pos = strpos($data, $delimiter, $pos);
            $quote_pos = strpos($data, $enclosure, $pos);
            $newline_pos = strpos($data, $newline, $pos);

            // Which one comes first?
            $pos = min(($comma_pos === false) ? $end : $comma_pos, ($quote_pos === false) ? $end : $quote_pos, ($newline_pos === false) ? $end : $newline_pos);

            // Cache it
            $char = (isset($data[$pos])) ? $data[$pos] : null;
            $done = ($pos == $end);

            // It it a special character?
            if ($done || $char == $delimiter || $char == $newline){

                // Ignore it as we're still in a quote
                if ($quote_open && !$done){
                    continue;
                }

                $length = $pos - ++$last_pos;

                // Is the last thing a quote?
                if ($trim_quote){
                    // Well then get rid of it
                    --$length;
                }

                // Get all the contents of this column
                $return[$row][] = ($length > 0) ? str_replace($enclosure . $enclosure, $enclosure, substr($data, $last_pos, $length)) : '';

                // And we're done
                if ($done){
                    break;
                }

                // Save the last position
                $last_pos = $pos;

                // Next row?
                if ($char == $newline){
                    ++$row;
                }

                $trim_quote = false;
            }
            // Our quote?
            else if ($char == $enclosure){

                // Toggle it
                if ($quote_open == false){
                    // It's an opening quote
                    $quote_open = true;
                    $trim_quote = false;

                    // Trim this opening quote?
                    if ($last_pos + 1 == $pos){
                        ++$last_pos;
                    }

                }
                else {
                    // It's a closing quote
                    $quote_open = false;

                    // Trim the last quote?
                    $trim_quote = true;
                }

            }

        }

        return $return;
    }



//find the image from amzon, updates the database, and returns the image link (NONE if there is no image)
function findImage($upc){
//get images for the albums you can
	include_once("../../../config.php");
	//$result = mysql_query("SELECT * FROM albums WHERE id='" . $id . "'");
	//$row = mysql_fetch_row($result);
	$pxml = aws_signed_request("com", array("Operation"=>"ItemSearch","SearchIndex"=>"Music","Keywords"=>$upc,"ResponseGroup"=>"Large"), $amzon_public_key, $amazon_private_key);
	if (isset($pxml->Items->Item->LargeImage->URL)){
		$image = $pxml->Items->Item->LargeImage->URL;
		mysql_query("UPDATE albums SET image='" . $image . "' WHERE upc='". $upc . "'") or die(mysql_error());
		return $image;
	}
	else{
		//mysql_query("UPDATE albums SET image='NONE' WHERE id='". $id . "'") or die(mysql_error());
		return "NONE";
	}
}

function get_similar($artist, $limit=9999){
	$url = "http://ws.audioscrobbler.com/2.0/?method=artist.getSimilar&artist=" . urlencode($artist) . "&api_key=f17c8bea37418b85d192dccbb244a9f9&limit=" . $limit;
	$response = @file_get_contents($url);
	if(!$response){
		return false;
	}
	else{
		$xml = simplexml_load_string($response);
		return $xml;
	}
}

function addSong($artist, $song, $section, $sectionnumber, $album, $label, $tracknumber){
	$requested = 0;

	$date= date("Y-m-d");
	$time = date("H:i:s");
	$datetime = date("YmdHis");

	$listeners = 0;

		$URL="http://wupx.com/arcums/playlist/addparse.php"; 
		$ch = curl_init();    
		curl_setopt($ch, CURLOPT_URL,$URL);  
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, "artist=$artist&song=$song&section=$section&sectionnumber=$sectionnumber&album=$album&label=$label&tracknumber=$tracknumber&requested=$requested&date=$date&time=$time&datetime=$datetime&listeners=$listeners");curl_exec ($ch);     
		curl_close ($ch); 
}

?>
