<?php
/**
 * Defines the UserSession class.
 * @file      UserSession.class.php
 * @author    Aaron Papp
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

require_once("../config.php");
require_once("LDAP.class.php");
require_once("UserInfo.class.php");
require_once("SessionHandler.class.php");
require_once("DataManager.class.php");
define("USER_INFO_SESSION_VARIABLE", "UserInfo");

/**
 * Handles the storage and retrieval of user info and authentication for a session.
 * @class UserSession
 */
class UserSession
{
	private $serverInstance; /**< Holds an instance of a ServerSingleton object. */
	private $sessionInstance; /**< Holds an instance of a SessionSingleton object. */
	private $sessionSecure; /**< Holds whether or not the session is using extra security. */

	/**
	 * Gets a UserInfo object from the session if one exists.
	 * @return The UserInfo object instance if one exists, otherwise NULL.
	 */
	private function getInfoObject()
	{
		if (!is_null($userInfo = $this->sessionInstance->get(USER_INFO_SESSION_VARIABLE)))
			return $userInfo;
		return null;
	}

	/**
	 * Instantiates objects used by the class, creates a default UserInfo object if one does not exist.
	 * @param[in] $secure True if the session should use extra security, otherwise false.
	 */
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

	/**
	 * Authenticates a user against a configured LDAP server and generates a new user session.
	 * @param[in] $username The username of the user.
	 * @param[in] $password The password of the user.
	 * @return True if authentication succeeded, otherwise false.
	 */
	public function auth($username, $password)
	{
		if (!$this->check())
		{
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
			}
		}
		return false;
	}

	/**
	 * Destroys the existing user session.
	 */
	public function destroy()
	{
		// Do other stuff? CHECK USER LEVEL
		if (!is_null($userInfo = $this->getInfoObject()) &&
			!is_null($userInfo->getUsername()) &&
			!is_null($userInfo->getFirstName()) &&
			!is_null($userInfo->getLastName()))
			$this->sessionInstance->destroy();
	}

	/**
	 * Checks if the current user session is valid.
	 * @return True if the session is valid, otherwise false.
	 */
	public function check()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
		{
			$currentTime = time();
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

	/**
	 * Checks if the user associated with the current session is a volunteer.
	 * @return True if the user is a volunteer, otherwise false.
	 */
	public function isVolunteer()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->isVolunteer();
		return false;
	}

	/**
	 * Checks if the user associated with the current session is a researcher.
	 * @return True if the user is a researcher, otherwise false.
	 */
	public function isResearcher()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->isResearcher();
		return false;
	}

	/**
	 * Checks if the user associated with the current session is a teacher.
	 * @return True if the user is a teacher, otherwise false.
	 */
	public function isTeacher()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->isTeacher();
		return false;
	}

	/**
	 * Returns the username of the current user session if one exists.
	 * @return The username associated with the current session if one exists, otherwise false.
	 */
	public function getUsername()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->getUsername();
		return false;// False, null, or throw?
	}

	/**
	 * Returns the first name of the current user session if one exists.
	 * @return The first name associated with the current session if one exists, otherwise false.
	 */
	public function getFirstName()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->getFirstName();
		return false;// False, null, or throw?
	}

	/**
	 * Returns the last name of the current user session if one exists.
	 * @return The last name associated with the current session if one exists, otherwise false.
	 */
	public function getLastName()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->getLastName();
		return false;// False, null, or throw?
	}

	/**
	 * Returns the POST token of the current user session if one exists.
	 * @return The POST token associated with the current session if one exists, otherwise false.
	 */
	public function getPostToken()
	{
		if (!is_null($userInfo = $this->getInfoObject()))
			return $userInfo->getPostToken();
		return false;// False, null, or throw?
	}
}
?>