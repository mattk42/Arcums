<?php
include("../header.php");
require_once("../admin/include/functions.php");

echo "<center>";

if (isset($_POST['date'])) {
    $cpdate = $_POST['date'];
}
else {
    $cpdate = mktime(0, 0, 0, date('m') , date('d') , date('y'));
}
$cedate = date('Y-m-d', mktime(0, 0, 0, date('m', $cpdate) , date('d', $cpdate) + 1, date('y', $cpdate)));
$csdate = date('Y-m-d', mktime(0, 0, 0, date('m', $cpdate) , date('d', $cpdate) , date('y', $cpdate)));
echo "<form name=dateSel action=charter.php method=POST>Date:<select name=date onchange=dateSel.submit()>";

for ($x = 0;$x < 7;$x++) {
    $ndate = mktime(0, 0, 0, date('m') , date('d') - $x, date('y'));
    
    if ($ndate == $cpdate) {
        echo "<option value='" . mktime(0, 0, 0, date('m') , date('d') - $x, date('y')) . "'selected>" . date('D (m/d)', mktime(0, 0, 0, date('m') , date('d') - $x, date('y'))) . "</option>";
    }
    else {
        echo "<option value='" . mktime(0, 0, 0, date('m') , date('d') - $x, date('y')) . "'>" . date('D (m/d)', mktime(0, 0, 0, date('m') , date('d') - $x, date('y'))) . "</option>";
    }
}
echo "</select></form>";
$query = "SELECT dj,artist, song, label, datetime as date FROM playlist where date between '$csdate' and '$csdate  24:59:59'";
$result = mysql_query($query) or die("Query failed.");
$numofrows = mysql_num_rows($result);
echo "<ul>\n";
echo "<table cellspacing=\"0\" cellpadding=\"2\" align=\"center\"'><tr bgcolor=black><td><b>DJ</b></td><td><b> ARTIST </b></td><td><b> SONG </b></td><td width=200><b> DATE </b></td></tr>";

if ($numofrows > 0) {
    
    for ($i = 0;$i < $numofrows;$i++) {
        $row = mysql_fetch_array($result);
        
        if ($i % 2) { //this means if there is a remainder

            echo "<TR bgcolor=\"#84C8FF\"  onmouseover=\"style.background='#FFFF00';\" onmouseout=\"style.background='#84C8FF';\" >\n";
        }
        else { //if there isnt a remainder we will do the else

            echo "<TR bgcolor=\"#EDEDED\" onmouseover=\"style.background='#FFFF00';\" onmouseout=\"style.background='#EDEDED';\">\n";
        }
        echo "<td> <font color = \"black\">$row[dj] </td><td> <font color = \"black\">$row[artist] </td><td> <font color = \"black\">$row[song] </td><td><font color = \"black\">$row[date] </td>";
    }
}
echo "</table>";
require ("../footer.php");
?>
</center>

</body>
</html>
