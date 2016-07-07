<?php

	require_once('includes/header.php');
	require_once('includes/sidebar.php');
	
	require_once('../includes/config.php');
	require_once('../includes/db.php');
	require_once('../includes/functions.php');
	
	//start the session
	if(!isset($_COOKIE["PHPSESSID"]))
	{
	  session_start();
	}


	if(!isset($_SESSION['user'])){
		?>
		<script language="javascript">alert("Please Login to continue!");</script>
		<?php
		header("location:../index.php");
		exit();
	}


	//start output buffering
	
	ob_start();
	
	// $db_object->dump_messages();	

	head();
	sidebar();
	$active	=	0;
	$db_object	=	new db();
	 
$error=0;
if(isset($_GET['id']) && !empty($_GET))
{
	$_FIELDS	=	array("id"=>array("int",1,"ID"),"tb"=>array("text",1,"Category"));
	$out	=	sanatize_post($_GET,$_FIELDS);
	if(isset($out['error'])){
		$_SESSION['error'][]	=	$out['error'];
		$error=1;
	}else{
		
		$resource	=	$db_object->alter_single_sport_record($out['id'],$out['tb']);
		if($resource){
				$_SESSION['message'][]	=	"Status Change Successsfule for ".$out['tb']." with ID = ".$out['id'];
				$active=1;
			}
	}
	//exit();
}elseif($update==0){
	echo "No Id Found! Please Go back and try Again";
 	exit();
}else{
	$row['sport']			=	"";
	$row['parent']			=	"";
	$row['description']	=	"";
}

?>

<div class="row message-board">
<h2 class="text-center">Message Board</h2>
 <?php
 
 //print errors
  if(sizeof($_SESSION['error'])>1){
 echo '<label style="text-align:center;Color:red;background:#0F9;text-align: center;
    Color: red;
    background: #0F9;
    padding: 15px 15px 15px 15px;
    border: 1px solid red;
    border-radius: 5px;width:100%;">Error:'.$_SESSION['error'][sizeof($_SESSION['error'])-1].'</label><br/>';} 
 if(sizeof($_SESSION['message'])>1){
 echo '<label style="text-align:center;Color:red;background:#0F9;text-align: center;
    Color: green;
    background: #0F9;
    padding: 15px 15px 15px 15px;
    border: 1px solid blue;
    border-radius: 5px;width:100%;">Message:'.$_SESSION['message'][sizeof($_SESSION['message'])-1].'</label><br/>';} 

 if(sizeof($_SESSION['notification'])>1){
 echo '<label style="text-align:center;Color:green;background:#0F9;text-align: center;
    Color: blue;
    background: #0F9;
    padding: 15px 15px 15px 15px;
    border: 1px solid red;
    border-radius: 5px;width:100%;">Notification:'.$_SESSION['notification'][sizeof($_SESSION['notification'])-1].'</label><br/>';} 	?>
</div>

<?php 
if(($active==1) || $error==1){
	?>
   <a href="<?php echo $out['tb'];?>_manager.php">Click To See Listing </a>
   <?php
	exit();
	}
?>

  
<?php 
	$_SESSION['form_uid']	=	UID;
	ob_end_flush();
?>