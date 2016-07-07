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
	 
	 		//Check If form is already submitted
			if($_SESSION['form_uid']!=$_POST['uid']){
					$_SESSION['notification'][]	=	 "Form Already Submitted!";
			}else
			{
					//setup the fields array
					$_FIELDS	=	array(
											"nameofcategory"			=>	array("text",1,"Name Of Category"),
											"parent"					=>	array("int",0,"Parent Sport"),
											"descriptionofcategory"	=>	array("text",0,"Description Of Category"),
											);
					// sanatize the post variables
					$out	=	sanatize_post($_POST,$_FIELDS);
					//if error then set eror variable
				if(isset($out['error'])){
						$_SESSION['error'][]	=	$out['error'];
						$error=1;
					}else{
						
						// save the record to the database
						
						
						//render the sql query
						$sql	=	sprintf("INSERT INTO `category_mstr` (`category`,`parent`,`description`)
												VALUES ('%s','%s','%s')",
												$out['nameofcategory'],
												$out['parent'],
												$out['descriptionofcategory']
												);
						//set sql property of the db class
						$db_object->set_sql($sql);
						
						//execute the query
						$return	=	$db_object->execute_sql();
				
						
				if(!$return){
					$_SESSION['error'][]	=	"Sport Add Failed! Please Try Again.";
				}else{
					$id		=	 mysql_insert_id();
					$_SESSION['message'][]	=	"Sport [".$out['nameofcategory']."] Added Successful with ID = $id";
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
   <a href="category_manager.php">Click To See Listing </a>
   <?php
 
	}
?>
 <h3 class="text-center">Add Category</h3>

 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
  <div class="form-group">
    <label for="nameofcategory">Name Of Category:</label>
   	 <input type="text" class="form-control" id="nameofcategory" name="nameofcategory" placeholder="Name Of Sport here...">
  </div>
<?php 
	$db_object->get_all_active_parent_categories(); 
	$row	=	$db_object->db_fetch();
	
	 
?>
  <div class="form-group">
    <label for="parentcategory">Parent Sport:</label>
    <select name="parent" class="form-control" >
    		<option value="0">Select The Parent Category</option>
			<?php 
			do{
					?>
               <option value="<?php echo $row['id'];?>"><?php echo $row['category'];?></option>
               <?php
				}while($row	=	$db_object->db_fetch())
			?>
    </select>
  </div>
  
  <div class="form-group">
  		<label for="descriptioofsport">Sport Descriptions</label>
      <textarea name="descriptionofcategory" class="form-control"></textarea>
      <input type="hidden" value="<?php echo UID; ?>" name="uid">
  </div>
 
  <button type="submit" class="btn btn-primary">Submit</button>
  <button type="reset" class="btn btn-primary">Reset Form</button>
</form>
<?php 
	$_SESSION['form_uid']	=	UID;
?>