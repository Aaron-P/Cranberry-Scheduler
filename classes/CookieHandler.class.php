<?php

class CookieHandler
{
	private function stripMagicQuotes($value)
	{
		if (get_magic_quotes_gpc() === 1)
		{
			// TODO: Change this for proper array walking, etc.
			return stripslashes($value);
		}
	}
	public function getValue($name)
	{
		if (isset($name))
			return stripMagicQuotes($_COOKIE[$name]);
		return null;
	}
	public function setValue($name, $value)
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


?>