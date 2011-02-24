<html><head> 
<title>Change Profile Photo</title> 
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
<table><tr><td><h3>Change Profile Photo</td></tr></table>
Your photo must be less than 80 Kb in size and in .jpg, .gif or .jpeg format.<br /> 
Please resize your photo to around a width of 125px and height 150px. Your photo will looked squished if you fail to do so. <br /> <br /> Contact itdirector@wupx.com for support.  <br /><br /><b>NO NUDITY OR INAPPROPRITE PHOTOS ALLOWED!</b>
<form action="changephotoparse.php" method="post" enctype="multipart/form-data"> 
<input type="hidden" name="MAX_FILE_SIZE" value="81920"> 
<input type="file" name="userphoto"><br /> <br /> 
<input type="submit" name="submit" value="Upload Photo"></form> 
</body></html> 