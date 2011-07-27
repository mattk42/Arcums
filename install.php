<?php

if (isset($_POST['dbhost'])) {

	//check for required data
	if(empty($_POST['dbhost'])|empty($_POST['dbuser'])|empty($_POST['dbname'])|empty($_POST['dbpass'])|empty($_POST['dbpref'])|empty($_POST['aroot'])|empty($_POST['auser'])|empty($_POST['apass'])|empty($_POST['aemail'])|empty($_POST['bdisp'])){
		die("Please go back and fill in all requires fields.");
	}

mysql_connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass']) or die("Error connecting to MySQL databse: " . mysql_error());
	echo "<b>Installing:</b><br>";

	//Escape all of the user input.
    $host = mysql_real_escape_string($_POST['dbhost']);
	$user = mysql_real_escape_string($_POST['dbuser']);
    $pass = mysql_real_escape_string($_POST['dbpass']);
    $db = mysql_real_escape_string($_POST['dbname']);
    $pref = mysql_real_escape_string($_POST['dbpref']);
   	$root = mysql_real_escape_string($_POST['aroot']);
	$admin_user = mysql_real_escape_string($_POST['auser']);
	$admin_pass = mysql_real_escape_string($_POST['apass']);
	$admin_email = mysql_real_escape_string($_POST['aemail']);
	$amazonpub = mysql_real_escape_string($_POST['amazonpub']);
	$amazonpri = mysql_real_escape_string($_POST['amazonpri']);
	$audioscrob = mysql_real_escape_string($_POST['audioscrobkey']);
	$blogd = mysql_real_escape_string($_POST['bdisp']);
	$shost = mysql_real_escape_string($_POST['shost']);
	$sport = mysql_real_escape_string($_POST['sport']);
	$spass = mysql_real_escape_string($_POST['spass']);
    
	//Create the DB
    $create_db_query = "CREATE DATABASE IF NOT EXISTS $db";
    mysql_query($create_db_query) or die("Error Creating Database" . mysql_error());
    echo "DB Created<br>";
   echo "CREATING TABLES"; 
    //Create the tables
    mysql_select_db($db);
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `accounts` (
								  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
								  `username` varchar(45) NOT NULL DEFAULT '',
								  `email` varchar(45) NOT NULL DEFAULT '',
								  `is_activated` varchar(45) NOT NULL DEFAULT '0',
								  `activation_code` varchar(45) NOT NULL DEFAULT '',
								  `name` varchar(45) NOT NULL DEFAULT '',
								  `djname` varchar(45) NOT NULL DEFAULT '',
								  `showname` longtext NOT NULL,
								  `showtime` varchar(45) NOT NULL DEFAULT '',
								  `aim` varchar(45) NOT NULL DEFAULT '',
								  `yahoo` varchar(45) NOT NULL DEFAULT '',
								  `msn` varchar(45) NOT NULL DEFAULT '',
								  `website` varchar(45) NOT NULL DEFAULT '',
								  `facebook` varchar(45) NOT NULL DEFAULT '',
								  `myspace` varchar(50) NOT NULL,
								  `favgenre` varchar(45) NOT NULL DEFAULT '',
								  `favartist` longtext NOT NULL,
								  `favcd` longtext NOT NULL,
								  `favmovie` longtext NOT NULL,
								  `major` varchar(45) NOT NULL DEFAULT '',
								  `minor` varchar(45) NOT NULL DEFAULT '',
								  `home` varchar(45) NOT NULL DEFAULT '',
								  `bd` varchar(45) NOT NULL DEFAULT '',
								  `bio` longtext NOT NULL,
								  `picture` varchar(45) NOT NULL DEFAULT 'nopic.jpg',
								  `showgenre` longtext NOT NULL,
								  `showday` varchar(45) NOT NULL DEFAULT '',
								  `photo` varchar(45) NOT NULL DEFAULT 'nopic.jpg',
								  `encryptpass` varchar(45) NOT NULL DEFAULT '',
								  `banner` varchar(45) NOT NULL DEFAULT '',
								  `hideaccount` varchar(45) NOT NULL DEFAULT '0',
								  `hideemail` varchar(45) NOT NULL DEFAULT '0',
								  `confirm_code` varchar(45) NOT NULL DEFAULT '',
								  `permissions` varchar(45) NOT NULL DEFAULT '1',
								  `staff` varchar(45) NOT NULL DEFAULT '0',
								  `position` varchar(125) NOT NULL DEFAULT '',
								  `sunday` varchar(45) NOT NULL DEFAULT '',
								  `monday` varchar(45) NOT NULL DEFAULT '',
								  `tuesday` varchar(45) NOT NULL DEFAULT '',
								  `wednesday` varchar(45) NOT NULL DEFAULT '',
								  `thursday` varchar(45) NOT NULL DEFAULT '',
								  `friday` varchar(45) NOT NULL DEFAULT '',
								  `saturday` varchar(45) NOT NULL DEFAULT '',
								  `goals` longtext NOT NULL,
								  `stafforder` varchar(45) NOT NULL DEFAULT '',
								  `last_login` datetime NOT NULL,
								  PRIMARY KEY (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=310 ;";
	$create_table_query[] = "INSERT INTO accounts (username,is_activated,email,staff,permissions,hideaccount,encryptpass) VALUES('$admin_user','1','$admin_email','1','4','1',MD5('$admin_pass'));";
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `blog_comments` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `post_id` int(11) NOT NULL,
								  `name` varchar(64) NOT NULL,
								  `email` varchar(128) NOT NULL,
								  `validation_Code` varchar(64) NOT NULL,
								  `valid` int(11) NOT NULL,
								  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
								  `title` varchar(128) NOT NULL,
								  `text` longtext NOT NULL,
								  `hide` int(11) NOT NULL,
								  `reported` int(1) NOT NULL DEFAULT '0',
								  KEY `id` (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=550 ;";
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `blog_posts` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `dj_id` int(11) NOT NULL,
								  `type` int(11) NOT NULL,
								  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
								  `title` varchar(128) NOT NULL,
								  `text` longtext NOT NULL,
								  `hide` int(11) NOT NULL,
								  UNIQUE KEY `id` (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=116 ;";
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `catalog_albums` (
								  `ID` int(11) NOT NULL AUTO_INCREMENT,
								  `category_id` int(11) NOT NULL,
								  `category_number` int(11) NOT NULL,
								  `vinyl_letter` varchar(2) DEFAULT NULL,
								  `title` varchar(128) NOT NULL,
								  `artist` varchar(128) NOT NULL,
								  `label` varchar(128) NOT NULL,
								  `upc` varchar(32) DEFAULT NULL,
								  `release` varchar(32) NOT NULL,
								  `discs` int(11) NOT NULL,
								  `image` text NOT NULL,
								  `description` varchar(256) NOT NULL,
								  `comments` varchar(256) NOT NULL,
								  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
								  `vinyl` tinyint(1) NOT NULL,
								  `history` longtext NOT NULL,
								  `similar_cache_date` date NOT NULL,
								  PRIMARY KEY (`ID`)
								) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=149027 ;";
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `catalog_categories` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `prefix` varchar(4) NOT NULL,
								  `name` varchar(64) NOT NULL,
								  PRIMARY KEY (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;";
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `catalog_request` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `album_id` int(11) NOT NULL,
								  `track_id` int(11) NOT NULL,
								  `datetime` datetime NOT NULL,
								  KEY `id` (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;";
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `catalog_similar` (
								  `artist` varchar(128) DEFAULT NULL,
								  `sim_artist` varchar(128) DEFAULT NULL
								) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `catalog_tracks` (
								  `ID` int(11) NOT NULL AUTO_INCREMENT,
								  `album_id` int(11) NOT NULL,
								  `position` int(11) NOT NULL,
								  `title` varchar(128) NOT NULL,
								  `cd_number` int(11) NOT NULL,
								  `length` varchar(11) NOT NULL,
								  PRIMARY KEY (`ID`)
								) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=160541 ;";
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `events` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `start` datetime DEFAULT NULL,
								  `end` datetime DEFAULT NULL,
								  `title` varchar(64) NOT NULL,
								  `location` varchar(64) NOT NULL,
								  `details` text NOT NULL,
								  `history` varchar(64) NOT NULL,
								  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
								  PRIMARY KEY (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;";
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `local_bands` (
								  `name` varchar(45) NOT NULL DEFAULT '',
								  `banner` varchar(45) NOT NULL DEFAULT '',
								  `hometown` varchar(45) NOT NULL DEFAULT '',
								  `genre` varchar(45) NOT NULL DEFAULT '',
								  `website` varchar(90) NOT NULL DEFAULT '',
								  `info` blob NOT NULL,
								  `image` varchar(45) NOT NULL DEFAULT '',
								  `callname` varchar(45) NOT NULL DEFAULT '',
								  `listen` varchar(90) NOT NULL DEFAULT '',
								  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
								  PRIMARY KEY (`id`)
								) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `playlist` (
								  `auto` int(10) unsigned NOT NULL AUTO_INCREMENT,
								  `artist` varchar(45) NOT NULL DEFAULT '',
								  `song` varchar(45) NOT NULL DEFAULT '',
								  `section` varchar(45) NOT NULL DEFAULT '',
								  `sectionnumber` varchar(45) NOT NULL DEFAULT '',
								  `requested` varchar(45) NOT NULL DEFAULT '0',
								  `psa` varchar(45) NOT NULL DEFAULT '',
								  `psanumber` varchar(45) NOT NULL DEFAULT '',
								  `dj` varchar(45) NOT NULL DEFAULT '',
								  `time` varchar(45) DEFAULT NULL,
								  `date` varchar(45) NOT NULL DEFAULT '',
								  `label` varchar(45) NOT NULL DEFAULT '',
								  `album` varchar(45) NOT NULL DEFAULT '',
								  `listeners` varchar(45) NOT NULL DEFAULT '',
								  `tracknumber` varchar(45) NOT NULL DEFAULT '',
								  `dj_id` varchar(45) NOT NULL DEFAULT '',
								  `datetime` datetime DEFAULT NULL,
								  PRIMARY KEY (`auto`)
								) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=145369 ;";
    $create_table_query[] = "CREATE TABLE IF NOT EXISTS `schedule` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `djname` text NOT NULL,
								  `djstation` varchar(20) NOT NULL DEFAULT '',
								  `djtitel` text NOT NULL,
								  `djdag` varchar(20) NOT NULL DEFAULT '',
								  `djstart` varchar(20) NOT NULL DEFAULT '',
								  `djstop` varchar(20) NOT NULL DEFAULT '',
								  `djtimeslot` varchar(20) NOT NULL DEFAULT '',
								  `djbeskrivelse` varchar(200) NOT NULL DEFAULT '',
								  PRIMARY KEY (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=145 ;";
    foreach ($create_table_query as $query) {
        mysql_query($query) or die("Error creating tables. " . mysql_error());
        echo "Table Created<br>";
    }
      
    
    //Create config.php
$cf = "
//General Config
\$admin_email = \"$admin_email\";
\$root = \"$root\";

//Catalog Config
\$catalog_report_emails=array(\"$admin_email\");

//Blog Config
\$blog_display_limit = $blogd;";

if($_POST['hasamazon']=="on"){
$cf.="
//Amazon API Config
\$amazon_public_key = \"$amazonpub\";
\$amazon_private_key = \"$amazonpri\";";
}
if($_POST['hasaudioscrob']=="on"){
$cf.="
//AudioScrobbler Config
\$audioscrobbler_api_key=\"$audioscrob\";";
}
if($_POST['hasstream']=="on"){
$cf.="
//WebStream Config
\$sc_conf = array(array(\"host\"=>\"$shost\",\"port\"=>\"$sport\",\"password\"=>\"$spass\"));";
}

$cf.="
//Database Config
\$username = \"$user\";
\$password = \"$pass\";
\$database = \"$db\";
\$server = \"$host\";
mysql_connect(\$server, \$username, \$password) or die(mysql_error());
mysql_select_db(\$database) or die(mysql_error());
";

   	$config = fopen(str_replace('/install.php','/',$_SERVER['SCRIPT_FILENAME'])."config.php","w") or die ("<font color=\"red\"><b>Failed Creating Config File</b></font>");
   	fwrite($config,"<?php".$cf."?>");
   	fclose($config) or die ("Failed writing config file");
    
echo "Config Complete";
echo "Installation Complete!<br>";
}
else {

?>

<script language="JavaScript" type="text/javascript">
	function checkform(form){
	var error=false;
	var required = [form.dbhost,form.dbuser,form.dbname,form.dbpass,form.dbpref,form.aroot,form.auser,form.apass,form.aemail,form.bdisp];
	for(var i in required)
	{
		if(required[i].value==''){
			required[i].style.backgroundColor='#ff0000';
			error=true;
		}
		else{
			required[i].style.backgroundColor='#ffffff';
		}
	}
	 if(error){
	 	alert("Please fill in all required fields.");
	 	return false;
	 }
	  return true ;
	}
	
	function enable(form){
		//amazon inputs
		form.amazonpub.disabled=!form.hasamazon.checked;
		form.amazonpri.disabled=!form.hasamazon.checked;
		//audiscrobbler inputs
		form.audioscrobkey.disabled=!form.hasaudioscrob.checked;
		//stream inputs
		form.shost.disabled=!form.hasstream.checked;
		form.sport.disabled=!form.hasstream.checked;
		form.spass.disabled=!form.hasstream.checked;
	}
</script>

<form name='db' method='POST' onsubmit='return checkform(this);'>
	<b>Database Settings:</b> MySql only<br>
	Database Server:*<input name='dbhost'><br>
	Database User:*<input name='dbuser'><br>
	Database Password:*<input type=password name='dbpass'><br>
	Databse Name:*<input name='dbname'><br>
	Table Prefix:*<input name='dbpref'><br><br>
	<b>Application Settings:</b><br>
	Application Root:*<input name='aroot' value='<?php echo "http://" . $_SERVER["HTTP_HOST"] . str_replace("/install.php","",$_SERVER["REQUEST_URI"]) ?>'><br>
	Admin Username:*<input name='auser'><br>
	Admin Password:*<input type='password' name='apass'><br>
	Admin email:*<input name='aemail'><br><br>
	<b>Blog Settings</b><br>
	Display limit:*<input type='number' name='bdisp'><br><br>
	<b>API Key:</b><br>
	Enable Amazon Features:<input type='checkbox' name='hasamazon' onChange='return enable(form);'><br>
	Amazon Public Key:<input disabled name='amazonpub'><br>
	Amazon Private Key:<input disabled name='amazonpri'><br>
	Enable Audioscrobber Features:<input type='checkbox' name='hasaudioscrob' onChange='return enable(form);'><br>
	Audioscrobbler (last.fm) API Key:<input disabled name='audioscrobkey'><br><br>
	<b>Stream Settings</b><br>
	Enable Shoutcast Features:<input type='checkbox' name='hasstream' onChange='return enable(form);'><br>
	Stream Host:<input disabled name='shost'><br>
	Stream Port:<input disabled name='sport'><br>
	Stream Password:<input disabled name='spass'><br><br><br>
	
	<input type='submit'>
</form>

<?php
}
?>
