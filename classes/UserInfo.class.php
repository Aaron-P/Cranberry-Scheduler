<?php
require_once("ServerHandler.class.php");

class UserInfo
{
	private $serverInstance;
	private $username;
	private $name;
	private $userLevel;
	private $userAgent;
	private $ipAddress;
	private $loginTime;
	private $accessTime;

	public function __construct($username, $name, $userLevel)
	{
		$currentTime = time();
		$this->serverInstance = new ServerHandler();
		$this->username = $username;
		$this->name = $name;
		$this->userLevel = $userLevel;
		$this->userAgent = $this->serverInstance->get("HTTP_USER_AGENT");
		$this->ipAddress = $this->serverInstance->get("REMOTE_ADDR");
		$this->loginTime = $currentTime;
		$this->accessTime = $currentTime;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getUserLevel()
	{
		return $this->userLevel;
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
}
?>