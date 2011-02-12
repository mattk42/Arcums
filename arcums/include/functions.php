<?php
function generateCode($length = 10)
{
$password="";
$chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
srand((double)microtime()*1000000);
for ($i=0; $i<$length; $i++)
{
$password = $password . substr ($chars, rand() % strlen($chars), 1);
}
return $password;
}


function username_exists($username)
{
$query = mysql_query("SELECT username FROM accounts WHERE username = '$username' LIMIT 1");
return (mysql_num_rows($query) > 0) ? TRUE : FALSE;
}


function is_valid_email($email) 
{
if(eregi("^[a-z0-9]+([-_.]?[a-z0-9])+@[a-z0-9]+([-_.]?[a-z0-9])+.[a-z]{2,4}", $email))
{
return TRUE;
} 
else 
{
return FALSE;
}
} 
?>
