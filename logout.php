<?php


session_start();

ob_start();


session_destroy();


header("location:index.php");


ob_end_flush();
?>