<?php
/**
 * Defines the CookieSingleton and CookieHandler classes.
 * @file      CookieHandler.class.php
 * @author    Aaron Papp
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

/**
 * A wrapper class for PHP's set of COOKIE variables.
 * @class CookieSingleton
 * @todo  Add automatic handling of magic quotes (priority: low).
 */
class CookieSingleton
{
	private static $cookieInstance; /**< Holds the static instance of the CookieSingleton object. */

	/**
	 * Default constructor.
	 */
	private function __construct()
	{
		// TODO: Handle magic quotes.
	}

	/**
	 * Generates and/or returns the static instance of the CookieSingleton object.
	 * @return The CookieSingleton object instance.
	 */
	protected static function Instance()
	{
		if (!self::$cookieInstance)
			self::$cookieInstance = new CookieSingleton();
		return self::$cookieInstance;
	}

	/**
	 * Gets the value of a COOKIE variable.
	 * @param[in] $name The name of the variable.
	 * @return The value of the variable if it exists, otherwise NULL.
	 */
	public function get($name)
	{
		if ($this->exists($name))
			return $_COOKIE[$name];
		return null;
	}

//	/*
//	 * Sets the value of a COOKIE variable.
//	 * @param[in] $name  The name of the variable.
//	 * @param[in] $value The value to assign to the variable.
//	 */
//	public function set($name, $value)
//	{
//		$_COOKIE[$name] = $value;
//	}

	/**
	 * Checks if a COOKIE variable exists.
	 * @param[in] $name The name of the variable.
	 * @return True if it exists, otherwise false.
	 */
	public function exists($name)
	{
		return isset($_COOKIE[$name]);
	}
}

/**
 * A wrapper for the CookieSingleton class.
 * @class CookieHandler
 */
class CookieHandler extends CookieSingleton
{
	/**
	 * Constructs a single instance of the CookieSingleton object.
	 */
	public function __construct()
	{
		CookieSingleton::Instance();
	}
}
?>