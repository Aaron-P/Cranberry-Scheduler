<?php
define("DB_DEFAULT_HOST",   "127.0.0.1");
define("DB_DEFAULT_DRIVER", "mysql");

class DBSingleton
{
	private static $dbInstance;
	private static $pdo;
	private $driver;
	private $database;
	private $host;
	private $port;
	private $username;
	private $password;

	private function __construct($database, $host = DB_DEFAULT_HOST, $port = null, $username = null, $password = null, $driver = DB_DEFAULT_DRIVER)
	{
		$this->driver = $driver;
		$this->database = $database;
		$this->host = $host;
		$this->port = $port;
		$this->username = $username;
		$this->password = $password;
		$this->connect();
	}

	private function connect()
	{
		$dsn = $this->driver.":host=".$this->host.";dbname=".$this->database;
		if (!is_null($this->port))
			$dsn .= ";port=".$this->port;
		try
		{
			self::$pdo = new PDO($dsn, $this->username, $this->password);
			// self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e)
		{
			// handle exception, we should probably rethrow and kill the object so we can attempt to remake.
		}
	}

	private function disconnect()
	{
		self::$pdo = null;
	}

	protected static function Instance($database, $host = DB_DEFAULT_HOST, $port = null, $username = null, $password = null, $driver = DB_DEFAULT_DRIVER)
	{
		if (!self::$dbInstance)
			self::$dbInstance = new DBSingleton($database, $host, $port, $username, $password);
		return self::$dbInstance;
	}

	public function query($sql, $variables = null)
	{
		// array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL)
		if (($statement = self::$pdo->prepare($sql)) !== false)
			if ($statement->execute($variables))
				return $statement->fetchAll();
		return null;
	}
}

class DBHandler extends DBSingleton
{
	public function __construct($database, $host = DB_DEFAULT_HOST, $port = null, $username = null, $password = null, $driver = DB_DEFAULT_DRIVER)
	{
		DBSingleton::Instance($database, $host, $port, $username, $password);
	}
}
?>
