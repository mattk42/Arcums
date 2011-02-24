<link href="../../themes/<?php echo $curtheme;?>/admin.css" rel="stylesheet" type="text/css" />
<?php
require("../include/config.php"); 


// Random confirmation code 
$confirm_code=md5(uniqid(rand())); 

// values sent from form 
$email=$_GET['email'];


// Insert data into database 

$sql="UPDATE djs SET confirm_code = '$confirm_code' WHERE email = '$email'";
$result=mysql_query($sql);

// if suceesfully inserted data into database, send confirmation link to email 
if($result){

// ---------------- SEND MAIL FORM ----------------

// send e-mail to ...
$to=$email;

// Your subject
$subject="WUPX Password Reset Request";

// From
$header="from: WUPX-FM <itdirector@wupx.com>";

// Your message
$message="Someone, hopefully you, has requested to reset your password. \r\n";
$message.="Click on this link to reset your password \r\n";
$message.="http://www.wupx.com/arcums/login/forgot_password_change.php?code=$confirm_code";

// send email
$sentmail = mail($to,$subject,$message,$header);

}

// if not found 
else {
echo '

<br><br><br><br><br><br><br><br>

<table width="448" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3"><img src="images/login_01.png" width="448" height="81" /></td>
  </tr>
  <tr>
    <td width="33"><img src="images/login_02.png" /></td>
    <td width="209" valign="top" background="images/login_03.png">
    


Address not found...<br><br>
<a href="forgot_password.php">[try again]</a>


  </td>
    <td width="212"><div align="right"><img src="images/login_04.png" /></div></td>
  </tr>
  <tr>
    <td colspan="3"><img src="images/login_05.png" /></td>
  </tr>
</table>

';
}

// if your email succesfully sent
if($sentmail){
echo '
<br><br><br><br><br><br><br><br>

<table width="448" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3"><img src="images/login_01.png" width="448" height="81" /></td>
  </tr>
  <tr>
    <td width="33"><img src="images/login_02.png" /></td>
    <td width="209" valign="top" background="images/login_03.png">
    


Your confirmation link has been sent to your e-mail address.<br><br>
<a href="index.php">[login]</a>


  </td>
    <td width="212"><div align="right"><img src="images/login_04.png" /></div></td>
  </tr>
  <tr>
    <td colspan="3"><img src="images/login_05.png" /></td>
  </tr>
</table>
';
}
else {
echo '

<br><br><br><br><br><br><br><br>

<table width="448" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3"><img src="images/login_01.png" width="448" height="81" /></td>
  </tr>
  <tr>
    <td width="33"><img src="images/login_02.png" /></td>
    <td width="209" valign="top" background="images/login_03.png">
    


Address not found...<br><br>
<a href="forgot_password.php">[try again]</a>


  </td>
    <td width="211"><div align="right"><img src="images/login_04.png" /></div></td>
  </tr>
  <tr>
    <td colspan="3"><img src="images/login_05.png" /></td>
  </tr>
</table>

';
}

?>

