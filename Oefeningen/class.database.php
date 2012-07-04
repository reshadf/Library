<?php
class databaseConnect
{
	/**
	 * declareren van alle functies
	 */
	private $openConnect;
	private $selectDatabase;
	private $runQuery;
	private $databaseConnect;
	private $pingServer;
	private $databaseConfig;

	public function __construct($databaseConfig)
	{
		$this->databaseConfig = $databaseConfig;
	}

	public function __destruct()
	{
		#destroy function
	}

	// open connectie
	public function openConnect()
	{
		try 
		{
			if($this->databaseConfig->connector == "mysql")
			{
				$this->openConnect = mysql_connect($this->databaseConfig->hostname, $this->databaseConfig->username, $this->databaseConfig->pass);
				$this->databaseConnect = mysql_select_db($this->databaseConfig->database);
			}	
		} 
		catch (Exception $e) 
		{
			return $e;
		}
		
	}

	#run een query
	public function startQuery($runQuery)
	{
		try
		{
			if(empty($this->openConnect))
			{
				$this->openConnect();
				if($this->databaseConfig->connector == "mysql")
				{
					$this->startQuery = mysql_query($query);
				}
				$this->closeConnect();
				return $this->startQuery;
			}	
			else
			{
				if($this->databaseConfig->connector == "mysql")
				{
					$this->startQuery = mysql_query($query);
				}
				return $this->startQuery;
			}
		}
		catch (Exception $e)
		{
			return $e;	
		}
	}

	#sluit connectie
	public function closeConnect()
	{
		try {
			if($this->config->connector == "mysql")
			{
				mysql_close($this->connection);
			}
		} 
		catch (Exception $e) 
		{
			return $e;
		}
	}

	/*
	* ping server om te zien of deze open verbinding heeft.
	*/
	public function pingServer()
	{
		# code...
		try 
		{
			if($this->databaseConfig->connector == "mysql")
			{
				if(!mysql_ping($this->openConnect))
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
}

class databaseConfig
{
	public $hostname;
	public $username;
	public $pass;
	public $database;
	public $table;
	public $connector;

	function __construct($hostname = NULL, $username = NULL, $pass = NULL, $database = NULL, $prefix = NULL, $connector = NULL)
	{
		$this->hostname = !empty($hostname) ? $hostname : "";
		$this->username = !empty($username) ? $username : "";
		$this->pass = !empty($pass) ? $pass : "";
		$this->database = !empty($database) ? $database : "";
		$this->table = !empty($table) ? $table : "";
		$this->connector = !empty($connector) ? $connector : "";  //mysql of mysqli waarde opgeven (database)
	}

	function __destruct()
	{
		#destruction!
	}
}
?>