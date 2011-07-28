<?php
include ("config.php");
$_SESSION['currentpage'] = $_SERVER['REQUEST_URI'];
?>

<meta HTTP-EQUIV="REFRESH" content="0; url=<?php echo $root ?>/blog/view.php">;
