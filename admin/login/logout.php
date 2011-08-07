<?php
session_start();
if(isset($_SESSION['dj_logged_in']) && isset($_SESSION['username']))
{
@session_destroy();
$_SESSION = array();
header("Refresh: 0;url=index.php");
}
?> 
