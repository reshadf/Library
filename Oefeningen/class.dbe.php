<?php
interface iDBConfigReader
{
	public function getHost();
	public function getUser();
	public function getPassword();
	public function getDatabase();
	public function getPort();
	public function getSocket();
}

interface iDBConfigWriter
{
	public function setHost();
	public function setUser();
	public function setPassword();
	public function setDatabase();
	public function setPort();
	public function setSocket();
}

final class MySQLConfiguration implements iDBConfigReader, iDBConfigWriter
{
	private
		  $host
		, $username
		, $password
		, $dbname
		, $port
		, $socket
	;
	
	public function __construct(
		  $host = NULL
		, $user = NULL
		, $pass = NULL
		, $db   = ""
		, $port = 3306
		, $sock = NULL
	)
	{
		$this->setHost($host);
		$this->setUser($user);
		$this->setPassword($pass);
		$this->setDatabase($db);
		$this->setPort($port);
		$this->setSocket($sock);
	}
	
	public function getHost()
	{
		return $this->host;
	}
	
	public function setHost($host = NULL)
	{
		if (NULL === $host)
		{
			$this->host = NULL;
		}
		elseif (!is_string($host))
		{
			$this->host = ini_get("mysqli.default_host");
		}
		else
		{
			$this->host = $host;
		}
		return $this->host;
	}
	
	public function getUser()
	{
		return $this->username;
	}
	
	public function setUser($user = NULL)
	{
		if (!is_string($user))
		{
			$user = ini_get("mysqli.default_user");
		}
		$this->username = $user;
		return $this->username;
	}
	
	public function getPass()
	{
		return $this->password;
	}
	
	public function setPass($pass = NULL)
	{
		if (NULL === $pass)
		{
			$this->password = NULL;
		}
		elseif (!is_string($pass))
		{
			$this->password = ini_get("mysqli.default_pw");
		}
		else
		{
			$this->password = $pass;
		}
		return $this->password;
	}
	
	public function getDatabase()
	{
		return $this->dbname;
	}
	
	public function setDatabase($db = NULL)
	{
		$this->dbname = (string) $db;
		return $this->dbname;
	}
	
	public function getPort()
	{
		return $this->port;
	}
	
	public function setPort($port = NULL)
	{
		if (!is_int($port))
		{
			$port = ini_get("mysqli.default_port");
		}
		$this->port = $port;
		return $this->port;
	}
	
	public function getSocket()
	{
		return $this->socket;
	}
	
	public function setSocket($sock = NULL)
	{
		if (!is_string($sock))
		{
			$sock = ini_get("mysqli.default_socket");
		}
		$this->socket = $sock;
		return $this->socket;
	}
}

interface iFactory
{
	public static function createDatabaseHandler(iDBConfigReader $conf);
}

class DBFactory implements iFactory
{
	public static function createDatabaseHandler(iDBConfigReader $conf)
	{
		try
		{
			return new MySQLi_Handler($conf);
		}
		catch (RuntimeException $e)
		{
			return new MySQL_Handler($conf);
		}
	}
}

interface iDBHandler
{
	// just an example, certainly more methods are needed in the final interface(s)
	public function query($sql);
}

class MySQL_Handler implements iDBHandler
{
	protected $connection = NULL;
	
	public function __construct(iDBConfigReader $conf)
	{
		$host = $conf->getHost();
		$user = $conf->getUser();
		$pass = $conf->getPassword();
		$db   = $conf->getDatabase();
		
		$cnx  = mysql_connect($host, $user, $pass);
		
		if (!$cnx or !mysql_connect_db($db, $cnx))
		{
			throw new RuntimeException(mysql_error(), mysql_errno());
		}
		$this->connection = $cnx;
	}
	
	public function __destruct()
	{
		mysql_close($this->connection);
	}
	
	public function query($sql)
	{
		// make the query using mysql
	}
}

/*
	try
{
	$database = new MySQLConfiguration("localhost", "root", "root", "testData");
	$db = DBFactory::createDatabaseHandler($database);
	$db->query("SELECT id, username, pass, age, gender FROM testTable");
	// …
}
catch (Exception $e)
{
	echo "<p>sorry, there is an error.</p>";
	send_error($e->getMessage());
}
*/


?>