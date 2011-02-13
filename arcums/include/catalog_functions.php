<script type="text/javascript">
function DoNav(theUrl)
  {
  document.location.href = theUrl;
  }
</script>
<?php


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
	require_once("../../config.php");
	global $audioscrobbler_api_key;
	$url = "http://ws.audioscrobbler.com/2.0/?method=artist.getSimilar&artist=" . urlencode($artist) . "&api_key=" . $audioscrobbler_api_key . "&limit=" . $limit;
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


function aws_signed_request($region, $params, $public_key, $private_key)
{
    /*
    Copyright (c) 2009 Ulrich Mierendorff

    Permission is hereby granted, free of charge, to any person obtaining a
    copy of this software and associated documentation files (the "Software"),
    to deal in the Software without restriction, including without limitation
    the rights to use, copy, modify, merge, publish, distribute, sublicense,
    and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
    THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
    DEALINGS IN THE SOFTWARE.
    */
    
    /*
    Parameters:
        $region - the Amazon(r) region (ca,com,co.uk,de,fr,jp)
        $params - an array of parameters, eg. array("Operation"=>"ItemLookup",
                        "ItemId"=>"B000X9FLKM", "ResponseGroup"=>"Small")
        $public_key - your "Access Key ID"
        $private_key - your "Secret Access Key"
    */

    // some paramters
    $method = "GET";
    $host = "ecs.amazonaws.".$region;
    $uri = "/onca/xml";
    
    // additional parameters
    $params["Service"] = "AWSECommerceService";
    $params["AWSAccessKeyId"] = $public_key;
    // GMT timestamp
    $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
    // API version
    $params["Version"] = "2009-03-31";
    
    // sort the parameters
    ksort($params);
    
    // create the canonicalized query
    $canonicalized_query = array();
    foreach ($params as $param=>$value)
    {
        $param = str_replace("%7E", "~", rawurlencode($param));
        $value = str_replace("%7E", "~", rawurlencode($value));
        $canonicalized_query[] = $param."=".$value;
    }
    $canonicalized_query = implode("&", $canonicalized_query);
    
    // create the string to sign
    $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
    
    // calculate HMAC with SHA256 and base64-encoding
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));
    
    // encode the signature for the request
    $signature = str_replace("%7E", "~", rawurlencode($signature));
    
    // create request
    $request = "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature;    

    // do request
	//echo $request;  
  $response = @file_get_contents($request);
    
    if ($response === False)
    {
        return False;
    }
    else
    {
        // parse XML

//OUTPUT THE RESPONSE TO A FILE	
//$myFile = "testFile.txt";
//$fh = fopen($myFile, 'w') or die("can't open file");
//fwrite($fh, $response);


//	echo $response,"<br><br>";
        $pxml = simplexml_load_string($response);
  
      if ($pxml === False)
        {
            return False; // no xml
        }
        else
        {
            return $pxml;
        }
    }
}
?>
