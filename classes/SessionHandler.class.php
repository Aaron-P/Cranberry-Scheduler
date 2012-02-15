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
	public function get($var)
	{
		if (isset($_SESSION[$var]))
			return $_SESSION[$var];
		return null;
	}
	public function set($var, $val)
	{
		$_SESSION[$var] = $val;
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