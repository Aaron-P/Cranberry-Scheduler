<?php
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
				// Change to a class? Possible to store this obecting in $_SESSION?
				$userSession = array(
					"username"   => $username,
					"userLevel"  => "",
					"userAgent"  => $this->serverInstance->get("HTTP_USER_AGENT"),
					"ipAddress"  => $this->serverInstance->get("REMOTE_ADDR"),
					"loginTime"  => time(),
					"accessTime" => time()
				);
				$this->sessionInstance->regenerate();
				$this->sessionInstance->set("UserSession", $userSession);
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
		if (!is_null($userSession = $this->sessionInstance->get("UserSession")))
		{
			$currentTime = time();
			$accessTimeout = 30 * 60;
			$sessionTimeout = 60 * 60;

			if (!isset($userSession["username"]) ||
				!isset($userSession["userLevel"]) ||
				!isset($userSession["userAgent"]) ||
				!isset($userSession["ipAddress"]) ||
				!isset($userSession["loginTime"]) ||
				!isset($userSession["accessTime"]) ||
				$currentTime - $sessionTimeout > $userSession["loginTime"] ||
				$currentTime - $accessTimeout > $userSession["accessTime"] ||
				($this->sessionSecure && $userSession["userAgent"] !== $this->serverInstance->get("HTTP_USER_AGENT")) ||
				($this->sessionSecure && $userSession["ipAddress"] !== $this->serverInstance->get("REMOTE_ADDR")))
			{
				$this->destroy();
				return false;
			}
			else
			{
				$userSession["accessTime"] = $currentTime;
				$this->sessionInstance->set("UserSession", $userSession);
				return true;
			}
		}
		else
			return false;
	}
	public function getUserLevel()
	{
		if (!is_null($userSession = $this->sessionInstance->get("UserSession")))
			return $userSession["userLevel"];
		return false;// False, null, or throw?
	}
	public function getUsername()
	{
		if (!is_null($userSession = $this->sessionInstance->get("UserSession")))
			return $userSession["username"];
		return false;// False, null, or throw?
	}
}
?>