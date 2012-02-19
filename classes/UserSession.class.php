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
		if (!$this->check()) // ldap auth check? // check not already authed?
		{
			$ldap = new LDAP();
			if ($ldap->connect(LDAP_SERVER) && $ldap->bind($username, $password))
			{
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
		//do other stuff
		$this->sessionInstance->destroy();
	}
	public function check()
	{
		$userSession = $this->sessionInstance->get("UserSession");
		if (!is_null($userSession))
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
		return $this->sessionInstance->get("");
	}
	public function getUsername()
	{
		
	}
}
?>