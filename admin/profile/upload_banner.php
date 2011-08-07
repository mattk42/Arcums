<html><head> 
<title>Upload Banner</title> 
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
<table><tr><td><h3>Upload Banner</td></tr></table>
Your banner must be less than 80 Kb in size and in .jpg, .gif or .jpeg format.<br /> 
Please create your banner at 425x75 pixels! <br /> <br /> Contact itdirector@wupx.com for support.  <br /><br /><b>NO NUDITY OR INAPPROPRITE BANNERS ALLOWED!</b>
<form action="changebannerparse.php" method="post" enctype="multipart/form-data"> 
<input type="hidden" name="MAX_FILE_SIZE" value="81920"> 
<input type="file" name="userbanner"><br /> <br /> 
<input type="submit" name="submit" value="Upload Photo"></form> 
</body></html> 