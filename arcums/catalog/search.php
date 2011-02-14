<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php require ("../include/version.php"); ?></title>
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
require_once ("../../config.php");
require_once ("../include/functions.php");
include ("../include/header.php");
echo "<center>";

if ($user_info['permissions'] >= 2) {
    global $amazon_public_key, $amazon_private_key;
    
    if (isset($_GET['UPC'])) {
        require_once ("../include/catalog_functions.php");
        $upc = mysql_real_escape_string($_GET['UPC']);

        //get ready for adding to database
        
        if (isset($_GET['submit_add'])) {
            $insert = true;
            $cat = mysql_real_escape_string($_GET['cat']);
        }
        else {
            $insert = false;
        }
        
        if (isset($_GET['char'])) {
            $cat_letter = mysql_real_escape_string($_GET['char']);
        }
        else {
            $cat_letter = "";
        }
        
        if (isset($_GET['page'])) {
            $page = mysql_real_escape_string($_GET['page']);
        }
        else {
            $page = 1;
        }

        //echo $page;
        $pxml = aws_signed_request("com", array(
            "Operation" => "ItemSearch",
            "SearchIndex" => "Music",
            "Keywords" => $upc,
            "ItemPage" => $page,
            "ResponseGroup" => "Large"
        ) , $amazon_public_key, $amazon_private_key);

        //$pxml = aws_signed_request("com", array("Operation"=>"ItemLookup","ItemId"=>"B000I2IR46","ResponseGroup"=>"Large"), $public_key, $private_key);
        //print_r ($pxml);

        
        if ($pxml === False) {
            echo "Album not found";
        }
        else {

            //If there is more than one result from the search, show table to choose from.
            
            if (sizeof($pxml->Items->Item) > 1) {
                echo "<center><table class='addtablemain' cellpadding='0' border='0' width='800'><tr>";
                $count = 0;
                
                foreach ($pxml->Items->Item as $album) {
                    
                    if ($album->ItemAttributes->UPC != "") {
                        
                        if ($count % 2 == 0) {
                            echo "<tr>";
                        }
                        $count+= 1;
                        
                        if ($pxml->Items->Item->LargeImage->URL != "") {
                            $image = $album->LargeImage->URL;
                        }
                        else {
                            $image = '../images/CD.png';
                        }
                        echo "<td width='400'><center><a href='search.php?UPC=" . $album->ItemAttributes->UPC . "'><img width='150' src='" . $image = $album->LargeImage->URL . "'></a><br>";
                        echo $album->ItemAttributes->Artist . "<br>" . $album->ItemAttributes->Title . "</center></td>";
                        
                        if ($count % 2 == 0) {
                            echo "</tr>";
                        }
                    }
                }
                echo "<tr><td colspan='2' align='center'>";
                
                if ($page > 1) {
                    $ppage = $page - 1;
                    echo "<a href='search.php?UPC=" . $upc . "&page=" . $ppage . "'><--- Previous Page</a>";
                }
                
                if ($page < $pxml->Items->TotalPages) {
                    $npage = $page + 1;
                    echo "<a href='search.php?UPC=" . $upc . "&page=" . $npage . "'>   Next Page ---></a><br>";
                }
                echo "</td></tr></table>";
            }
            else {
                
                if (isset($pxml->Items->Item->ItemAttributes->Title)) {
                    $image = $pxml->Items->Item->LargeImage->URL;
                    $artist = $pxml->Items->Item->ItemAttributes->Artist;
                    $title = $pxml->Items->Item->ItemAttributes->Title;
                    $label = $pxml->Items->Item->ItemAttributes->Label;
                    $discs = $pxml->Items->Item->ItemAttributes->NumberOfDiscs;
                    $release = $pxml->Items->Item->ItemAttributes->ReleaseDate;
                    $upc2 = $pxml->Items->Item->ItemAttributes->UPC;
                    $desc = "";
                    
                    if (!$insert) {

                        //$pxml->Items->Item->EditorialReviews->EditorialReview->Content;
                        echo "<center><table class='catalogtablemain'><tr><td align='center'>";
                        echo '<img src="' . $image . '"></img><br></td><td>';
                        echo "<b>ARTIST:</b> ", $artist, "<br>";
                        echo "<b>TITLE:</b> ", $title, "<br>";
                        echo "<b>LABEL:</b> ", $label, "<br>";
                        echo "<b>DISCS:</b> ", $discs, "<br>";
                        echo "<b>RELEASE:</b> ", $release, "<br>";
                        echo "<b>UPC:</b> ", $upc2, "<br>";

                        //echo "<b>Description:</b> ",$desc,"<br>";
                        echo "<br><br>";
                    } //end if

                    else {
                        echo "Adding Album";

                        //insert album into db
                        
                        if ($cat != 5) {
                            $res = mysql_query("SELECT MAX(Category_Number) FROM catalog_albums WHERE Category_ID='" . $cat . "'") or die(mysql_error());
                            $cat_letter = '';
                        }
                        else {
                            $res = mysql_query("SELECT MAX(Category_Number) FROM catalog_albums WHERE Category_ID='" . $cat . "' AND vinyl_letter='" . $cat_letter . "'") or die(mysql_error());
                        }
                        $row = mysql_fetch_row($res);
                        $cat_num = $row[0];
                        $cat_num++;
                        $query = "INSERT INTO catalog_albums VALUES('','" . mysql_real_escape_string($cat) . "','" . mysql_real_escape_string($cat_num) . "','" . $cat_letter . "','" . mysql_real_escape_string($title) . "','" . mysql_real_escape_string($artist) . "','" . mysql_real_escape_string($label) . "','" . mysql_real_escape_string($upc) . "','" . mysql_real_escape_string($release) . "','" . mysql_real_escape_string($discs) . "','" . mysql_real_escape_string($image) . "','" . mysql_real_escape_string($desc) . "','',NOW(),'0','" . $user_info['username'] . "[AMAZON_ADD];" . "','')";
                        echo $query;
                        mysql_query($query) or die(mysql_error());

                        //Get the albumid that was used during the input, for track input
                        $result = mysql_query("SELECT ID FROM catalog_albums WHERE UPC='" . $upc . "' AND Category_ID='" . $cat . "' AND Category_Number='" . $cat_num . "'") or die(mysql_error());

                        //echo "SELECT ID FROM albums WHERE UPC='" . $upc . "' AND Category_ID='" . $cat . "' AND Category_Number='" . $cat_num . "'";
                        $row = mysql_fetch_row($result);
                        $album_id = $row[0];
                    } //end else

                    //echo sizeof($pxml->Items->Item->Tracks->Disc)."<----";

                    
                    if (sizeof($pxml->Items->Item->Tracks->Disc) > 0) {
                        
                        foreach ($pxml->Items->Item->Tracks->Disc as $disc) {
                            $disc_number = $disc->attributes()->Number;
                            
                            if (!$insert) {
                                echo "<b>Disc:</b> " . $disc_number . "<br>";
                            }
                            
                            foreach ($disc->Track as $track) {
                                $track_number = $track->attributes()->Number;
                                
                                if (!$insert) {
                                    echo "<b>" . $track_number . ".</b> " . $track . "<br>";
                                }
                                else {
                                    $len = "00:00";
                                    mysql_query("INSERT INTO catalog_tracks(album_id,position,title,cd_number,length) VALUES('" . $album_id . "','" . mysql_real_escape_string($track_number) . "','" . mysql_real_escape_string($track) . "','" . mysql_real_escape_string($disc_number) . "','" . mysql_real_escape_string($len) . "')") or die(mysql_error());

                                    //echo "INSERT INTO tracks(album_id,position,title,cd_number,length) VALUES('" . $album_id . "','" . mysql_real_escape_string($track_number) . "','" . mysql_real_escape_string($track) . "','" . mysql_real_escape_string($disc_number) . "','" . mysql_real_escape_string($len) . "')";
                                    
                                } //end else

                                
                            } //end foreach

                            
                        } //end foreach

                        
                    } //end if

                    else {
                        echo "No Tracks Found<br>";
                    }
                } //end if xml isset

                else {
                    echo "Could not find item.\n";
                } //end else

                
                if (!$insert) {
                    echo "<br><form method='get' action='search.php'>
					Category: <select name='cat'>";
                    $res = mysql_query("SELECT DISTINCT(name), id FROM catalog_categories") or die(mysql_error());
                    
                    while ($row = mysql_fetch_row($res)) {
                        echo "<option value='$row[1]'>$row[0]</option>";
                    } //end while

                    echo "</select>
					<input type='hidden' name='submit_add' value='1'>
					<input type='hidden' name='UPC' value='" . $upc . "'>
					<input type='text' size='1' onChange='javascript:this.value=this.value.toUpperCase().trim();' autocomplete='off' maxlength='1' name='char'>
					<input type='submit' value='Add'>
					</form>";
                } //end if

                else {
                    echo "<meta HTTP-EQUIV='REFRESH' content='0; url=$root/arcums/catalog/view.php?id=" . $album_id . "'>";
                }
            } //end else

            
        } //end else

        //show the category dropdown and add button

        
    }
    else {
        echo '<form method="GET" action=search.php>UPC or Keywords:<input type="text" size="12"  name="UPC"><input type="submit" value="submit"></form>';
    }
    echo "</center>";
}
else {
    echo '<meta HTTP-EQUIV="REFRESH" content="0; url=../login">';
}
?>
