<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="themes/<?php echo $curtheme; ?>/wupx.css" media="screen" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php require_once("config.php") ?>
<title><?php echo $stationname; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $root; ?>/themes/<?php echo $curtheme;?>/wupx.css" media="screen" />

<div id="container">
  <div id="header">
		<div class="top"><span class="cornerTopLeft" style="display: none; vertical-align: top;">&nbsp;</span>&nbsp;</div>
  	
    <h1><span><?php echo $stationname; ?></span></h1>
  
<?php

function generateNav() {
    global $root;
    //Array of links to show in header
    $links = array(
        array(
            "pageName" => "Home",
            "title" => "Go to the main page",
            "url" => "$root/index.php"
        ) ,
    );
	$query="SELECT id,name FROM blog_blogs WHERE hide='0'";
	$result = mysql_query($query) or die (mysql_error());
	while($row=mysql_fetch_row($result)){
		$links[]=array(
			"pageName"=>$row[1],
			"title"=>$row[1],
			"url"=>"$root/blog/view.php?blog=$row[0]"
		);
	}
	$dir = '/var/www/arcums/pages/';
	$files = scandir($dir);
	foreach ($files as $file){
		if(filetype($dir.$file)=='dir' && preg_match("/\d+_.*/",$file)){
		$links[]=array(
				"pageName"=>preg_replace("/\d+_/","",$file),
				"title"=>$file,
				"url"=>"$root/pages/$file"
			);
		}
	}	
    $list_html = "<ul>\n";
    
    //create the html for the link bar
    foreach ($links as $link) {
        $a = (strcmp($_SERVER['PHP_SELF'], $link['url']) != 0) ? "" : " class=\"current\"";
        $list_html.= "<li{$a}><a href=\"{$link['url']}\" title=\"{$link['title']}\">{$link['pageName']}</a></li>";
    }
    $list_html.= "</ul>\n";
    
    return $list_html;
}

function displayNav() {
    echo generateNav();
}
?>
    <div id="nav">
      <?php displayNav(); ?>
    </div>
		
    <div id="showBanner">
    	<?php include "addons/banner.php"; ?>
    </div>
  </div>

  <div id="content">
  	<div id="inner">
  		<div id="left">
  		  <div class="contentStrip"><span class="contentStripRight">&nbsp;</span></div>
  		  <div class="content">
