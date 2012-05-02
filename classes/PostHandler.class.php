<?php
/**
 * Defines the PostSingleton and PostHandler classes.
 * @file      PostHandler.class.php
 * @author    Aaron Papp
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

/**
 * A wrapper class for PHP's set of POST variables.
 * @class PostSingleton
 * @todo  Add automatic handling of magic quotes (priority: low).
 */
class PostSingleton
{
	/**
	 * Holds the static instance of the PostSingleton object.
	 */
	private static $postInstance;

	/**
	 * Default constructor.
	 */
	private function __construct()
	{
		// TODO: Handle magic quotes.
	}

	/**
	 * Generates and/or returns the static instance of the PostSingleton object.
	 * @return The PostSingleton object instance.
	 */
	protected static function Instance()
	{
		if (!self::$postInstance)
			self::$postInstance = new PostSingleton();
		return self::$postInstance;
	}

	/**
	 * Gets the value of a POST variable.
	 * @param[in] $name The name of the variable.
	 * @return The value of the variable if it exists, otherwise NULL.
	 */
	public function get($name)
	{
		if ($this->exists($name))
			return $_POST[$name];
		return null;
	}

//	/*
//	 * Sets the value of a POST variable.
//	 * @param[in] $name  The name of the variable.
//	 * @param[in] $value The value to assign to the variable.
//	 */
//	public function set($name, $value)
//	{
//		$_POST[$name] = $value;
//	}

	/**
	 * Checks if a POST variable exists.
	 * @param[in] $name The name of the variable.
	 * @return True if it exists, otherwise false.
	 */
	public function exists($name)
	{
		return isset($_POST[$name]);
	}
}

/**
 * A wrapper for the PostSingleton class.
 * @class PostHandler
 */
class PostHandler extends PostSingleton
{
	/**
	 * Constructs a single instance of the PostSingleton object.
	 */
	public function __construct()
	{
		PostSingleton::Instance();
	}
}
?>