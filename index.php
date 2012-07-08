<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL|E_STRICT);

	/**
	*
	* 	auto loader 
	*
	*/


	function __autoload($className) 
	{
		// haal de base dir op.
	    $base = dirname(__FILE__);
	    
	    // het pad ophalen
	   	$path = $className;

	    // alle paden samenvoegen tot waar ik zijn moet en de phpfile eraan plakken.
	    $file = $base . "/lib/" . $path . '.php';       
	    
	    
	    // als file bestaat haal op anders error
	    if (file_exists($file)) 
	    {
	        require $file;      
	    }
	    else 
	    {
	        error_log('Class "' . $className . '" could not be autoloaded');
	        throw new Exception('Class "' . $className . '" could not be autoloaded from: ' . $file); 
	    }
	}

	/**********************************************************************************************************************************************************************************************************************/

	if(isset($_COOKIE['Login']))
	{
		echo 'u bent al ingelogd';
	}
	else
	{
		// create a new FormHandler object 
		$form = new FormHandler(); 

		// some fields.. (see manual for examples) 
		$form->textField( "Naam", "name", FH_STRING, 20, 40); 
		$form->passField("Wachtwoord", "pass", FH_PASSWORD);


		//$autologin = $form->checkbox("Remember me:", "remember", 1);

		$form->textField( "Naam", "name", FH_STRING, 20, 40); 
		$form->passField("Wachtwoord", "pass", FH_PASSWORD);


		// button for submitting 
		$form->submitButton(); 


		// set the 'commit-after-form' function 
		$form->onCorrect('doRun'); 

		// display the form 
		$form->flush(); 
	}

	// the 'commit-after-form' function 
		function doRun()  
		{ 
			$msg = '';
			$username = mysql_real_escape_string($_POST['name']);
			$pass = mysql_real_escape_string($_POST['pass']);
		    try 
			{
				$db = new PDO('mysql:host=localhost;dbname=testData', 'root', 'root');
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					$stmt = $db->prepare('	SELECT 
													username, pass 
											FROM 	
													testTable
											WHERE
													username = :name
											AND 	
													pass = :pass

										');

					$stmt->bindParam(':name', $username, PDO::PARAM_STR);
					$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);

					$stmt->execute();
					
					$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

					if($result == false)
					{
						$msg = 'sorry could not connect';
					}
					else
					{
						//$_SESSION['name'] = $username;

						/**
						 * Create a cookie with the name "myCookieName" and value "testing cookie value"
						 */
						$cookie = new Cookie();
						// Set cookie name
						$cookie->setName('Login');
						// Set cookie value
						$cookie->setValue("testing cookie value");
						// Set cookie expiration time
						$cookie->setTime("+1 hour");
						// Create the cookie
						$cookie->create();
						

						// Delete the cookie.
						//$cookie->delete();
						$msg = 'logged in as ' . $username . '<br>';
						
					}
					
			} 
			catch (PDOException $e) 
			{
				echo "Error:" . $e;
			}

			echo $msg;

		   	$db = NULL;
		} 

