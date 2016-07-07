<?php

session_start();

define("REMOTEIP",$_SERVER['REMOTE_ADDR']);


define("UID", uniqid());


define("MAX_LOGIN_ATTEMPT", 35);


$_SESSION['notification']	=	array("");
$_SESSION['message']			=	array("");
$_SESSION['error']			=	array("");
?>