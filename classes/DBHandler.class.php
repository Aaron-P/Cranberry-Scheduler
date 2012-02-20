<?php

class DBSingleton
{
	private static $dbInstance;
	private $connection = false;
	private $host = "localhost:3306";
	private $userName = "root";
	private $password = "";
	private $dbName = "cranberryscheduler";

	private function __construct()
	{
		$this->connect();
	}

	private function connect()
	{
		$this->connection = mysql_connect($this->host, $this->userName, $this->password);
		if ($this->connection)
		{
			if (mysql_select_db($this->dbName))
				return true;
			$this->disconnect();
		}
		return false;
	}

	private function disconnect()
	{
		if ($this->connection)
			return mysql_close($this->connection);
	}

	protected static function Instance()
	{
		if (!self::$dbInstance)
			self::$dbInstance = new DBSingleton();
		return self::$dbInstance;
	}

	public function query($sql)
	{
		$escSql = mysql_real_escape_string($sql);
		return mysql_query($escSql);
	}
}

class DBHandler extends DBSingleton
{
	public function __construct()
	{
		DBSingleton::Instance();
	}
}

?>
