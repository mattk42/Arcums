<?php

//get listeners for our own server
$main = getCurrentListeners("wupx.nmu.edu","wupx915","8000");

//get Listeners for remote server
$secondary = getCurrentListeners("wupx5.nmu.edu","r4d10x","8000");

$total = $main + $secondary;
echo"Main: ";
echo $main;
echo "<br>Secondary: ";
echo $secondary;
echo "<br>Total: ";
echo $total;

// getCurrentListeners
// ** connects to shoutcast web administration xml feed for usage statistics
// -- returns a string of the current listeners or 0 if there was a problem
function getCurrentListeners($hostname,$pwd, $port) {

        $fp = @fsockopen($hostname, $port, &$errno, &$errstr, 30); // connect to the server

        $shoutcastXML = "";

        if ($fp) {
                // authenticate, and get xml feed from shoutcast administration
                fputs($fp, "GET /admin.cgi?mode=viewxml HTTP/1.1\r\nHost: $hostname:$port\r\n".
                "User-Agent: Shoutcast Stats (Mozilla Compatible)\r\n".
                "Authorization: Basic ".base64_encode ("admin:$pwd")."\r\n\r\n");

                while (!feof($fp)) {
                        // we only care about the xml, so only keep last line, discard headers
                        $shoutcastXML = fgets($fp, 8192);
                }

                @fclose($fp);
        }

        $shoutcastXML = ($shoutcastXML != "") ? simplexml_load_string($shoutcastXML) : false;
	
	$result = $shoutcastXML->CURRENTLISTENERS;

        if ($result != "")
                return $result;
	else{
		return "0";
	}

}


?>
