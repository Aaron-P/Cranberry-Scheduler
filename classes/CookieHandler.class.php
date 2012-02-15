<?php
class CookieSingleton
{
	private static $cookieInstance;
	private function __construct()
	{
		// handle magic quotes?
	}
	protected static function Instance()
	{
		if (!self::$cookieInstance)
			self::$cookieInstance = new CookieSingleton();
		return self::$cookieInstance;
	}
	public function get($name)
	{
		if (exists($name))
			return $_COOKIE[$name];
		return null;
	}
	public function set($name, $value)
	{
		$_COOKIE[$name] = $value;
	}
	public function exists($name)
	{
		if (isset($_COOKIE[$name]))
			return true;
		return false;
	}	
}
class CookieHandler extends CookieSingleton
{
	public function __construct()
	{
		CookieSingleton::Instance();
	}
}
?>