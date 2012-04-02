/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Copyright (c) 2012 Aaron Papp                                               *
 *                    De'Liyuon Hamb                                           *
 *                    Shawn LeMaster                                           *
 *               All rights reserved.                                          *
 *                                                                             *
 * Developed by: Web Dynamics                                                  *
 *               Southern Illinois University Edwardsville                     *
 *               https://github.com/Aaron-P/Cranberry-Scheduler                *
 *                                                                             *
 * Permission is hereby granted, free of charge, to any person obtaining a     *
 * copy of this software and associated documentation files (the "Software"),  *
 * to deal with the Software without restriction, including without limitation *
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,    *
 * and/or sell copies of the Software, and to permit persons to whom the       *
 * Software is furnished to do so, subject to the following conditions:        *
 *   1. Redistributions of source code must retain the above copyright notice, *
 *      this list of conditions and the following disclaimers.                 *
 *   2. Redistributions in binary form must reproduce the above copyright      *
 *      notice, this list of conditions and the following disclaimers in the   *
 *      documentation and/or other materials provided with the distribution.   *
 *   3. Neither the names of Web Dynamics, Southern Illinois University        *
 *      Edwardsville, nor the names of its contributors may be used to endorse *
 *      or promote products derived from this Software without specific prior  *
 *      written permission.                                                    *
 *                                                                             *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR  *
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,    *
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL    *
 * THE CONTRIBUTORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR   *
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,       *
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER *
 * DEALINGS WITH THE SOFTWARE.                                                 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

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
