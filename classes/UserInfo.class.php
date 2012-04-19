<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

require_once("ServerHandler.class.php");

class UserInfo
{
	private $serverInstance;
	private $username;
	private $firstName;
	private $lastName;
	private $userPermissions;
	private $userAgent;
	private $ipAddress;
	private $loginTime;
	private $accessTime;
	private $postToken;

	public function __construct($username, $firstName, $lastName, $userPermissions)
	{
		$currentTime = time();
		$this->serverInstance = new ServerHandler();
		$this->username = $username;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->userPermissions = $userPermissions;
		$this->userAgent = $this->serverInstance->get("HTTP_USER_AGENT");
		$this->ipAddress = $this->serverInstance->get("REMOTE_ADDR");
		$this->loginTime = $currentTime;
		$this->accessTime = $currentTime;
		$this->postToken = $this->generatePostToken();
	}

	private function generatePostToken()
	{
		// This should use something more secure like a random uuid generated from dev/random or something, but this should be fine for this.
		return sha1(mt_rand().microtime().$this->serverInstance->get("HTTP_USER_AGENT").$this->serverInstance->get("REMOTE_ADDR"));
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function isVolunteer()
	{
		if (isset($this->userPermissions["volunteer"]))
			return (bool)$this->userPermissions["volunteer"];
		return false;
	}

	public function isResearcher()
	{
		if (isset($this->userPermissions["researcher"]))
			return (bool)$this->userPermissions["researcher"];
		return false;
	}

	public function isTeacher()
	{
		if (isset($this->userPermissions["teacher"]))
			return (bool)$this->userPermissions["teacher"];
		return false;
	}

	public function getFirstName()
	{
		return $this->firstName;
	}

	public function getLastName()
	{
		return $this->lastName;
	}

	public function getUserAgent()
	{
		return $this->userAgent;
	}

	public function getIpAddress()
	{
		return $this->ipAddress;
	}

	public function getLoginTime()
	{
		return $this->loginTime;
	}

	public function getAccessTime()
	{
		return $this->accessTime;
	}

	public function setAccessTime($time)
	{
		if ($time >= $this->accessTime)
			$this->accessTime = $time;
	}

	public function getPostToken()
	{
		return $this->postToken;
	}
}
?>