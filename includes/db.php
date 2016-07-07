<?php
/**
*		File: db.php  A database operation file
*		Author: Sachin Mohite
*		Company: Pioneer Solutions
*
*/

require_once('functions.php');
require_once('config.php');


class db{
	
		private	$db_uname	=	"root";
		private	$db_pass		=	"";
		private	$db_host		=	"localhost";
		private	$db_name		=	"sms";
		private 	$message		=	array("");
		private	$error		=	array("");
		private	$db_select	=	"";
		private 	$connection	=	"";
		private 	$sql			=	"";
		private	$resource	=	"";
	
/**
*	construct function to setup connectin and select the database 
*	all the initializatin task can be done here
*	
*
*/
	
	function __construct(){
			$this->connection				=		@mysql_connect($this->db_host,$this->db_uname,$this->db_pass);
			if($this->connection){
						$this->message[]	=	"Database connected: Db class construct:001:01";
						$db_select			=	@mysql_select_db($this->db_name);
					if(!$db_select){
							$this->error[]	=	"Failed to Select Database: Db class construct:002:01";
						}
				}else{
						 $this->message[]	=	"Database Connection Error: Db class construct:001:02";	
				}
		}
		
/**
*	authentication functoin for the login form as well as other purposes
*	parameter	$uname	=	username $pass	=	password
*	Returns userid role and username in array
*	function does not do any kind of sanatization on it's own 
*/
		
		function auth(	$uname,	$pass){
					//generate the query 
					 $sql				=	sprintf("SELECT 	id, 	username, 	role	
														FROM	`usr_mstr`	
														WHERE `username`	=	'%s'	
														AND	`password`	=	md5('%s')",
														$uname,$pass);
						//execute the query
							 	$resource			=	@mysql_query($sql);
						 		$member				=	mysql_fetch_assoc($resource);
						 // generate the message and return the value
						if($member>0){
									 	$this->message[]	=	"Authentication Successful: db class auth:002:01";
										return $member;
							}else{
									 	$this->message[]	=	"Authentication Failed: db class auth:002:02";
										$this->error[]		=	"Authentication Error";
										$this->log_error_criticle();
										return false;
							}
			}

/**
*		function to registe the ip address of the user
*		register the ip 
*
*/	

		function register_ip($remark	=	"default"){
				$sql			=	sprintf("INSERT INTO `iptable` ( `ip` , `remark`)
												VALUES('%s',	'$remark')",
												REMOTEIP);
				$resource	=	@mysql_query($sql);
				if($resource){
							 $this->message[]	=	"IP Registered successfully:db class 003:01";
					}else{
							 $this->message[]	=	"Failed to register the Ip address:db class 003:02";
					}		
			}
			
/**
*
*  	set sql query 
*
*
*
*/
		function execute_sql()
		{
			$resource					=	@mysql_query($this->sql);
			if(!$resource){
					 $this->message[]	=	"Failed to execute the query:db class 004:01";
					 $this->dump_messages();
			}
			return $resource;
		}			
/**
*
*		get all sports 
*
*
*/			
		function get_all_parent_sports(){
				$sql	=	"SELECT `id`, `sport` FROM `sport_mstr` WHERE `parent`=0";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}	
/**
*
*		get all sports 
*
*
*/			
		function get_all_active_parent_sports(){
				$sql	=	"SELECT `id`, `sport` FROM `sport_mstr` WHERE `parent`	=	0 AND `status`	=	1";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}				

/**
*
*		get all sports 
*
*
*/			
		function get_all_sports(){
				$sql	=	"SELECT sm.id, sm.sport, (
															SELECT `sport` 
															FROM `sport_mstr` AS smp
															WHERE smp.id = sm.parent
														) AS parent,
							`status` 
							FROM `sport_mstr` AS sm
							ORDER BY `id` DESC";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}		
			
			
			
/**
*
*		get single record based on id and table
*
*
*/			
		function get_single_sport_record($id	=	""){
				$sql							=	"SELECT * FROM `sport_mstr` WHERE `id`	=	'$id'";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}
			
/**
*
*		Delete single record based on id and table
*
*
*/			
		function delete_single_sport_record($id	=	""){
				$sql							=	"DELETE FROM `sport_mstr` WHERE `id`	=	'$id'";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}	
/**
*
*		Delete single record based on id and table
*
*
*/			
		function delete_single_category_record($id	=	""){
				$sql							=	"DELETE FROM `category_mstr` WHERE `id`	=	'$id'";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}	
/**
*
*		Delete single record based on id and table
*
*
*/			
		function delete_single_organization_record($id	=	""){
				$sql							=	"DELETE FROM `organization_mstr` WHERE `id`	=	'$id'";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}							
/**
*
*		alter the state of the sport record 
*
*
*/			
		function alter_single_sport_record($id	=	"",$tb=""){
				$sql							=	"UPDATE ".$tb."_mstr SET `status` = 1	-	status WHERE `id`	=	'$id'";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}	
/**
*
*		get all sports 
*
*
*/			
		function get_all_orgs(){
				$sql	=	"SELECT `id`, `organization_name`,`head`,`mobile`,`email`,`address`,`state`,`city` 
							,`country` ,`status` FROM `organization_mstr` 
							ORDER BY `id` DESC";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}					
/**
*
*		get all sports 
*
*
*/			
		function get_all_categories(){
				$sql	=	"SELECT sm.id, sm.category, (
															SELECT `category` 
															FROM `category_mstr` AS smp
															WHERE smp.id = sm.parent
														) AS parent,
							`status` 
							FROM `category_mstr` AS sm
							ORDER BY `id` DESC";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}				
			
/**
*
*		get all Categories 
*
*
*/			
		function get_all_active_parent_categories(){
				$sql	=	"SELECT `id`, `category` FROM `category_mstr` WHERE `parent`	=	0 AND `status`	=	1";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}																	
/**
*
*		get single record based on id and table
*
*
*/			
		function get_single_category_record($id	=	""){
				$sql							=	"SELECT * FROM `category_mstr` WHERE `id`	=	'$id'";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}	
			
			
/**
*
*		get single record based on id and table
*
*
*/			
		function get_single_organization_record($id	=	""){
				$sql							=	"SELECT * FROM `organization_mstr` WHERE `id`	=	'$id'";
				$this->sql					=	$sql;
				return $this->resource 	=	$this->execute_sql();

			}						
/**
*
*  	set sql query 
*
*
*
*/
		function set_sql($sql	=	"")
		{
			$this->sql				=	$sql;	
		}			
/**
*
*		get all sports 
*
*
*/			
		function db_fetch(){
				return $row			=	@mysql_fetch_assoc($this->resource);
			}				

			
/**
*		function to log the message from the outsite of the class
*
*
*
*/
			function log_message($message	=	""){
					$this->message[]			=	$message;
				}

/**
*		function to log the message from the outsite of the class
*
*
*
*/
			function log_error($error	=	""){
					$this->error[]			=	$message;
				}
				
/**
*		function to log the errors into the database
*
*
*/
			function log_error_criticle(){
						$this->register_ip('criticle');
						echo "log errors";
				}
/**
*
*
*		dump messages 
*/
	
		function dump_messages(){
				echo "<pre>";
				print_r($this->message);
				echo "</pre>";
			}
			
/**
*
*
*		dump errors 
*/
	
		function dump_errors(){
				echo "<pre>";
				print_r($this->error);
				echo "</pre>";
			}			
	}

?>