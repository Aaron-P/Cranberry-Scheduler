<?php
class GetSingleton
{
	private static $getInstance;
	private function __construct()
	{
		// handle magic quotes?
	}
	protected static function Instance()
	{
		if (!self::$getInstance)
			self::$getInstance = new GetSingleton();
		return self::$getInstance;
	}
	public function get($name)
	{
		if ($this->exists($name))
			return $_GET[$name];
		return null;
	}
	public function set($name, $value)
	{
		$_GET[$name] = $value;
	}
	public function exists($name)
	{
		if (isset($_GET[$name]))
			return true;
		return false;
	}	
}
class GetHandler extends GetSingleton
{
	public function __construct()
	{
		GetSingleton::Instance();
	}
}
?>