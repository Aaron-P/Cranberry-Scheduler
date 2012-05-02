<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

require_once("../config.php");

if (!defined("ACCESS_TIMEOUT_LIMIT"))
	define("ACCESS_TIMEOUT_LIMIT", 30 * 60);
if (!defined("SESSION_TIMEOUT_LIMIT"))
	define("SESSION_TIMEOUT_LIMIT", 60 * 60);
if (!defined("USER_INFO_SESSION_VARIABLE"))
	define("USER_INFO_SESSION_VARIABLE", "UserInfo");


define("DB_DEFAULT_HOST",   "127.0.0.1");
define("DB_DEFAULT_DRIVER", "mysql");
if (!defined("DB_HOSTNAME"))
	define("DB_HOSTNAME", "");
if (!defined("DB_USERNAME"))
	define("DB_USERNAME", "");
if (!defined("DB_PASSWORD"))
	define("DB_PASSWORD", "");
if (!defined("DB_DATABASE"))
	define("DB_DATABASE", "");

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

	public function query($sql, $variables = array())
	{
		// array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL)
		if (($statement = self::$pdo->prepare($sql)) !== false)
			if ($statement->execute($variables))
				return $statement->fetchAll(PDO::FETCH_ASSOC);
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
