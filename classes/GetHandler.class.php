<?php
/**
 * Defines the GetSingleton and GetHandler classes.
 * @file      GetHandler.class.php
 * @author    Aaron Papp
 * @version   1.0
 * @date      2011-2012
 * @cop
 * @copyright University of Illinois/NCSA Open Source License
 */

/**
 * A wrapper class for PHP's set of GET variables.
 * @class GetSingleton
 * @todo  Add automatic handling of magic quotes (priority: low).
 */
class GetSingleton
{
	/**
	 * Holds the static instance of the GetSingleton object.
	 */
	private static $getInstance;

	/**
	 * Default constructor.
	 */
	private function __construct()
	{
		// TODO: Handle magic quotes.
	}

	/**
	 * Generates and/or returns the static instance of the GetSingleton object.
	 * @return The GetSingleton object instance.
	 */
	protected static function Instance()
	{
		if (!self::$getInstance)
			self::$getInstance = new GetSingleton();
		return self::$getInstance;
	}

	/**
	 * Gets the value of a GET variable.
	 * @param[in] $name The name of the variable.
	 * @return The value of the variable if it exists, otherwise NULL.
	 */
	public function get($name)
	{
		if ($this->exists($name))
			return $_GET[$name];
		return null;
	}

//	/*
//	 * Sets the value of a GET variable.
//	 * @param[in] $name  The name of the variable.
//	 * @param[in] $value The value to assign to the variable.
//	 */
//	public function set($name, $value)
//	{
//		$_GET[$name] = $value;
//	}

	/**
	 * Checks if a GET variable exists.
	 * @param[in] $name The name of the variable.
	 * @return True if it exists, otherwise false.
	 */
	public function exists($name)
	{
		return isset($_GET[$name]);
	}
}

/**
 * A wrapper for the GetSingleton class.
 * @class GetHandler
 */
class GetHandler extends GetSingleton
{
	/**
	 * Constructs a single instance of the GetSingleton object.
	 */
	public function __construct()
	{
		GetSingleton::Instance();
	}
}
?>