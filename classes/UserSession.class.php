<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

require_once("../config.php");
require_once("LDAP.class.php");
require_once("UserInfo.class.php");
require_once("SessionHandler.class.php");
require_once("DataManager.class.php");

class UserSession
{
	private $serverInstance;
	private $sessionInstance;
	private $sessionSecure;

	private function getInfoObject()
	{
		if (!is_null($userInfo = $this->sessionInstance->get(USER_INFO_SESSION_VARIABLE)))
			return $userInfo;
		return null;
	}

	public function __construct($secure = true)
	{
		$this->serverInstance = new ServerHandler();
		$this->sessionInstance = new SessionHandler();
		$this->sessionSecure = (bool)$secure;

		if (is_null($this->getInfoObject()))
		{
			$userInfo = new UserInfo(NULL, NULL, NULL, NULL);
			$this->sessionInstance->set(USER_INFO_SESSION_VARIABLE, $userInfo);
		}
	}

	public function auth($username, $password)
	{
		if (!$this->check())
		{
			/*
			$ldap = new LDAP();
			if ($ldap->connect(LDAP_SERVER) && $ldap->bind($username, $password))
			{
				$dataManager = new DataManager();

				if (!$dataManager->eidExists($username))
					$dataManager->addPerson($username, $userFullName["firstName"], $userFullName["lastName"], 1, 0, 0);

				$personInfo = $dataManager->getPersonInfo($username);
				$userPermissions = array(
					"volunteer"  => (bool)$personInfo["IsVolunteer"],
					"researcher" => (bool)$personInfo["IsResearcher"],
					"teacher"    => (bool)$personInfo["IsTeacher"]
				);

				if (!is_null($userFullName = $ldap->getUserName()))
					$userInfo = new UserInfo($username, $userFullName["firstName"], $userFullName["lastName"], $userPermissions);
				else
					$userInfo = new UserInfo($username, "", "", "");

				$this->sessionInstance->destroy();
				$this->sessionInstance->regenerate();
				$this->sessionInstance->set(USER_INFO_SESSION_VARIABLE, $userInfo);
				return true;
			}*/



			if (true)
			{
				$userFullName = array(
					"firstName" => "test",
					"lastName" => "name"
				);

				$dataManager = new DataManager();

				// This should be done with a transaction or some other means.
				if (!$dataManager->eidExists($username))
					$dataManager->addPerson($username, $userFullName["firstName"], $userFullName["lastName"], 1, 0, 0);

				$personInfo = $dataManager->getPersonInfo($username);

				$firstName = $personInfo['FirstName'];
				$lastName = $personInfo['LastName'];

				$userPermissions = array(
					"volunteer"  => (bool)$personInfo["IsVolunteer"],
					"researcher" => (bool)$personInfo["IsResearcher"],
					"teacher"    => (bool)$personInfo["IsTeacher"]
				);

				if (true)
					$userInfo = new UserInfo($username, $firstName, $lastName, $userPermissions);
				else
					$userInfo = new UserInfo($username, "", "", "");

				$this->sessionInstance->destroy();
				$this->sessionInstance->regenerate();
				$this->sessionInstance->set(USER_INFO_SESSION_VARIABLE, $userInfo);
				return true;
			}
		}
		return false;
	}

	public function destroy()
	{
		// Do other stuff? CHECK USER LEVEL
		if (!is_null($userInfo = $this->getInfoObject()) &&
			!is_null($userInfo->getUsername()) &&
			!is_null($userInfo->getFirstName()) &&
			!is_null($userInfo->getLastName()))
			$this->sessionInstance->destroy();
	}

	public function check()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
		{
			$currentTime = time();

//			!is_object($userInfo) ||
//			!($userInfo instanceof UserInfo) ||
// CHECK USER LEVEL

			if (is_null($userInfo->getUsername()) ||
				is_null($userInfo->getFirstName()) ||
				is_null($userInfo->getLastName()) ||
				$currentTime - SESSION_TIMEOUT_LIMIT > $userInfo->getLoginTime() ||
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

/*	public function getUserLevel()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->getUserLevel();
		return false;// False, null, or throw?
	}*/

	public function isVolunteer()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->isVolunteer();
		return false;
	}

	public function isResearcher()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->isResearcher();
		return false;
	}

	public function isTeacher()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->isTeacher();
		return false;
	}

	public function getUsername()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->getUsername();
		return false;// False, null, or throw?
	}

	public function getFirstName()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->getFirstName();
		return false;// False, null, or throw?
	}

	public function getLastName()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->getLastName();
		return false;// False, null, or throw?
	}

	public function getPostToken()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->getPostToken();
		return false;// False, null, or throw?
	}
}
?>