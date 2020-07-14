<?php

	//Checks for empty/unset form fields
	function _InvalidInputs($names, $labels){
		$input_error = array();
		for($i = 0; $i < count($names); $i++){
			if( empty($names[$i]) || !isset($names[$i]) ){
				//Input is empty or unset - return true
				array_push($input_error, "Error: Empty Field - ".$labels[$i]."<br >");
			}
		}
		return $input_error;
	}
	
	function _ClearFields($names, $m_names){
		for($i = 0; $i < count($names); $i++){
			$names[$i] = "";
		}
	}
	
	//Checks for invalid email addresses
	function _InvalidEmail($email){
		$email_error;
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$email_error = "Error: Please enter a valid e-mail address"."<br >";
			return $email_error;
		}
		return false;
	}
	
	
	
	function validate_form($names, $labels){	   
		$form_errors = array();
		$invalid_inputs = _InvalidInputs($names, $labels);
		
		return $form_errors;
	}
	
	
	function process_form($dbc, $email){
		$query = "INSERT INTO ".EARLY_BIRDS." (email) VALUES ('$email')";

		//Get the id of recently inserted row
		mysqli_query($dbc,$query);
		$data = mysqli_query($dbc,"SELECT LAST_INSERT_ID()");
		$row = mysqli_fetch_array($data);
		$basic_id = $row['LAST_INSERT_ID()'];
	}

?>