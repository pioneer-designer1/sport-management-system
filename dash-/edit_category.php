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
											"parent"						=>	array("int",0,"Parent Sport"),
											"descriptionofcategory"	=>	array("text",0,"Description Of Category"),
											"id"							=>	array("text",0,"ID")
											);
					// sanatize the post variables
					$out	=	sanatize_post($_POST,$_FIELDS);
					//if error then set eror variable
				if(isset($out['error'])){
						$_SESSION['error'][]	=	$out['error'];
					}else{
						
						// save the record to the database
						
						
						//render the sql query
$sql	=	sprintf("UPDATE`category_mstr` SET `category` = '%s',`parent`='%s',`description`='%s' WHERE `id`='%s'",
												$out['nameofcategory'],
												$out['parent'],
												$out['descriptionofcategory'],
												$out['id']
												);
						//set sql property of the db class
						$db_object->set_sql($sql);
						
						//execute the query
						$return	=	$db_object->execute_sql();
				
				
				if(!$return){
					$_SESSION['error'][]	=	"Sport Update Failed! Please Try Again.";
				}else{
					$id		=	 $out['id'];
					$update	=	1;
			$_SESSION['message'][]	=	"Sport [".$out['nameofcategory']."] Updated Successful with ID = $id";
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
		$db_object->get_single_category_record($out['id']);
		$row	=	$db_object->db_fetch();
	}
	//exit();
}elseif($update==0){
	echo "No Id Found! Please Go back and try Again";
 	exit();
}else{
	$row['category']			=	"";
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
if(($update==1) && !(isset($_GET['id'])) || $error==1){
	?>
   <a href="category_manager.php">Click To See Listing </a>
   <?php
	exit();
	}
?>

 <h3 class="text-center">Modify Sport</h3>

 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
  <div class="form-group">
    <label for="nameofcateory">Name Of Sports:</label>
<input type="text" class="form-control" value="<?php echo $row['category']?>" id="nameofcategory" name="nameofcategory" placeholder="Name Of Category here...">
  </div>
<?php 
	$db_object->get_all_active_parent_categories(); 
	$row_parent	=	$db_object->db_fetch();
	
	 
?>
  <div class="form-group">
    <label for="parentcategory">Parent Sport:</label>
    <select name="parent" class="form-control" >
    		<option value="0">Select The Parent Category</option>
			<?php 
			do{
				if($row_parent['id']==$row['parent']){$selected="selected";}else{$selected="";}
					?>
               <option value="<?php echo $row_parent['id'];?>" <?php echo $selected?>>
						<?php echo $row_parent['category'];?>
               </option>
               <?php
				}while($row_parent	=	$db_object->db_fetch())
			?>
    </select>
  </div>
  
  <div class="form-group">
  		<label for="descriptioofsport">Sport Descriptions</label>
      <textarea name="descriptionofcategory" class="form-control"><?php echo $row['description']; ?></textarea>
      <input type="hidden" value="<?php echo $out['id']; ?>" name="id">
      <input type="hidden" value="<?php echo UID; ?>" name="uid">
  </div>
 
  <button type="submit" class="btn btn-primary">Submit</button>
  <button type="reset" class="btn btn-primary">Reset Form</button>
</form>
<?php 
	$_SESSION['form_uid']	=	UID;
	ob_end_flush();
?>