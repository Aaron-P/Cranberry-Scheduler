<?php

define("dbHost", "127.0.0.1");
define("dbPort", "3306");
define("dbUser", "root");
define("dbPassword", "");
define("dbName", "cranberryscheduler");

class DBHandler
{
	private $host;
	private $port;
	private $user;
	private $password;
	private $dbName;
	private $dsn;

	public function __construct()
	{
		$this->host = dbHost;
		$this->port = dbPort;
		$this->user = dbUser;
		$this->password = dbPassword;
		$this->dbName = dbName;
		$this->dsn = 'mysql:host='		. $this->host
						. ';port='		. $this->port
						. ';dbname='	. $this->dbName;
	}

	public function query($sql, $arr)
	{
		try
		{
			$connection = new PDO($this->dsn, $this->user, $this->password);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sth = $connection->prepare($sql,
				array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$result = $sth->execute($arr);
			$connection = null;
			return $sth->fetchAll();
		}
		catch (PDOException $e)
		{
			echo "Database error: " . $e->getMessage() . "<br />";
		}
	}
}

?>
