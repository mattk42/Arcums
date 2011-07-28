<?php
include("header.php");
require_once("config.php");
$contact_info = "<p>Radio X is located in the University Center on NMU's campus</p><p>Radio X<br>
1204 University Center<br>
Marquette, MI 49855</p>\n\n";
$contact_info.= "<p>Use the form below to contact a member of our staff, or call us on the phone:</p>\n\n";
$contact_info.= "<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Music Director --</b> 227-1845\n\n
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Office --</b> 227-1844\n\n
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Request Line --</b> 227-2348</p>\n\n";
$contact_info.= "<p>Staff office hours are also available on the ".
	  	"<a href=\"http://www.wupx.com/estaff.php\">staff</a> page.</p>\n\n";
$contact_info.= "<p><i>Fields marked with an asterisk (<span class=\"alert\">*</span>) are required.</i></p>\n\n";

$query = "SELECT name,email,position FROM accounts WHERE staff='1'";
$staff = mysql_query($query);
echo "Email:<br>";
while($row = mysql_fetch_row($staff)){
        echo "<a href=mailto:$row[1]>$row[2] ($row[0])</a>";
}

include("footer.php");
?>
