<?php
/**
 * Defines the LDAP class.
 * @file      LDAP.class.php
 * @author    Aaron Papp
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

/**
 * Facilitates the use of LDAP for authentication and information retrieval.
 * @class LDAP
 * @todo  Add a check to fetch whether or not the user is a teacher.
 */
class LDAP
{
	private $handle; /**< Holds a handle to an LDAP server. */
	private $bind; /**< Holds a bind to an LDAP server. */
	private $username; /**< Holds the username of the user being authenticated. */

	/**
	 * Constructs the LDAP object, connects to a specified LDAP server.
	 * @param[in] $hostname The host name of the ldap server to connect to.
	 * @param[in] $port     The port number of the LDAP server to connect to.
	 */
	public function __construct($hostname = null, $port = 389)
	{
		$this->handle = null;
		$this->bind = null;
		$this->username = null;
		if (!is_null($hostname))
			if (!$this->connect($hostname, $port))
				throw new Exception("Could not connect to LDAP server at {$hostname}:{$port}.");
	}

	/**
	 * Connects to a specified LDAP server.
	 * @param[in] $hostname The host name of the ldap server to connect to.
	 * @param[in] $port     The port number of the LDAP server to connect to.
	 * @return True if the LDAP connection was established, otherwise false.
	 */
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

	/**
	 * Attempts to bind to the connected LDAP server with a specified username and password.
	 * @param[in] $username The username to attempt to bind with.
	 * @param[in] $password The password to attempt to bind with.
	 * @return True if the bind was successful, otherwise false.
	 */
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

	/**
	 * Attempts to retrieve the user's first and last name from the LDAP server.
	 * @return An array of the user's first and last name if available, otherwise NULL.
	 */
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

	/**
	 * Destroys the object, unbinds from the LDAP server.
	 */
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