<?php

	//Checks for empty/unset form fields
	function _InvalidInputs($names, $labels)
	{
		$input_error = array();
		for($i = 0; $i < count($names); $i++)
		{
			if(empty($names[$i]) || !isset($names[$i]))
			{
				//Input is empty or unset - return true
				array_push($input_error, "Error: Empty Field - ".$labels[$i]."<br >");
			}
		}
		return $input_error;
	}
	
	//Checks for invalid email addresses
	function _InvalidEmail($email)
	{
		$email_error;
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$email_error = "Error: Please enter a valid e-mail address"."<br >";
			return $email_error;
		}
		return false;
	}
	
	//Validates form input
	function validate_form($names, $labels)
	{	   
		$form_errors = array();
		$invalid_inputs = _InvalidInputs($names, $labels);

		return $form_errors;
	}
	
	//Runs databases queries
	function process_form($dbc, $email)
	{
		$get_email_query = "SELECT email "."FROM ".EARLY_BIRDS." WHERE email = '".$email."'";
		$result = pg_exec($dbc, $get_email_query);

		
		//Check if email already exists in database
		if(pg_num_rows($result) > 0)
		{
			return pg_num_rows($result);
		}

		$set_email_query = "INSERT INTO ".EARLY_BIRDS." (email) VALUES ('$email')";
		//Insert email into database
		pg_exec($dbc, $set_email_query);
		return pg_num_rows($result);
	}

?>