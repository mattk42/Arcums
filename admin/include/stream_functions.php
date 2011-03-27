<?php

require_once("../../config.php");

function getCurrentListeners(){
	global $sc_conf;	
	$total = 0;
	if(isset($sc_conf)){
		foreach ($sc_conf as $sc_serv){
			$host = $sc_serv['host'];
			$port = $sc_serv['port'];
			$password = $sc_serv['password'];
			$total += getStreamListeners($host,$password,$port);
		}
	}
	return $total;
}


function getStreamListeners($hostname,$pwd, $port) {
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
		//echo $shoutcastXML;
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
