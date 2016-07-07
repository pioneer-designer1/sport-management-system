<?php 
function sidebar(){


	//get script file name
	$script	=	basename($_SERVER["SCRIPT_FILENAME"], ".php");
	
	//set page array
	$page 	= 	array(	
							"sport_manager"			=>	"0",
							"category_manager"		=>	"0",
							"organization_manager"	=>	"0",
							"players_manager"			=>	"0",							
							"dashboard"					=>	"0",
							"index"						=>	'0',
						);
																
	 //set active class for proper page
	if(array_key_exists($script,$page)){
		//set active class to the loaded page
		$page[$script]		=	"active";
	}else{
		//set active class to the loaded page
		$dashboard			=	"active";
	};
	
	//set variable values
	 extract($page);
?>
<div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="<?php echo $dashboard;?>">
            	<a href="dashboard.php">Overview <span class="sr-only">(current)</span></a>
            </li>
            <li class="<?php echo $sport_manager;?>">
            	<a href="sport_manager.php">Sports</a>
             </li>
              <li class="<?php echo $category_manager;?>">
            	<a href="category_manager.php">Category</a>
             </li>
              <li class="<?php echo $organization_manager;?>">
            	<a href="organization_manager.php">Organization</a>
             </li>
              <li class="<?php echo $players_manager;?>">
            	<a href="players_manager.php">Player</a>
             </li>             
            <li><a href="#">Reports</a></li>
            <li><a href="#">Analytics</a></li>
            <li><a href="#">Export</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <!--<li><a href="">Nav item</a></li>
            <li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>
            <li><a href="">More navigation</a></li>-->
          </ul>
          <ul class="nav nav-sidebar">
            <!--<li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>-->
          </ul>
        </div>
<?php } ?>