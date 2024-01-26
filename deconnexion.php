<?php
session_start();
session_destroy();
header("Location: page_acc.php");
exit();
?>
