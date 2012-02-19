<?php
class LDAP
{
	private $handle;
	private $bind;
	public function __construct($hostname = null, $port = 389)
	{
		$this->handle = null;
		$this->bind = null;
		if (!is_null($hostname))
			if (!$this->connect($hostname, $port))
				throw new Exception("Could not connect to LDAP server at {$hostname}:{$port}.");
	}
	public function connect($hostname, $port = 389)
	{
		$this->handle = ldap_connect($hostname, $port);
		if ($this->handle === false)
		{
			$this->handle = null;
			return false;
		}
		return true;
	}
	public function bind($username, $password)
	{
		if (is_null($this->handle))
			throw new Exception("Could not bind, not connected to a LDAP server.");
		$this->bind = @ldap_bind($this->handle, $username, $password);
		if ($this->bind === false)
		{
			$this->bind = null;
			return false;
		}
		return true;
	}
	public function __destruct()
	{
		if (!is_null($this->handle))
			ldap_unbind($this->handle);
	}
}
?>