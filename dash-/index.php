<?php
//start the session
session_start();

//start output buffering
ob_start();

if(!isset($_SESSION['user'])){
		?>
		<script language="javascript">alert("Please Login to continue!");</script>
		<?php
		header("location:../index.php");
		exit();
	}else{
		header("location:dashboard.php");
	}
 
ob_end_flush();
//session_destroy();
?>