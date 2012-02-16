<?php
class ServerSingleton
{
	private static $serverInstance;
	private function __construct()
	{
		// handle magic quotes?
	}
	protected static function Instance()
	{
		if (!self::$serverInstance)
			self::$serverInstance = new ServerSingleton();
		return self::$serverInstance;
	}
	public function get($name)
	{
		if (exists($name))
			return $_SERVER[$name];
		return null;
	}
//	public function set($name, $value)
//	{
//		$_SERVER[$name] = $value;
//	}
	public function exists($name)
	{
		if (isset($_SERVER[$name]))
			return true;
		return false;
	}
}
class ServerHandler extends ServerSingleton
{
	public function __construct()
	{
		ServerSingleton::Instance();
	}
}
?>