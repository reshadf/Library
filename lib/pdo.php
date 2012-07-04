<?php
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
?>