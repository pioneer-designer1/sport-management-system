<?php
require_once('includes/header.php');
require_once('includes/sidebar.php');


//require_once('../includes/config.php');
require_once('../includes/db.php');
require_once('../includes/functions.php');

//start the session
session_start();

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
?>

   <div class="row toolbar">
      <nav class="navbar navbar-default text-center">
         <div class="collapse navbar-collapse" id="sports-bar-nav-menu">
            <ul class="nav navbar-nav">
              <li class="active">
    		<a href="add_category.php">&nbsp;&nbsp;<span class="glyphicon glyphicon-plus">&nbsp;</span>Add </a>
               </li>
              <li>
        <!-- <a href="#">&nbsp;&nbsp;<span class="glyphicon glyphicon-pencil">&nbsp;</span></a>-->
              </li>
               <li>
        <!-- <a href="#">&nbsp;&nbsp;<span class="glyphicon glyphicon-remove">&nbsp;</span></a>-->
              </li>
               <li>
        <!-- <a href="#">&nbsp;&nbsp;<span class="glyphicon glyphicon-ok">&nbsp;</span></a>-->
              </li>
            </ul>
         </div>
      </nav>
   </div>
         <script type="text/javascript" language="javascript" class="init">
	

		$(document).ready(function() {
			$('#example').DataTable(  {
										 		 "order": [[ 3, "desc" ]]
											 });
										} );


	</script>
   <div class="row table-row">
      <h2 class="sub-header">Category Table</h2>
          <div class="table-responsive">
            <table class="table table-striped" id="example">
              <thead>
                <tr>
                  <th><input type="checkbox" id="all" /></th>
                  <th>#</th>
                  <th>Name Of Sport</th>
                  <th>Parent</th>
                  <th>Status</th>
                  <th>Edit</th>
                  <th>Delete</th>                                    
                </tr>
              </thead>
              <tbody>

<?php
	$db_object	=	new db();
	$resource	=	$db_object->get_all_categories();
	$row			=	$db_object->db_fetch();

do{
	?>
   				<tr>
              		<td><input type="checkbox" id="ids-<?php echo $row['id'];?>" /></td>
                  <td><?php echo $row['id'];?></td>
                  <td><?php echo $row['category'];?></td>
                  <td><?php echo $row['parent']?$row['parent']:"--";?></td>
                  <td><a href="active.php?id=<?php echo $row['id'];?>&tb=category">
						<?php echo status($row['status']);?>
                  </a>
                  </td>
                  <td><a href="edit_category.php?id=<?php echo $row['id'];?>">Edit</a></td>
                  <td><a href="delete_category.php?id=<?php echo $row['id'];?>">Delete</a></td>                  
                </tr>
   <?php
	}while($row			=	$db_object->db_fetch())
?>

                


              </tbody>
            </table>
          </div>
      
      
      
   </div>
   <div class="row pagination-row">
		
   </div>
</div>


<?php
ob_end_flush();
//session_destroy();
?>