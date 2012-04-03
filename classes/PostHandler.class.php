<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

class PostSingleton
{
	private static $postInstance;

	private function __construct()
	{
		// handle magic quotes?
	}

	protected static function Instance()
	{
		if (!self::$postInstance)
			self::$postInstance = new PostSingleton();
		return self::$postInstance;
	}

	public function get($name)
	{
		if ($this->exists($name))
			return $_POST[$name];
		return null;
	}

//	public function set($name, $value)
//	{
//		$_POST[$name] = $value;
//	}

	public function exists($name)
	{
		if (isset($_POST[$name]))
			return true;
		return false;
	}
}

class PostHandler extends PostSingleton
{
	public function __construct()
	{
		PostSingleton::Instance();
	}
}
?>