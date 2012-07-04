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


	// $user = new User('Reshad');
	// echo 'Hallo ' . $user->getUsername() . '<br>';

	try 
	{
		$db = new PDO('mysql:host=localhost;dbname=portfolio', 'root', 'root');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$stmt = $db->query('SELECT id, name, password FROM members');
 
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
	   		echo 'id: ' . $row['id'] . '<br/> ';
	   		echo 'Naam: ' . $row['name'].'<br/> ';
	   		echo 'password: ' . $row['password'].'<br/>'; //etc...
   		}

	} 
	catch (PDOException $e) 
	{
		$foutmelding = 'Er is iets fout gegaan. Hieronder vind u meer info over de fout, graag de Webmaster contacteren met de foutmelding<br/>'; 
		$foutmelding .= 'regel: ' . $e->getLine() . '<br>';
		$foutmelding .= 'Bestand: ' . $e->getFile() . '<br>';
		$foutmelding .= 'Error melding: ' . $e->getMessage();
		echo $foutmelding;
	}

   	$db = NULL;


   	$admin = new Admin('Reshad');

   	echo 'Hallo ' . $admin->getUsername() . '<br>';
   	echo 'is ' . $admin->getUserStatus();

	
?>