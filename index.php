<?php
require_once('includes/db.php');
require_once('includes/config.php');
require_once('includes/functions.php');

//start the session
if(!isset($_COOKIE["PHPSESSID"]))
{
  session_start();
}

//turn on ourput buffering
ob_start();

$status	=	"";
if(isset($_POST) && !empty($_POST)){

		//set up connection to database
		$db_object	=	new db();

		//set login attempt and register the ip address
		if(!isset($_SESSION['login_attempt'])){
				$_SESSION['login_attempt']	=	1;
				$db_object->register_ip('Login Attempt');
			}else{
				$_SESSION['login_attempt'] += 1;
				if($_SESSION['login_attempt'] > MAX_LOGIN_ATTEMPT){
						echo "Maximum Login Attempts! Please Try After Some time!";
						$db_object->log_message("Maximum Login Attempts! Please Try After Some time!");
						exit();
					}
			}

		//Sanatize the post values
		$values	=	array("uname"	=>		array("text",1),
								"pass"	=>		array("text",1)
								);

		//filter the post values
		$post	=	sanatize_post($_POST,$values);

		// authenticate the user 
		$auth	=	$db_object->auth($post['uname'],$post['pass']);


		//perform the action
		if(!($auth)){
				   $db_object->dump_messages();
					$db_object->dump_errors();
					$status	=	"<br/>Authentication Failed!....Please Try Again";
			}else{
					$_SESSION['user']				=	$auth;
					$_SESSION['user']['uid']	=	UID;
					print_r($_SESSION);

					header("location:dash-/dashboard.php");
			}
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Rural Games Organization of India</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

<div class="container">
<div class="row margin-top-15">
   <div class="col-md-4"></div>
   <div class="col-md-4 pull-mid text-center">Sports Management System Login</div>
   <div class="col-md-4"></div>
   <div id="clearfix"></div>
</div>

<div class="row">
   <div class="col-md-3"></div>
      <div class="col-md-6">
<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-4 control-label">User Name</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" name="uname" id="inputEmail3" placeholder="User Name">
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label">Password</label>
    <div class="col-sm-8">
      <input type="password" class="form-control" name="pass" id="inputPassword3" placeholder="Password">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-5 col-sm-10">
      <button type="submit" class="btn btn-danger btn-lg">Sign in</button>
      <button type="reset" class="btn btn-default btn-primary btn-lg"	>Cancel</button>
    </div>
  </div>
   <div class="form-group">
    <div class="col-sm-offset-4 col-sm-12">
 <?php 
 if($status!=""){
 echo '<label style="text-align:center;Color:red;background:#0F9;text-align: center;
    Color: red;
    background: #0F9;
    padding: 0 15px 15px 15px;
    border: 1px solid red;
    border-radius: 5px;">'.$_SESSION['login_attempt']	." ".$status.'</label>';}?>
    </div>
  </div> 
</form>
</div>
<div class="col-md-3"></div>

 </div><!-- row ends here -->

</div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
<?php
ob_end_flush();
?>