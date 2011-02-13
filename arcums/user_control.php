<link href="css/arcums.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function check_match(){
	var p1=document.forms["passchange"]["password"].value
	var p2=document.forms["passchange"]["password2"].value
	if (p1!=p2)
	  {
		  alert("Passwords do not match");
		  return false;
	  }
}
</script>
<?

//TODO: ADD ORDERING, CLEAR GET VARIABLES FROM THE URL
require_once ("../config.php");
include ("include/header.php");

function show_table() {
    global $root;

    //DISPLAY A TABLE OF THE USERS.
    $query = "SELECT id,username, is_activated, email, name, djname, permissions, staff, photo FROM accounts";
    $res = mysql_query($query) or die("Database Error (user_control:5)");
    echo "<center><table class='usertablemain'><tr class='headers'><td></td><td>Username</td><td>Name</td><td>Dj Name</td><td>Email</td><td>Active?</td><td>Staff?</td><td>Permissions</td><td></td></tr>";
    
    while ($row = mysql_fetch_assoc($res)) {
        echo "<tr>";
        echo "<td><img src='" . $root . "/arcums/profile/photos/" . $row['photo'] . "' height='70'></td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['djname'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['is_activated'] . "</td>";
        echo "<td>" . $row['staff'] . "</td>";
        echo "<td>" . $row['permissions'] . "</td>";
        echo "<td><a href='?pass=" . $row['id'] . "'>Reset Password</a> ";
        
        if ($row['is_activated'] == 0) {
            echo "<a href='?activate=" . $row['id'] . "'>Activate</a>";
        }
        else {
            echo "<a href='?deactivate=" . $row['id'] . "'>Deactivate</a>";
        }
        echo "</td></tr>";
    }
    echo "</table></center>";
}

//CHECK FOR REQUESTED ACTIONS, PERFORM IF REQUESTED

if (isset($_GET['deactivate'])) {
    $id = mysql_real_escape_string($_GET['deactivate']);
    $query = "UPDATE accounts SET is_activated=0 WHERE id=$id";
    $res = mysql_query($query) or die("Database Error");
    
    if (mysql_affected_rows() < 1) {
        echo "Query failed to modify any rows";
    }
    else {
        echo "User Deactivated";
    }
    show_table();
}
else if (isset($_GET['activate'])) {
    $id = mysql_real_escape_string($_GET['activate']);
    $query = "UPDATE accounts SET is_activated=1 WHERE id=$id";
    $res = mysql_query($query) or die("Database Error");
    
    if (mysql_affected_rows() < 1) {
        echo "Query failed to modify any rows";
    }
    else {
        echo "User Activated";
    }
    show_table();
}
else if (isset($_POST['idpass'])) {
    $id = mysql_real_escape_string($_POST['idpass']);
    $newpass = mysql_real_escape_string($_POST['password']);
    $query = "UPDATE accounts SET encryptpass=MD5($newpass) WHERE id = $id";
    mysql_query($query) or die("Error updating password");
    echo "Password succsefully changed";
    show_table();
}
else if (isset($_GET['pass'])) {
    $id = mysql_real_escape_string($_GET['pass']);
    $query = "select username from accounts where id = $id";
    $res = mysql_query($query) or die("Failed to select user");
    $row = mysql_fetch_assoc($res);
    echo "Changing password for user: <b>" . $row['username'] . "</b><br>";
    echo "<form name='passchange' method='POST' onsubmit='return check_match()' >New Password:<input type='password' name='password'></input><br>Re-enter New Password:<input type='password' name='password2'></input><br><input type='hidden' name='idpass' value='$id'><input type='submit'></form>";
}
else {
    echo "";
    show_table();
}
?>
