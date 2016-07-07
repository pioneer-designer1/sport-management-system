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
	$update	=	0;
	//$_SESSION['error'][]="";
	$db_object	=	new db();
	//$_SESSION['message'][]	=	"";
 if(isset($_POST) && !empty($_POST)){
/*	 	 echo "<pre>";
		 print_r($_POST);
		 echo "</pre>"; */
	 
	 		//Check If form is already submitted
			if($_SESSION['form_uid']!=$_POST['uid']){
					$_SESSION['notification'][]	=	 "Form Already Submitted!";
			}else
			{
					//setup the fields array
					$_FIELDS	=	array(
											"nameoforganization"			=>	array("text",1,"Name Of Category"),
											"nameofhead"					=>	array("text",0,"Name Of head"),
											"mobile"							=>	array("dec",0,"Description Of Category"),
											"email"							=>	array("email",0,"Description Of Category"),
											"address"						=>	array("text",0,"Description Of Category"),
											"country"						=>	array("text",0,"Description Of Category"),
											"state"							=>	array("text",0,"Description Of Category"),
											"nameoforcity"					=>	array("text",0,"Description Of Category"),
											"id"								=>	array("text",0,"ID")
											);
											
					// sanatize the post variables
					$out	=	sanatize_post($_POST,$_FIELDS);
					//echo "1111111";
					//if error then set eror variable
				if(isset($out['error'])){
						$_SESSION['error'][]	=	$out['error'];
						 print_r($out['error']);
					}else{
						//echo "1111111";
						// save the record to the database
						
						//render the sql query
$sql	=	sprintf("UPDATE`organization_mstr` SET `organization_name` = '%s',`head`='%s',`mobile`='%s' ,`email`='%s',`address`='%s' ,`country`='%s' ,`state`='%s' ,`city`='%s' WHERE `id`='%s'",
												$out['nameoforganization'],
												$out['nameofhead'],
												$out['mobile'],
												$out['email'],
												$out['address'],
												$out['country'],
												$out['state'],
												$out['nameoforcity'],
												$out['id']
												);
												
						//set sql property of the db class
						$db_object->set_sql($sql);
						
						//execute the query
						$return	=	$db_object->execute_sql();
				
			if(!$return){
					$_SESSION['error'][]	=	"Organization Update Failed! Please Try Again.";
				}else{
					$id		=	 $out['id'];
					$update	=	1;
$_SESSION['message'][]	=	"Organization [".$out['nameoforganization']."] Updated Successful with ID = $id";
						}
				
					}
			}
 }
$error=0;
if(isset($_GET['id']) && !empty($_GET))
{
	$_FIELDS	=	array("id"=>array("int",1,"ID"));
	$out	=	sanatize_post($_GET,$_FIELDS);
	if(isset($out['error'])){
		$_SESSION['error'][]	=	$out['error'];
		$error=1;
	}else{
		$db_object->get_single_organization_record($out['id']);
		$row	=	$db_object->db_fetch();
	}
	//exit();
}elseif($update==0){
	echo "No Id Found! Please Go back and try Again";
 	exit();
}else{
			$out['nameoforganization']		=    "";
			$out['nameofhead']				=    "";
			$out['mobile']						=    "";
			$out['email']						=    "";
			$out['address']					=    "";
			$out['country']					=    "";
			$out['state']						=    "";
			$out['nameoforcity']				=    "";
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
if(($update==1) && !(isset($_GET['id'])) || $error==1){
	?>
   <a href="organization_manager.php">Click To See Listing </a>
   <?php
	exit();
	}
?>
 
 <h3 class="text-center">Edit Organization</h3>

 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
  <div class="form-group">
    <label for="nameofcategory">Name Of Organization :</label>
   	 <input type="text" class="form-control" id="nameoforganization" name="nameoforganization" placeholder="Name Of Organization here..." value="<?php echo $row['organization_name'];?>">
  </div>
  
    <div class="form-group">
    <label for="nameofcategory">Name Of Head :</label>
   	 <input type="text" class="form-control" id="nameoforhead" name="nameofhead" placeholder="Name Of Head Of Organization here..."  value="<?php echo $row['head'];?>">
  </div>
  
  <div class="form-group">
    <label for="nameofcategory">Mobile No :</label>
   	 <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile No ..."  value="<?php echo $row['mobile'];?>">
  </div>
  
  <div class="form-group">
    <label for="nameofcategory">Email :</label>
   	 <input type="text" class="form-control" id="email" name="email" placeholder="Email Id ..."  value="<?php echo $row['email'];?>">
  </div>
  
  <div class="form-group">
  		<label for="descriptioofsport">Address :</label>
      <textarea name="address" class="form-control">  <?php echo $row['address'];?> </textarea>
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
   	 <input type="text" class="form-control" id="nameoforcity" name="nameoforcity" placeholder="City ..."  value="<?php echo $row['city'];?>">
        <input type="hidden" value="<?php echo $out['id']; ?>" name="id">
  </div>
 
  <button type="submit" class="btn btn-primary">Submit</button>
  <button type="reset" class="btn btn-primary">Reset Form</button>
</form>
<?php 
	$_SESSION['form_uid']	=	UID;
?>