<?php
session_start();
require ("../../config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require ("../include/version.php"); ?></title>
</head>

<body>

<?

if (isset($_SESSION['dj_logged_in'])) {
    include ("../include/header.php");
    echo '<link href="../css/arcums.css" rel="stylesheet" type="text/css" />';
    require_once ("../include/functions.php");
    require_once ('../include/catalog_functions.php');
}
else {
    include ("../../header.php");
    echo '<link href="" rel="stylesheet" type="text/css"';
}
$page_limit = 300;
?>
<br>
<center>
<table class="headers"><tr><td>
<form method="GET" action="index.php" name='searchform'">
<?

if (isset($_GET['artist'])) {
    $s_artist = mysql_real_escape_string($_GET['artist']);
}
else {
    $s_artist = "";
}

if (isset($_GET['title'])) {
    $s_title = mysql_real_escape_string($_GET['title']);
}
else {
    $s_title = "";
}

if (isset($_GET['track'])) {
    $s_track = mysql_real_escape_string($_GET['track']);
}
else {
    $s_track = "";
}
?>
<center>
Artist: <input class='searchbox' type="text" name="artist" value="<? echo $s_artist; ?>" onChange="document.searchform.submit()">  Title: <input class='searchbox' type="text" name="title" value="<? echo $s_title; ?>" onChange="document.searchform.submit()"><br>  <center>Track Title: <input class='searchbox' type="text" name="track" value="<? echo $s_track; ?>" onChange="document.searchform.submit()">
</center>
Category: <select name='cat' onChange="document.searchform.submit()">
<?
echo "<option value='%'>Any</option>";
$res = mysql_query("SELECT DISTINCT(name), id FROM catalog_categories ORDER BY name ASC") or die(mysql_error());

while ($row = mysql_fetch_array($res)) {
    echo $row[1];
    
    if ($row[1] == $_GET['cat']) {
        $selected = "SELECTED";
    }
    else {
        $selected = "";
    }
    echo $selected;
    echo "<option value='$row[1]' $selected>$row[0]</option>";
} //end while


?>
<input type='submit' value='Submit'>
</select>
</center>
</form>
</td></tr></table>



<?

if (isset($_GET['artist']) || isset($_GET['title'])) {
    
    if (isset($_GET['start'])) {
        $start = mysql_real_escape_string($_GET['start']);
        
        if ($start == "") {
            $start = "0";
        }
    }
    else {
        $start = "0";
    }
    
    if (isset($_GET['artist'])) {
        $artist = mysql_real_escape_string($_GET['artist']);
    }
    else {
        $artist = "%";
    }
    
    if (isset($_GET['title'])) {
        $title = mysql_real_escape_string($_GET['title']);
    }
    else {
        $title = "%";
    }
    
    if (isset($_GET['track'])) {
        $track = mysql_real_escape_string($_GET['track']);
    }
    else {
        $track = "";
    }
    
    if (isset($_GET['cat'])) {
        $cat_id = mysql_real_escape_string($_GET['cat']);
    }
    else {
        $cat_id = "%";
    }
    
    if (isset($_GET['dir']) && $_GET['dir'] == "DESC") {
        $dir = mysql_real_escape_string($_GET['dir']);
        $odir = "ASC";
    }
    else {
        $dir = "ASC";
        $odir = "DESC";
    }
    
    if (isset($_GET['order'])) {
        $order = mysql_real_escape_string($_GET['order']);
        
        if ($order == 2) {
            $order_s = "artist";
        }
        else 
        if ($order == 3) {
            $order_s = "title";
        }
        else {
            $order_s = "vinyl $dir, vinyl_letter $dir, category_id $dir, category_number";
        }
    }
    else {
        $order = 1;
        $order_s = "vinyl $dir, vinyl_letter $dir, category_id $dir, category_number";
    }
    $bg = "<TR bgcolor=\"#ededed\" onmouseover=\"style.background='#FFFF00';\" onmouseout=\"style.background='#ededed';\">\n";

    //$query is the query for the albums themselvs, c_query is the query for the total count
    $query = "SELECT DISTINCT * FROM catalog_albums  WHERE artist LIKE '%" . $artist . "%' AND title LIKE '%" . $title . "%' AND category_id LIKE '" . $cat_id . "' ORDER BY " . $order_s . " " . $dir . " LIMIT " . $start . "," . $page_limit;
    $c_query = "SELECT DISTINCT * FROM catalog_albums  WHERE artist LIKE '%" . $artist . "%' AND title LIKE '%" . $title . "%' AND category_id LIKE '" . $cat_id . "'";
    $list_tracks = false;
    
    if ($track != "") {
        $query = "SELECT DISTINCT catalog_albums.* , catalog_tracks.position, catalog_tracks.title FROM catalog_tracks INNER JOIN catalog_albums ON catalog_albums.id=catalog_tracks.album_id WHERE catalog_albums.artist LIKE '%" . $artist . "%' AND catalog_albums.title LIKE '%" . $title . "%' AND catalog_tracks.title LIKE '%" . $track . "%' AND catalog_albums.category_id LIKE '" . $cat_id . "'ORDER BY catalog_albums." . $order_s . " " . $dir . " LIMIT " . $start . "," . $page_limit;
        $c_query = "SELECT DISTINCT catalog_albums.* , catalog_tracks.position, catalog_tracks.title FROM catalog_tracks INNER JOIN catalog_albums ON catalog_albums.id=catalog_tracks.album_id WHERE catalog_albums.artist LIKE '%" . $artist . "%' AND catalog_albums.title LIKE '%" . $title . "%' AND catalog_tracks.title LIKE '%" . $track . "%' AND catalog_albums.category_id LIKE '" . $cat_id . "'";
        $list_tracks = true;
    }
    $album_query = mysql_query($query) or die(mysql_error());
    $c = mysql_query($c_query) or die(mysql_error());
    $total = mysql_num_rows($c);
    $dir_change = "<a href=\"?artist=" . $artist . "&title=" . $title . "&track=" . $track . "&cat=" . $cat_id . "&start=" . $start . "&order=" . $order . "&dir=" . $odir . "\"><img width='15' src='../images/$dir.png'></a>";
    echo "<table cellspacing='0' cellpadding='10'><tr class='headers' bgcolor='black'>";
    echo "<td> <a href=\"?artist=" . $artist . "&title=" . $title . "&track=" . $track . "&cat=" . $cat_id . "&start=" . $start . "&order=1 \"><b>Catalog Number</b></a>  </td><td>  <a href=\"?artist=" . $artist . "&title=" . $title . "&track=" . $track . "&cat=" . $cat_id . "&start=" . $start . "&order=2 \"><b>Artist</b></a> </td><td> <a href=\"?artist=" . $artist . "&title=" . $title . "&track=" . $track . "&cat=" . $cat_id . "&start=" . $start . "&order=3 \"><b>Album</b></a> </td><td align='right'>" . $dir_change . "</td></tr>";
    
    while ($row = mysql_fetch_array($album_query)) {
        
        if ($bg == "<TR bgcolor=\"#ededed\" onmouseover=\"style.background='#FFFF00';\" onmouseout=\"style.background='#ededed';\"") {
            $bg = "<TR bgcolor=\"#84C8FF\"  onmouseover=\"style.background='#FFFF00';\" onmouseout=\"style.background='#84C8FF';\"";
        }
        else {
            $bg = "<TR bgcolor=\"#ededed\" onmouseover=\"style.background='#FFFF00';\" onmouseout=\"style.background='#ededed';\"";
        }
        echo $bg . "onclick=\"DoNav('" . "view.php?id=" . $row['ID'] . "');\">\n";
        $cat_query = mysql_query("SELECT prefix FROM catalog_categories WHERE id='" . $row['category_id'] . "'");
        echo "<td>";
        $cat_result = mysql_fetch_array($cat_query);
        
        if ($row[14] == 1) {
            $v = " V-";
        }
        else {
            $v = "";
        }
        echo "<b><a href=\"view.php?id=" . $row['ID'] . "\"><font color='blue'>" . $cat_result['prefix'] . $v . $row['vinyl_letter'] . " " . $row['category_number'] . "<font></a></b></td><td><font color=black>" . $row['artist'] . "</font></td><td><font color='black'>" . $row[4] . "</font>";
        
        if ($list_tracks) {
            echo "<br><font size='1'>" . $row['position'] . ".) " . $row['title'] . "</font>";
        }
        echo "</td>";
        
        if (isset($_SESSION['dj_logged_in'])) {
            echo "<td><a href=report.php?id=" . $row['ID'] . "><img alt='Report' width='20' src='../images/uhoh.png'></a> ";
            
            if ($user_info['permissions'] >= 2) {
                echo "<a href=edit.php?id=" . $row['ID'] . "><img alt='Edit' width='20' src='../images/edit-icon.png'></a> ";
                echo " <a href=remove.php?id=" . $row['ID'] . "><img alt='Delete' width='20' src='../images/delete-icon.png'></a>";
            }
            echo "</td>";
            echo "</tr></font>";
        }
        else {
            echo "<td></td></tr></font>";
        }
    }
    $shown = $start + $page_limit;
    $back = $start - $page_limit;
    $cur_page = $shown / $page_limit;
    $pages = ceil($total / $page_limit);
    
    if ($pages > 10) {
        $first = $cur_page - 4;
        $last = $cur_page + 4;
        
        if ($first < 0) {
            $last = ($last - ($first));
        }
        
        if ($last < 0) {
            $first == $first + $last;
        }
    }
    else {
        $first = 0;
        $last = $pages;
    }

    //echo "On Page:".$cur_page." OF:".ceil($pages);
    echo "<tr align='center' class='headers'><td colspan='4'>";
    
    if ($start > 0) {

        //echo "[".$title."]";
        echo "<a href=\"?artist=" . $artist . "&title=" . $title . "&track=" . $track . "&cat=" . $cat_id . "&start=" . $back . "&order=" . $order . "&dir=" . $dir . "\"><-Back      </a>     ";
    }
    
    if ($pages > 1) {
        
        for ($p = 1;$p <= $pages;$p++) {
            
            if ($p < 3 || $p >= $pages - 3 || ($p > $first && $p < $last)) {
                
                if ($cur_page == $p) {
                    echo "<font size='5'>";
                }
                echo "&nbsp<a href=\"?artist=" . $artist . "&title=" . $title . "&track=" . $track . "&cat=" . $cat_id . "&start=" . $page_limit * ($p - 1) . "&order=" . $order . "&dir=" . $dir . "\">" . $p . "</a>";
                
                if ($cur_page == $p) {
                    echo "</font>";
                }
            }
            else {
                echo "&nbsp<a href=\"?artist=" . $artist . "&title=" . $title . "&track=" . $track . "&cat=" . $cat_id . "&start=" . $page_limit * ($p - 1) . "&order=" . $order . "&dir=" . $dir . "\">.</a>";
            }
        }
    }
    
    if ($shown < $total) {
        echo "<a href=\"?artist=" . $artist . "&title=" . $title . "&track=" . $track . "&cat=" . $cat_id . "&start=" . $shown . "&order=" . $order . "&dir=" . $dir . "\">      Forward-> </a>";
    }
    
    if (($start + $page_limit) > $total) {
        $end = $total;
    }
    else {
        $end = ($start + $page_limit);
    }
    echo "<br><font size='1'>Showing: " . $start . " - " . $end . " of " . $total . "</font>";
    echo "</tr><td></table></center>";
}

if (!isset($_SESSION['dj_logged_in'])) {
    include ("../../footer.php");
}
?>
