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
	$add		=	0;
	$error	=	0;
	$db_object	=	new db();
	//$_SESSION['message'][]	=	"";
 if(isset($_POST) && !empty($_POST)){
	/*	 echo "<pre>";
		 print_r($_POST);
		 echo "</pre>";*/
	 
	 		//Check If form is already submitted
			if($_SESSION['form_uid']!=$_POST['uid']){
					$_SESSION['notification'][]	=	 "Form Already Submitted!";
			}else
			{
					//setup the fields array
					$_FIELDS	=	array(
											"nameoforganization"		=>	array("text",1,"Name Of Category"),
											"nameofhead"				=>	array("text",0,"Name Of Head"),
											"mobile"						=>	array("dec",0,"Mobile No"),
											"email"						=>	array("text",0,"Email"),
											"address"					=>	array("text",0,"Address"),
											"country"					=>	array("text",0,"Country"),
											"state"						=>	array("text",0,"State"),
											"nameoforcity"				=>	array("text",0,"city"),
											);
					// sanatize the post variables
					$out	=	sanatize_post($_POST,$_FIELDS);
					//if error then set eror variable
				if(isset($out['error'])){
						$_SESSION['error'][]	=	$out['error'];
						$error=1;
					}else{
						
						// save the record to the database
						
						//generate the sql query
						$sql	=	sprintf("INSERT INTO `organization_mstr` ( `organization_name`, `head`, `mobile`,
						`email`,`address`,`state`,`city`,`country`)
												VALUES ('%s','%s','%s','%s','%s','%s','%s','%s')",
												$out['nameoforganization'],
												$out['nameofhead'],
												$out['mobile'],
												$out['email'],
												$out['address'],
												$out['country'],
												$out['state'],
												$out['nameoforcity']
												);
						//set sql property of the db class
						$db_object->set_sql($sql);
						
						//execute the query
						$return	=	$db_object->execute_sql();
				
				if(!$return){
					$_SESSION['error'][]	=	"Sport Add Failed! Please Try Again.";
				}else{
					$id		=	 mysql_insert_id();
		$_SESSION['message'][]	=	"Sport [".$out['nameoforganization']."] Added Successful with ID = $id";
					$add=1;
				}
			}
		}
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
if(($add==1) || $error==1){
	?>
   	<a href="organization_manager.php">Click To See Listing </a>
   <?php
	}
?>
 <h3 class="text-center">Add Organization</h3>

 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
  <div class="form-group">
    <label for="nameofcategory">Name Of Organization :</label>
   	 <input type="text" class="form-control" id="nameoforganization" name="nameoforganization" placeholder="Name Of Organization here...">
  </div>
  
    <div class="form-group">
    <label for="nameofcategory">Name Of Head :</label>
   	 <input type="text" class="form-control" id="nameoforhead" name="nameofhead" placeholder="Name Of Head Of Organization here...">
  </div>
  
  <div class="form-group">
    <label for="nameofcategory">Mobile No :</label>
   	 <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile No ...">
  </div>
  
  <div class="form-group">
    <label for="nameofcategory">Email :</label>
   	 <input type="text" class="form-control" id="email" name="email" placeholder="Email Id ...">
  </div>
  
  <div class="form-group">
  		<label for="descriptioofsport">Address :</label>
      <textarea name="address" class="form-control"></textarea>
      <input type="hidden" value="<?php echo UID; ?>" name="uid">
  </div>
  
  <?php 
	//$db_object->get_all_active_parent_categories(); 
	//$row	=	$db_object->db_fetch();
	
	 
?>
  <div class="form-group">
    <label for="parentcategory">Country :</label>
    <select name="country" class="form-control" >
    		<option value="0">Select Country</option>
			<?php 
			/*do{
					?>
               <option value="<?php echo $row['id'];?>"><?php echo $row['category'];?></option>
               <?php
				}while($row	=	$db_object->db_fetch())*/
			?>
    </select>
  </div>


  <div class="form-group">
    <label for="parentcategory">State :</label>
    <select name="state" class="form-control" >
    		<option value="0">Select State</option>
			<?php 
			/*do{
					?>
               <option value="<?php echo $row['id'];?>"><?php echo $row['category'];?></option>
               <?php
				}while($row	=	$db_object->db_fetch())*/
			?>
    </select>
  </div>
 
  <div class="form-group">
    <label for="nameofcategory">City :</label>
   	 <input type="text" class="form-control" id="nameoforcity" name="nameoforcity" placeholder="City ...">
  </div>
 
  <button type="submit" class="btn btn-primary">Submit</button>
  <button type="reset" class="btn btn-primary">Reset Form</button>
</form>
<?php 
	$_SESSION['form_uid']	=	UID;
?>