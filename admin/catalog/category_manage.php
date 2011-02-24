<?php
include ("../../config.php");
?>
<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
<?php
include ("../include/header.php");

function show_table() {
    global $root;
    
    if (isset($_GET['ord'])) {
        $ord = mysql_real_escape_string($_GET['ord']);
    }
    else {
        $ord = 'prefix';
    }
    
    if ($_GET['dir'] == "ASC") {
        $dir = 'ASC';
        $dir_link = 'DESC';
    }
    else {
        $dir = 'DESC';
        $dir_link = 'ASC';
    }

    //DISPLAY ADD FORM
    echo "<center><form name=add>Prefix:<input name='prefix' size='2'>Name:<input name='name'><input type='submit' value='add'></form></center>";

    //DISPLAY A TABLE OF THE USERS.
    $query = "SELECT id, prefix,name FROM catalog_categories ORDER BY $ord $dir";
    $res = mysql_query($query) or die(mysql_error());
    echo "<center><table class='usertablemain'><tr class='headers'><td><a href=?ord=prefix&dir=$dir>Prefix</a></td><td><a href=?ord=name&dir=$dir>Name</td><td><a href=?ord=$ord&dir=$dir_link>$dir</a></td></tr>";
    
    while ($row = mysql_fetch_assoc($res)) {
        echo "<tr>";
        echo "<td>" . $row['prefix'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td><a href='?del=" . $row['id'] . "'>Delete</a> ";
        echo "</td></tr>";
    }
    echo "</table></center>";
}

if (isset($_GET['prefix'])) {

    //ADD A NEW CATEGORY
    $pre = mysql_real_escape_string($_GET['prefix']);
    $name = mysql_real_escape_string($_GET['name']);
    $query = "INSERT INTO catalog_categories (prefix,name) VALUES('$pre','$name')";
    $res = mysql_query($query) or die(mysql_error());
    
    if (mysql_affected_rows() < 1) {
        echo "Query failed to modify any rows";
    }
    else {
        echo "Category Added";
    }
    show_table();
}
else 
if (isset($_GET['del'])) {

    //DELETE THE CATEGORY
    $id = mysql_real_escape_string($_GET['del']);
    $query = "DELETE FROM catalog_categories WHERE id=$id";
    $res = mysql_query($query) or die(mysql_error());
    
    if (mysql_affected_rows() < 1) {
        echo "Query failed to modify any rows";
    }
    else {
        echo "Category Deleted";
    }
    show_table();
}
else {
    show_table();
}
?>
