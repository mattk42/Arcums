<?php 
header("Cache-control: private"); 
include("../include/config.php"); 
require("../include/functions.php");



// CHECK FOR CONFIRMATION CODE
if(isset($_POST['code']) && isset($_POST['email']))
{
$confirm_code = mysql_real_escape_string($_POST['code']);
$confirm_email = mysql_real_escape_string($_POST['email']);
if(mysql_num_rows(mysql_query("SELECT confirm_code, email FROM djs WHERE confirm_code = '$confirm_code' AND email = '$confirm_email'")) == 0)
{
echo "That confirmation code or e-mail is incorrect.  Please go back and try again.!";
} else {
echo "Confirmation & e-mail code is correct...<br>";





// CHECK FOR MATCHING PASSWORDS
    if ($_POST['pass1'] != $_POST['pass2']) { 
             echo "Your passwords don't match..."; 
        } else { 
echo "Your passwords match...<br><br>";




// UPDATE PASSWORD IN MYSQL
                $encryptpass = md5($_POST['pass1']); 
				$code = $_POST['code']; 
				$email = $_POST['email']; 
 				$random_confirm_code=md5(uniqid(rand())); 
                mysql_query("UPDATE djs SET encryptpass='$encryptpass' WHERE confirm_code='$code' AND email='$email' LIMIT 1") or die(mysql_error()); 
                mysql_query("UPDATE djs SET confirm_code='$random_confirm_code' WHERE confirm_code='$code' AND email='$email' LIMIT 1") or die(mysql_error()); 
						echo "Password Updated";







}
}
}
?> 