<?php

class GetHandler
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
		if (exists($name))
			return stripMagicQuotes($_GET[$name]);
		return null;
	}
	public function setValue($name, $value)
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

?>