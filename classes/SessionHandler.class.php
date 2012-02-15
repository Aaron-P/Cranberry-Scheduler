<?php
class SessionSingleton
{
	private static $sessionInstance;
	private function __construct()
	{
		session_start();
	}
	protected static function Instance()
	{
		if (!self::$sessionInstance)
			self::$sessionInstance = new SessionSingleton();
		return self::$sessionInstance;
	}
	public function get($name)
	{
		if (exists($name))
			return $_SESSION[$name];
		return null;
	}
	public function set($name, $value)
	{
		$_SESSION[$name] = $value;
	}
	public function exists($name)
	{
		if (isset($_SESSION[$name]))
			return true;
		return false;		
	}
}
class SessionHandler extends SessionSingleton
{
	public function __construct()
	{
		SessionSingleton::Instance();
	}
}
?>