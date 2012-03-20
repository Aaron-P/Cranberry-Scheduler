<?php
require_once("LDAP.class.php");
require_once("UserInfo.class.php");

define("ACCESS_TIMEOUT_LIMIT", 30 * 60);
define("SESSION_TIMEOUT_LIMIT", 60 * 60);
define("USER_INFO_SESSION_VARIABLE", "UserInfo");

class UserSession
{
	private $serverInstance;
	private $sessionInstance;
	private $sessionSecure;

	public function __construct($secure = true)
	{
		$this->serverInstance = new ServerHandler();
		$this->sessionInstance = new SessionHandler();
		$this->sessionSecure = (bool)$secure;
	}

	public function auth($username, $password)
	{
		if (!$this->check())
		{
			$ldap = new LDAP();
			if ($ldap->connect(LDAP_SERVER) && $ldap->bind($username, $password))
			{
				$userInfo = new UserInfo($username, "", "");
				$this->sessionInstance->regenerate();
				$this->sessionInstance->set(USER_INFO_SESSION_VARIABLE, $userInfo);
				return true;
			}
		}
		return false;
	}

	public function destroy()
	{
		// Do other stuff?
		$this->sessionInstance->destroy();
	}

	public function check()
	{
		if (!is_null($userInfo = $this->sessionInstance->get(USER_INFO_SESSION_VARIABLE)))
		{
			$currentTime = time();

//			!is_object($userInfo) ||
//			!($userInfo instanceof UserInfo) ||

			if ($currentTime - SESSION_TIMEOUT_LIMIT > $userInfo->getLoginTime() ||
				$currentTime - ACCESS_TIMEOUT_LIMIT > $userInfo->getAccessTime() ||
				($this->sessionSecure && $userInfo->getUserAgent() !== $this->serverInstance->get("HTTP_USER_AGENT")) ||
				($this->sessionSecure && $userInfo->getIpAddress() !== $this->serverInstance->get("REMOTE_ADDR")))
			{
				$this->destroy();
				return false;
			}
			else
			{
				$userInfo->setAccessTime($currentTime);
				$this->sessionInstance->set(USER_INFO_SESSION_VARIABLE, $userInfo);
				return true;
			}
		}
		else
			return false;
	}

	public function getUserLevel()
	{
		if (!is_null($userInfo = $this->sessionInstance->get(USER_INFO_SESSION_VARIABLE)))
			return $userInfo->getUserLevel();
		return false;// False, null, or throw?
	}

	public function getUsername()
	{
		if (!is_null($userInfo = $this->sessionInstance->get(USER_INFO_SESSION_VARIABLE)))
			return $userInfo->getUsername();
		return false;// False, null, or throw?
	}

	public function getName()
	{
		if (!is_null($userInfo = $this->sessionInstance->get(USER_INFO_SESSION_VARIABLE)))
			return $userInfo->getName();
		return false;// False, null, or throw?
	}
}
?>