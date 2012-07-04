<?php

class dbConfig
{
    protected $host = false;
    protected $username = false;
    protected $password = false;


	public function __construct($host, $username, $password)
	{
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
	}
	
	public function getHost()
	{
		return $this->host;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getPassword()
	{
		return $this->password;
	}
	
	function __destruct()
	{
		#destruction!
	} 
}

abstract class dbConnect
{
	public $dbConfig;

	public function __construct($dbConfig)
	{
		$this->dbConfig = $dbConfig;
	}

}

class dbConnectMySQL extends dbConnect
{
	public $database;
	public $startQuery;
	public $getResults;


	public function openConnection()
	{
		try
                {
                    $this->dbConnect = mysql_connect($this->host, $this->username, $this->password);
                    $this->dbConnect = mysql_select_db($this->database);
                }
                catch (Exception $e)
                {
                        return $e;
                }
	}

    public function startQuery($query)
    {
            try
            {
                    if(empty($this->openConnection))
                    {
                            $this->openConnection();

                                    $this->startQuery = mysql_query($query);

                            return $this->startQuery;
                    }      
                    else
                    {
                           
                                    $this->startQuery = mysql_query($query);

                            return $this->startQuery;
                    }
            }
            catch (Exception $e)
            {
                    return $e;     
            }
    }

    public function getResults()
    {
            try
            {
                	return mysql_fetch_assoc($result);
            }
            catch (Exception $e)
            {
                    return $e;     
            }
    }

    public function __destruct()
    {
    	#destruction
    }
}

?>