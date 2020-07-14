<?php
	//Start session
	session_start();
	
	//Set page instance id
	if(!isset($_SESSION['page_instance_ids']) || (count($_SESSION['page_instance_ids']) > 5)){
		$_SESSION['page_instance_ids'] = array();
	}
	
	require_once('connectvars.php');
	require_once('sitevars.php');
	require_once('functions.php');
	
	//Request form submission, so validate form	
	if(isset($_POST['submit'])){
		
		// Connect to the database
    	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		
		// Get the form data
    	
		$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
		
		
		$field_names = array("$email");
						
		$field_labels = array("E-mail");
							 
		
		$field_name_label = array("$email" => "E-mail");
		
		//Validate form
		if( $form_errors = validate_form( $field_names, $field_labels ) ){		
			
			//Form submitted with errors; show errors and show form
			echo '<div class = "error_php">';
			for($j=0; $j<count($form_errors); $j++){ echo "$form_errors[$j]"."<br >"; }
			echo '</div>';
			?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>AutoMate</title>
<link rel="stylesheet" href="index.css" >
<script type="text/javascript" src="gen_validatorv4.js">
</script>
</head>

<body>
<div id="header">
  <div id="head">
	<h1>AutoMate</h1>
  </div>
</div>

    <form name="alumniform" enctype="multipart/form-data" method="post" action="" >
      <table>
       <div id="welcome">
			Get an e-mail as soon as we launch!
       </div>
        
        <tr>
          <th>
          <label for="email">E-mail:</label>
          </th>
          <td>
          <div id="alumniform_email_errorloc" class="error_strings"></div>
          <input type="email" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>" >
          <span class="required">*</span>
          </td>
        </tr>
        
        <tr>
          <th>
          </th>
          <td>
          <input type="submit" name="submit" value="Yes, I can't wait!" >
          </td>
        </tr>
      </table>
    </form>

	<script type="text/javascript">
		var frmvalidator  = new Validator("alumniform");
		frmvalidator.EnableOnPageErrorDisplay();
		frmvalidator.EnableMsgsTogether();
		
		frmvalidator.addValidation("email","req","Please fill: E-mail");
		frmvalidator.addValidation("email","email");
    </script>
    
</body>
</html> 

        <?php
		}
		else{
			//Form valid, process form if request != refresh
			$page_id_index = array_search($_POST['page_instance_id'], $_SESSION['page_instance_ids']);
			if($page_id_index !== false){
				unset($_SESSION['page_instance_ids'][$page_id_index]);

				//$pic_path = file path of new picture name
				process_form($dbc, $email);
				
				//Confirm success with the user
				echo '<link rel="stylesheet" href="index.css" >';
				echo '<div id="thanks"><h2>THANK YOU!</h2></div>';
        		echo '<div id="success">';
            		echo '<div id="data">';
              			echo '<ul>';
							echo '<li><strong><span class="label">E-mail:</span></strong><br >'.
                	 			 '<span class="text">'.$email.'</span></li>';
              			echo '</ul>';
            	echo'</div>';
       			echo'</div>';
	   
	   			//Close the database connection
	   			mysqli_close($dbc);
			}
			else{
				//Refresh detected, redirect back to form
				header("Location: ".$_SERVER["REQUEST_URI"]);
				exit;
			}

		}
		
	}
	else{
		//Form wasn't submitted, so display the form
		$_SESSION['page_instance_ids'][] = uniqid('',true);
		?>	
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>AutoMate</title>
<link rel="stylesheet" href="index.css" >
<script type="text/javascript" src="gen_validatorv4.js">
</script>
</head>

<body>
<div id="header">
  <div id="head">
	<h1>AutoMate</h1>
  </div>
</div>

    <form name="alumniform" enctype="multipart/form-data" method="post" action="" >
      <table>
	  	<div id="welcome">
			Get an e-mail as soon as we launch!
    	</div>
        
        <tr>
          <th>
          <label for="email">E-mail:</label>
          </th>
          <td>
          <div id="alumniform_email_errorloc" class="error_strings"></div>
          <input type="email" id="email" name="email" value="" >
          </td>
        </tr>
        
        <tr>
          <th>
          </th>
          <td>
          <input type="hidden" name="page_instance_id" value="<?php echo end($_SESSION['page_instance_ids']); ?>" >
          <input type="submit" name="submit" value="Yes, I can't wait!" >
          </td>
        </tr>
      </table>
    </form>

	<script type="text/javascript">
		var frmvalidator  = new Validator("alumniform");
		frmvalidator.EnableOnPageErrorDisplay();
		frmvalidator.EnableMsgsTogether();
		
		frmvalidator.addValidation("email","req","Please fill: E-mail");
		frmvalidator.addValidation("email","email");
    </script>
    
</body>
</html>

<?php		
	   }
?>