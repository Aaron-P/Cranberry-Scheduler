<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

class LDAP
{
	private $handle;
	private $bind;
	private $username;

	public function __construct($hostname = null, $port = 389)
	{
		$this->handle = null;
		$this->bind = null;
		$this->username = null;
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
		$this->username = $username;
		return true;
	}

	public function getUserName()
	{
		if (is_null($this->handle))
			throw new Exception("Could not bind, not connected to a LDAP server.");
		if (is_null($this->bind))
			throw new Exception("Could not search ldap directory, no bind established.");
		if (is_null($this->username))
			throw new Exception("Could not search ldap directory, no username established.");

		$search = ldap_search($this->handle, "CN=".$this->username.",OU=Students,OU=SIUE Users,DC=campus,DC=siue,DC=edu", "(cn=".$this->username.")");
		$info = ldap_get_entries($this->handle, $search);
		if (isset($info[0]) && isset($info[0]["displayname"]) && isset($info[0]["displayname"][0]))
		{
			$name = explode(", ", $info[0]["displayname"][0]);
			return array("firstName" => $name[1], "lastName" => $name[0]);
		}
		return null;
	}

	public function __destruct()
	{
		if (!is_null($this->handle))
		{
			ldap_unbind($this->handle);
			$this->handle = null;
		}
	}
}
?>