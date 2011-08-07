<html><head> 
<title>Change Profile Password</title> 
</head>
<style>
body
{
background: #131313;
background-image: url('../images/bg.jpg');
font-family: Verdana, Arial;
font-weight: bold;
font-size: 9px;
color: #FFFFFF;
}
.text
{
font-family: Verdana, Arial;
font-weight: bold;
font-size: 11px;
color: #FFFFFF;
}
.header
{
font-family: Verdana, Arial;
font-weight: bold;
font-size: 14px;
color: #FFFFFF;
}
.register_box
{
border: 1px solid #323232;
background: #202020;
font-family: Verdana, Arial;
font-weight: bold;
font-size: 9px;
color: #FFFFFF;
}
a:link
{
color: c0c0c0;
}
a:visited
{
color: c0c0c0;
}
a:hover
{
color: 00ff00;
}
</style>
<body>
<table><tr><td><div class="header">Change Account Settings</td></tr></table> 
Fill in only the fields you would like to change.  If you're going to change your password, it can only consist of alphanumeric characters (A-z,a-z,0-9) and must be between 6 and 16 characters long. 
<form action="changepasswordparse.php" method="post"> 
<table><tr><td>
<div class="text">Old Password: </td><td><input type="password" name="pass" size="20"><br /> </td></tr>
<tr><td><div class="text">New Password: </td><td><input type="password" name="pass1" size="20"><br />  </td></tr>
<tr><td><div class="text">New Password (again): </td><td><input type="password" name="pass2" size="20"><br /> 
</tr></td></table>
<input type="submit" value="Update Account" name="submit">

</form> 
</body></html> 