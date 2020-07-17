<?php 

//Start session
session_start();

$host = 'localhost';
$username = 'postgres';
$password = 'donimuvukor@postgres';
$dbname = 'automate';

//Set page instance id
if(!isset($_SESSION['page_instance_ids']) || (count($_SESSION['page_instance_ids']) > 5))
{
    $_SESSION['page_instance_ids'] = array();
}

require_once('sitevars.php');
require_once('functions.php');

echo '<div id="thanks"><h2>I got here P!</h2></div>';

var_dump($_POST);

if(count($_POST) == 0) {
    echo 'Nothing in POST';
}

//If submit button is clicked, create DB connection and get form data	
if( isset($_POST['submit-btn-top']) || isset($_POST['submit-btn-bottom']) )
{
    echo '<div id="thanks"><h2>I got here P!</h2></div>';
    $dbc = pg_connect("host=$host dbname=$dbname user=$username password=$password");

    $email = pg_escape_string($dbc, trim($_POST['email']));
    
    
    $field_names = array("$email");
                    
    $field_labels = array("E-mail");
                         
    
    $fields = array("$email" => "E-mail");

    //If validation fails, retrieve errors
    if($form_errors = validate_form($field_names, $field_labels))
    {		
			
        //Display validation error message
        echo '<div class = "error_php">';
        for($j=0; $j<count($form_errors); $j++){ echo "$form_errors[$j]"."<br >"; }
        echo '</div>';

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
    }

?>