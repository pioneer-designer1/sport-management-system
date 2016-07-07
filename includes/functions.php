<?php 

//require_once('includes/db.php');

/**
*	function to sanatize the form input values
*
*	parameters:
*	$post 	=	 	is a post or a variable array to filter the values
*	$val		=		is a 2 dimentional array containg the field or key name and expected filter format
*
*
*	in foreach loop we have taken the values form $val insted of post because it will prevent other unwanted   *   fields from form input
*
*
*/


function sanatize_post($post	=	array(""),$val	=	array("")){
		foreach($val as $key	=> $value){
			// check if required fields are not null
			if(($value[1]==1) && ($post[$key]==NULL)){
					$out['error']	=	$value[2]." Field Is Required";
					return $out;
				}
				
				//sanatize the post values
				switch($value[0]){
						case "text":
								$out[$key]	=	addslashes($post[$key]);
						break;
						case "int":
							
							if (filter_var($post[$key], FILTER_VALIDATE_INT) == false) {
								 $out['error']	=	$value[2]." Field Value Is Not Valid";
 
							} else {
								 $out[$key]	=	addslashes($post[$key]);
							}
						break;
						case "email":
							if (filter_var($post[$key], FILTER_VALIDATE_EMAIL) === false) {
								 $out['error']	=	$value[2]." Field Value Is Not Valid";
							} else {
								 $out[$key]	=	addslashes($post[$key]);
							}
						break;
							case "dec":
							if (filter_var($post[$key], FILTER_SANITIZE_NUMBER_INT) === false) {
								 $out['error']	=	$value[2]." Field Value Is Not Valid";
							} else {
								 $out[$key]	=	addslashes($post[$key]);
							}
						break;
					}
			}
 
			return $out;
	}

/**
*
*   convert no to the state
*
*/

	function status($state="1"){
				switch($state){
						case 0:
							return "Inactive";
							break;
						case 1:
							return "Active";
							break;
					}
		}
	
?>