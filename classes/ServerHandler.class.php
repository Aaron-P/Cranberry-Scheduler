<?php
/**
 * Defines the ServerSingleton and ServerHandler classes.
 * @file      ServerHandler.class.php
 * @author    Aaron Papp
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

/**
 * A wrapper class for PHP's set of SERVER variables.
 * @class ServerSingleton
 */
class ServerSingleton
{
	/**
	 * Holds the static instance of the ServerSingleton object.
	 */
	private static $serverInstance;

	/**
	 * Default constructor.
	 */
	private function __construct()
	{
	}

	/**
	 * Generates and/or returns the static instance of the ServerSingleton object.
	 * @return The ServerSingleton object instance.
	 */
	protected static function Instance()
	{
		if (!self::$serverInstance)
			self::$serverInstance = new ServerSingleton();
		return self::$serverInstance;
	}

	/**
	 * Gets the value of a SERVER variable.
	 * @param[in] $name The name of the variable.
	 * @return The value of the variable if it exists, otherwise NULL.
	 */
	public function get($name)
	{
		if ($this->exists($name))
			return $_SERVER[$name];
		return null;
	}

//	/*
//	 * Sets the value of a SERVER variable.
//	 * @param[in] $name  The name of the variable.
//	 * @param[in] $value The value to assign to the variable.
//	 */
//	public function set($name, $value)
//	{
//		$_SERVER[$name] = $value;
//	}

	/**
	 * Checks if a SERVER variable exists.
	 * @param[in] $name The name of the variable.
	 * @return True if it exists, otherwise false.
	 */
	public function exists($name)
	{
		return isset($_SERVER[$name]);
	}
}

/**
 * A wrapper for the ServerSingleton class.
 * @class ServerHandler
 */
class ServerHandler extends ServerSingleton
{
	/**
	 * Constructs a single instance of the ServerSingleton object.
	 */
	public function __construct()
	{
		ServerSingleton::Instance();
	}
}
?>