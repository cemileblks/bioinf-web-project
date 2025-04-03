<?php
session_start();
session_unset();
session_destroy();
header('location: https://bioinfmsc8.bio.ed.ac.uk/~s2756532/web_project/index.php');
exit;
?>
