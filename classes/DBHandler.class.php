<?php
/**
 * Defines the DBSingleton and DBHandler classes.
 * @file      DBHandler.class.php
 * @author    Aaron Papp
 * @author    Shawn LeMaster
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

/**
 * The default database host.
 */
define("DB_DEFAULT_HOST",   "127.0.0.1");
/**
 * The default PDO driver.
 */
define("DB_DEFAULT_DRIVER", "mysql");

/**
 * A wrapper class for PHP's PDO class.
 * @class DBSingleton
 * @todo Add transaction support.
 * @todo Change how PDO objects are instantiated to properly handle exceptions.
 */
class DBSingleton
{
	private static $dbInstance; /**< Holds the static instance of the DBSingleton object. */
	private static $pdo; /**< Holds a static instance of the PDO object. */
	private $driver; /**< Holds the driver string for the PDO object. */
	private $database; /**< Holds the database name. */
	private $host; /**< Holds the database host name. */
	private $port; /**< Holds the database port number. */
	private $username; /**< Holds the database username. */
	private $password; /**< Holds the database password. */

	/**
	 * Constructs the DBSingleton object, connects a specified database through PHP PDO.
	 * @param[in] $database The name of the database to open.
	 * @param[in] $host     The host name to connect to the database with.
	 * @param[in] $port     The port number to connect to the database with.
	 * @param[in] $username The username to connect to the database with.
	 * @param[in] $password The password to connect to the database with.
	 * @param[in] $driver   The PDO driver to connect to the database with.
	 */
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

	/**
	 * Connects to a specified database using a PDO object as a static instance.
	 */
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

	/**
	 * Disconnects from the database.
	 */
	private function disconnect()
	{
		self::$pdo = null;
	}

	/**
	 * Generates and/or returns the static instance of the DBSingleton object.
	 * @param[in] $database The name of the database to open.
	 * @param[in] $host     The host name to connect to the database with.
	 * @param[in] $port     The port number to connect to the database with.
	 * @param[in] $username The username to connect to the database with.
	 * @param[in] $password The password to connect to the database with.
	 * @param[in] $driver   The PDO driver to connect to the database with.
	 * @return The DBSingleton object instance.
	 */
	protected static function Instance($database, $host = DB_DEFAULT_HOST, $port = null, $username = null, $password = null, $driver = DB_DEFAULT_DRIVER)
	{
		if (!self::$dbInstance)
			self::$dbInstance = new DBSingleton($database, $host, $port, $username, $password);
		return self::$dbInstance;
	}

	/**
	 * Executes a prepared SQL statement.
	 * @param[in] $sql       The parameterized SQL statement to execute.
	 * @param[in] $variables The array of parameters and their values.
	 * @return The results if the execution was successful, otherwise NULL.
	 */
	public function query($sql, $variables = array())
	{
		// array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL)
		if (($statement = self::$pdo->prepare($sql)) !== false)
			if ($statement->execute($variables))
				return $statement->fetchAll(PDO::FETCH_ASSOC);
		return null;
	}
}

/**
 * A wrapper for the DBSingleton class.
 * @class DBHandler
 */
class DBHandler extends DBSingleton
{
	/**
	 * Constructs a single instance of the DBSingleton object.
	 */
	public function __construct($database, $host = DB_DEFAULT_HOST, $port = null, $username = null, $password = null, $driver = DB_DEFAULT_DRIVER)
	{
		DBSingleton::Instance($database, $host, $port, $username, $password);
	}
}
?>
