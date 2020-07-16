<?php
	//Start session
	session_start();

	$host = 'localhost';
	$username = 'postgres';
	$password = 'donimuvukor@postgres';
	$dbname = 'automate';
	
	//Set page instance id
	if(!isset($_SESSION['page_instance_ids']) || (count($_SESSION['page_instance_ids']) > 5)){
		$_SESSION['page_instance_ids'] = array();
	}
	
	//require_once('connectvars.php');
	require_once('sitevars.php');
	require_once('functions.php');
	
	//If submit button is clicked, create DB connection and get form data	
	if(isset($_POST['submit']))
	{
		
		/*Connect to the database
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		*/

		$dbc = pg_connect("host=$host dbname=$dbname user=$username password=$password");
		
		/*Get the form data
		$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
		*/

		$email = pg_escape_string($dbc, trim($_POST['email']));
		
		
		$field_names = array("$email");
						
		$field_labels = array("E-mail");
							 
		
		$fields = array("$email" => "E-mail");
		
		//If validation fails, retrieve errors
		if($form_errors = validate_form($field_names, $field_labels))
		{		
			
			//Display form again along with validation errors
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
			//Check if submission == page refresh/reload
			$page_id_index = array_search($_POST['page_instance_id'], $_SESSION['page_instance_ids']);

			if($page_id_index !== false)
			{
				unset($_SESSION['page_instance_ids'][$page_id_index]);

				//Process form
				if(process_form($dbc, $email) > 0) 
				{
				//Display error message
				echo '<link rel="stylesheet" href="index.css" >';
				echo '<div id="thanks"><h2>THIS EMAIL HAS ALREADY BEEN SIGNED UP!</h2></div>';
        		echo '<div id="success">';
            		echo '<div id="data">';
              			echo '<ul>';
							echo '<li><strong><span class="label">E-mail:</span></strong><br >'.
                	 			 '<span class="text">'.$email.'</span></li>';
              			echo '</ul>';
            	echo'</div>';
				echo'</div>';
				}
				else 
				{
				//Display success message
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
				}
	   
	   			/*Close database connection
				mysqli_close($dbc);
				*/
				pg_close($dbc);
			}
			else
			{
				//Page refresh/reload detected; redirect to form
				header("Location: ".$_SERVER["REQUEST_URI"]);
				exit;
			}

		}
		
	}
	else
	{
		//Form was not submitted; display form
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