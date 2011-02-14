<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" type="text/css" href="css/wupx.css" media="screen" />
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>WUPX 91.5 FM Radio X Marquette</title>
<? include ("config.php") ?>
<link rel="stylesheet" type="text/css" href="<? echo $root; ?>/css/wupx.css" media="screen" />

<div id="container">
  <div id="header">
		<div class="top"><span class="cornerTopLeft" style="display: none; vertical-align: top;">&nbsp;</span>&nbsp;</div>
  	
    <h1><span>Radio X 91.5 FM WUPX</span></h1>
  
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
        array(
            "pageName" => "DJ Blog",
            "title" => "Read our DJ blog",
            "url" => "$root/blog/view.php?type=0"
        ) ,
        array(
            "pageName" => "Schedule",
            "title" => "See our show schedule",
            "url" => "$root/schedule/index.php"
        ) ,
        array(
            "pageName" => "DJs",
            "title" => "View DJ profiles",
            "url" => "$root/djs.php"
        ) ,
        array(
            "pageName" => "Staff",
            "title" => "View E Staff profiles and office hours",
            "url" => "$root/estaff.php"
        ) ,
        array(
            "pageName" => "Media",
            "title" => "Check out our popular wallpapers and other media",
            "url" => "$root/media.php"
        ) ,
        array(
            "pageName" => "About Us",
            "title" => "About Radio X",
            "url" => "$root/about.php"
        ) ,
        array(
            "pageName" => "Contact Us",
            "title" => "Contact",
            "url" => "$root/contact.php"
        )
    );
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
    	<?php include "rotate.php"; ?>
    </div>
  </div>

  <div id="content">
  	<div id="inner">
  		<div id="left">
  		  <div class="contentStrip"><span class="contentStripRight">&nbsp;</span></div>
  		  <div class="content">
