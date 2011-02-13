<?php
session_start();
require("../../config.php");
require("../include/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ARCUMS 2.0</title>
<link href="../css/arcums.css" rel="stylesheet" type="text/css" />
</head>

<body>
<br><br><br><br><br><br><br><br>
<?php
if(!isset($_SESSION['dj_logged_in'])){
	if(!isset($_POST['check_login']))
	{
		echo '
		<table width="448" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td colspan="3"><img src="images/login_01.png" width="448" height="81" /></td>
		  </tr>
		  <tr>
		    <td width="33"><img src="images/login_02.png" /></td>
		    <td width="209" valign="top" background="images/login_03.png">
		    <form method="post" action="index.php">


		      <table width="100%" height="79" border="0" cellpadding="0" cellspacing="0">
			<tr>
			  <td colspan="2" valign="top"><div class="login" align="right">
			  <b>username</b>  <input name="username" type="text" id="username" size="13" />
			  </div></td>
			</tr>
			<tr>
			  <td colspan="2" height="22" valign="top"><div class="login" align="right">
			  <b>password</b> <input name="encryptpass" type="password" id="password" size="13" />
			  </div></td>
			</tr>
			 <tr>
			  <td height="26" valign="bottom"><div class="login"  align="right"><a href="forgot_password.php">[forgot password]</a> </div></td>
			  <td align="right" valign="bottom">


		  <button id="replacement-2" type="submit" name="check_login">&nbsp;</button>




		</td>
			</tr>
		      </table>
		 
		    </form>    </td>
		    <td width="211" ><div align="right"><img src="images/login_04.png" /></div></td>
		  </tr>
		  <tr>
		    <td colspan="3"><img src="images/login_05.png" /></td>
		  </tr>
		</table>
		';
		}
		elseif(isset($_POST['check_login'])){
			$username = mysql_real_escape_string($_POST['username']);
			$encryptpass = md5($_POST['encryptpass']);
			if(empty($_POST['encryptpass']) || empty($username))
		{
		echo '
		<table width="425" border="0" background="images/errorbg.png" align="center" cellpadding="0" cellspacing="0">
		<tr>
		<td align="center" valign="bottom">
		<img src="images/!.png"> You left a blank field.
		</td>
		</tr>
		</table>
		<table width="448" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td colspan="3"><img src="images/login_01.png" width="448" height="81" /></td>
		  </tr>
		  <tr>
		    <td width="33"><img src="images/login_02.png" /></td>
		    <td width="204" valign="top" background="images/login_03.png">
		    <form method="post" action="index.php">


		      <table width="100%" height="79" border="0" cellpadding="0" cellspacing="0">
			<tr>
			  <td  colspan="2" valign="top"><div align="right">
			    <input name="username" type="text" id="username" size="27" />
			  </div></td>
			</tr>
			<tr>
			  <td colspan="2" height="22" valign="top"><div align="right">
			    <input name="encryptpass" type="password" id="password" size="27" />
			  </div></td>
			</tr>
			 <tr>
			  <td height="26" valign="bottom"><div align="right"><a href="forgot_password.php">[forgot password]</a></div></td>
			  <td align="right" valign="bottom">


		  <button id="replacement-2" type="submit" name="check_login">&nbsp;</button>




		</td>
			</tr>
		      </table>
		 
		    </form>    </td>
		    <td width="212"><div align="right"><img src="images/login_04.png" /></div></td>
		  </tr>
		  <tr>
		    <td colspan="3"><img src="images/login_05.png" /></td>
		  </tr>
		</table>
		';
	}
	else{
		$check_login = mysql_query("SELECT username, encryptpass, id FROM accounts WHERE username = '$username' AND encryptpass = '$encryptpass' AND is_activated=1 LIMIT 1") or die("Database Error (/arcums/index.php, 130)");
		if(mysql_num_rows($check_login) > 0){
			$row = mysql_fetch_assoc($check_login);
			$query = "UPDATE accounts SET last_login = NOW() WHERE id =". $row['id'];
			mysql_query($query) or die("Failed to update last login time");
			$_SESSION['dj_logged_in'] = 1;
			$_SESSION['username'] = $username;

			echo '<meta HTTP-EQUIV="REFRESH" content="0; url=';echo $_SESSION['currentpage'];echo '">';
		}
	else{
		echo'<table width="425" border="0" background="images/errorbg.png" align="center" cellpadding="0" cellspacing="0">
		<tr>
		<td align="center" valign="bottom">';
		$check_login = mysql_query("SELECT username, encryptpass, id FROM accounts WHERE username = '$username' AND encryptpass = '$encryptpass' LIMIT 1") or die("Database Error (/arcums/index.php, 130)");
		if(mysql_num_rows($check_login) > 0){
			echo '<img src="images/!.png"> User is not activated';
		}
		else{
			echo '<img src="images/!.png"> Wrong Password or user doesn\'t exist.';
		}
		echo '
		</td>
		</tr>
		</table>
		<table width="448" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
		    <td colspan="3"><img src="images/login_01.png" width="448" height="81" /></td>
		  </tr>
		  <tr>
		    <td width="33"><img src="images/login_02.png" /></td>
		    <td width="204" valign="top" background="images/login_03.png">
		    <form method="post" action="index.php">


		      <table width="100%" height="79" border="0" cellpadding="0" cellspacing="0">
			<tr>
			  <td  colspan="2" valign="top"><div align="right">
			    <input name="username" type="text" id="username" size="27" />
			  </div></td>
			</tr>
			<tr>
			  <td colspan="2" height="22" valign="top"><div align="right">
			    <input name="encryptpass" type="password" id="password" size="27" />
			  </div></td>
			</tr>
			 <tr>
			  <td height="26" valign="bottom"><div align="right"><a href="forgot_password.php">[forgot password]</a> </div></td>
			  <td align="right" valign="bottom">


		  <button id="replacement-2" type="submit" name="check_login">&nbsp;</button>




		</td>
			</tr>
		      </table>
		 
		    </form>    </td>
		    <td width="212"><div align="right"><img src="images/login_04.png" /></div></td>
		  </tr>
		  <tr>
		    <td colspan="3"><img src="images/login_05.png" /></td>
		  </tr>
		</table>

		';
	}
}
}
}
else
{
echo '
<table width="448" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3"><img src="images/login_01.png" width="448" height="81" /></td>
  </tr>
  <tr>
    <td width="33"><img src="images/login_02.png" /></td>
    <td width="209" valign="top" background="images/login_03.png">
   You\'re logged in as:<br><b>'; echo $_SESSION['username']; echo ' </b> <a href="logout.php">[logout]</a> 
<br><br>
<a href="../playlist">Continue to ARCUMS...</a>

</td>
    <td width="212"><div align="right"><img src="images/login_04.png" /></div></td>
  </tr>
  <tr>
    <td colspan="3"><img src="images/login_05.png" /></td>
  </tr>
</table>



';
}
?>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=1,scrollbars=0,location=0,statusbar=1,menubar=0,resizable=1,width=300,height=100,left = 312,top = 309');");
}
// End -->
</script>
</body>
</html>
