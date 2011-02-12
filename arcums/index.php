<?php
session_start();
include("../config.php");
$_SESSION['currentpage'] = $_SERVER['REQUEST_URI'];
?>
<meta HTTP-EQUIV="REFRESH" content="0; url=<?php echo $root ?>/arcums/playlist/index.php">;


