<?php
/**
 * Defines the SessionSingleton and SessionHandler classes.
 * @file      SessionHandler.class.php
 * @author    Aaron Papp
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

/**
 * A wrapper class for PHP's set of SESSION variables.
 * @class SessionSingleton
 */
class SessionSingleton
{
	/**
	 * Holds the static instance of the SessionSingleton object.
	 */
	private static $sessionInstance;

	/**
	 * Default constructor, starts the session.
	 */
	private function __construct()
	{
		session_start();
	}

	/**
	 * Generates and/or returns the static instance of the SessionSingleton object.
	 * @return The SessionSingleton object instance.
	 */
	protected static function Instance()
	{
		if (!self::$sessionInstance)
			self::$sessionInstance = new SessionSingleton();
		return self::$sessionInstance;
	}

	/**
	 * Gets the value of a SESSION variable.
	 * @param[in] $name The name of the variable.
	 * @return The value of the variable if it exists, otherwise NULL.
	 */
	public function get($name)
	{
		if ($this->exists($name))
			return $_SESSION[$name];
		return null;
	}

	/**
	 * Sets the value of a SESSION variable.
	 * @param[in] $name  The name of the variable.
	 * @param[in] $value The value to assign to the variable.
	 */
	public function set($name, $value)
	{
		$_SESSION[$name] = $value;
	}

	/**
	 * Checks if a SESSION variable exists.
	 * @param[in] $name The name of the variable.
	 * @return True if it exists, otherwise false.
	 */
	public function exists($name)
	{
		return isset($_SESSION[$name]);
	}

	/**
	 * Regenerates the session with a new id.
	 */
	public function regenerate()
	{
		session_regenerate_id();
	}

	/**
	 * Destroys the current session and starts a new one.
	 */
	public function destroy()
	{
		session_unset();
		session_destroy();
		session_start();
	}
}

/**
 * A wrapper for the SessionSingleton class.
 * @class SessionHandler
 */
class SessionHandler extends SessionSingleton
{
	/**
	 * Constructs a single instance of the SessionSingleton object.
	 */
	public function __construct()
	{
		SessionSingleton::Instance();
	}
}
?>