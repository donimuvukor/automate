<?php

$host = 'localhost';
$username = 'postgres';
$password = 'donimuvukor@postgres';
$dbname = 'automate';


require_once('sitevars.php');
require_once('functions.php');

var_dump($_POST);

if( isset($_POST['submit']) )
{
    $dbc = pg_connect("host=$host dbname=$dbname user=$username password=$password");

    $email = isset($_POST['email-top']) ?  
             pg_escape_string($dbc, trim($_POST['email-top'])) :
             pg_escape_string($dbc, trim($_POST['email-bottom']));
    
    $field_names = array("$email");
                    
    $field_labels = array("E-mail");
                         
    $fields = array("$email" => "E-mail");

    //If validation fails, retrieve errors
    if( validate_form($field_names, $field_labels) )
    {		
			
        //Display validation error message
        echo '<div class = "error_php">';
            for($j=0; $j<count($form_errors); $j++){ echo "$form_errors[$j]"."<br >"; }
        echo '</div>';

        //Redirect to home page 
        header("refresh:5; url=index.html");

    } 
    //Validation passes, no errors in form
    else if ( !validate_form($field_names, $field_labels) )
    {
        //Process form
        if(process_form($dbc, $email) > 0) 
        {
            //Email already exists in database
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
            
            //Redirect to home page 
            header("refresh:5; url=index.html");
        }
        else
        {
            //Email saved successfully
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

            //Redirect to home page 
            header("refresh:5; url=index.html");
        }

        pg_close($dbc);	
    }
}

?>

