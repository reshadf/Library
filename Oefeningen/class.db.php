<?php
/*
* database conn
*/
class db
{
	private $connection;
	private $selectdb;
	private $lastQuery;
	private $config;

	function __construct($config)
	{
		# code...
		$this->config = $config;
	}

	function __destruct()
	{
		#destruction!
	}


/* 
* open connectie
*/
	public function openConnection()
	{
		# code...
		try 
		{

			if($this->config->connector == "mysql")
			{
				$this->connection = mysql_connect($this->config->hostname, $this->config->username, $this->config->pass);
				$this->selectdb = mysql_select_db($this->config->database);
			}
			elseif($this->config->connector == "mysqli")
			{
				$this->connection = mysql_connect($this->config->hostname, $this->config->username, $this->config->pass);
				$this->selectdb = mysql_select_db($this->config->database);	
			}
			
		} 
		catch (Exception $e) 
		{
			return $e;
		}
	}


	/*
	* escape de invoer.
	*/

	public function escapeString($string)
	{
		# code...
		if($this->config->connector == "mysql")
		{
			return mysql_real_escape_string($string);
		}
		elseif ($this->config->connector == "mysqli") 
		{
			# code...
			return mysql_real_escape_string($this->connection, $string);
		}
	}

	/*
	* laatste query onthouden 
	*/
	public function lastQuery($query = "query")
	{

		try 
		{
			if(empty($this->connection))
			{
				$this->openConnection();

				if($this->config->connector == "mysql")
				{
					$this->lastQuery = mysql_query($query);
				}
				elseif($this->config->connector == "mysqli")
				{
					$this->lastQuery = mysqli_query($this->connection, ($query));
				}

				$this->closeConnection();

				return $this->lastQuery;
			}
			else
			{
				if($this->config->connector == "mysql")
				{
					$this->lastQuery = mysql_query($query);
				}
				elseif($this->config->connector == "mysqli")
				{
					$this->lastQuery = mysqli_query($this->connection, ($query));
				}

				return $this->lastQuery;
			}

		} 

		catch (Exception $e) 
		{
			return $e;
		}
	}

	/*
	* ping server om te zien of deze nog open verbinding heeft.
	*/
	public function pingServer()
	{
		# code...
		try 
		{
			if($this->config->connector == "mysql")
			{
				if(!mysql_ping($this->connection))
				{
					return false;
				}
				else
				{
					return true;
				}
			}
			elseif($this->config->connector == "mysqli")
			{
				if(!mysqli_ping($this->connection))
				{
					return false;
				}
				else
				{
					return true;
				}
			}
		} 
		catch (Exception $e) 
		{
			return $e;
		}
	}

	public function hasRows($result)
	{
		# code...
		try 
		{
			if($this->config->connector == "mysql")
			{
				if(mysql_num_rows($result)>0)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			elseif ($this->config->connector == "mysqli") 
			{
				# code...
				if(mysqli_num_rows($result)>0)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		} 
		catch (Exception $e) 
		{
			return $e;
		}
	}

	public function countRows($result)
	{
		try
		{
			if($this->config->connector == "mysql")
			{
				return mysql_num_rows($result);
			}
			elseif ($this->config->connector == "mysqli")
			{
				# code...
				return mysqli_num_rows($result);
			}
		}
			catch (Exception $e) 
			{
				return $e;
			}
	}

	public function fetchAssoc($result)
	{
		try 
		{
			if($this->config->connector == "mysql")
			{
				return mysql_fetch_assoc($result);
			}	
			elseif ($this->config->connector == "mysqli") 
			{
				# code...
				return mysqli_fetch_assoc($result);
			}
		} 
		catch (Exception $e) 
		{
			return $e;
		}
	}

	/* 
* sluit connectie
*/

	public function closeConnection()
	{
		# code...
		try {
			if($this->config->connector == "mysql")
			{
				mysql_close($this->connection);
			}
			elseif ($this->config->connector == "mysqli") 
			{
				# code...
				mysqli_close($this->connection);
			}
		} 
		catch (Exception $e) 
		{
			return $e;
		}
	}
}
	/* 
*
* connectie met database
*
* $config = new config("hostname", "username", "password", "database", "prefix", "connector");
*/

class config
{
	public $hostname;
	public $username;
	public $pass;
	public $database;
	public $prefix;
	public $connector;

	function __construct($hostname = NULL, $username = NULL, $pass = NULL, $database = NULL, $prefix = NULL, $connector = NULL)
	{
		$this->hostname = !empty($hostname) ? $hostname : "";
		$this->username = !empty($username) ? $username : "";
		$this->pass = !empty($pass) ? $pass : "";
		$this->database = !empty($database) ? $database : "";
		$this->prefix = !empty($prefix) ? $prefix : "";
		$this->connector = !empty($connector) ? $connector : "mysql";  //mysql of mysqli waarde opgeven (database)
	}

	function __destruct()
	{
		#destruction!
	}
}

/*
	$config = new config("127.0.0.1", "root", "root", "testData", "testTable", "mysql");

	$db = new db($config);

	$db->openConnection();

	$test_connection = $db->pingServer();
	if($test_connection == 1)
	{
		$status = "online";
	}
	else
	{
		$status = "offline";
	}

		echo "we zijn " . $status;

		$sql = $db->lastQuery("SELECT 
									id, username, pass, age, gender
								FROM
										testTable
							");
	if($sql === false)
	{
		echo "<br>de tabel werd niet benaderd :(";
	}
	else
	{

		$hasRows = $db->hasRows($sql);
		if($hasRows == 0)
			echo "<br>geen rijen";
		else
		{
			$hasRows = 'wel';
			echo "<br>We hebben: " . $hasRows . " rijen";
		}
	} 

	$countRows = $db->countRows($sql);
	echo "<br>We hebben " . $countRows . " aantal rijen<br>";

	while ($result = $db->fetchAssoc($sql))
	{
		if($result['gender'] == '1')
	{
		$result['gender'] = "man";
	}
	else
	{
		$result['gender'] = "vrouw";
	}
	
	$value = '<br>id nummmer: ' . $result['id'] . '<br>';
	$value .= 'gebruikersnaam: ' . $result['username'] . '<br>';
	$value .= 'password: ' . $result['pass'] . '<br>';
	$value .= 'leeftijd: ' . $result['age'] . '<br>';
	$value .= 'geslacht: ' . $result['gender'] . '<br>';

		echo '<pre>';
		echo '<code>';
		echo ($value);
		echo '</code>';
		echo '</pre>';
	}
*/
?>

