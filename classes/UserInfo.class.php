<?php
/**
 * Defines the UserInfo class.
 * @file      UserInfo.class.php
 * @author    Aaron Papp
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

require_once("ServerHandler.class.php");

/**
 * Stores information about a user.
 * @class UserInfo
 * @todo Change the POST token generation to use a more secure method.
 */
class UserInfo
{
	private $serverInstance; /**< Holds an instance of a ServerSingleton object. */
	private $username; /**< Holds the username of the user. */
	private $firstName; /**< Holds the first name of the user. */
	private $lastName; /**< Holds the last name of the user. */
	private $userPermissions; /**< Holds the permissions array of the user. */
	private $userAgent; /**< Holds the user agent of the user's browser. */
	private $ipAddress; /**< Holds the IP address of the user's computer. */
	private $loginTime; /**< Holds the time the user logged in. */
	private $accessTime; /**< Holds the time the user last accessed a page. */
	private $postToken; /**< Holds the POST token of the user's current session. */

	/**
	 * Constructs the UserInfo object.
	 * @param[in] $username The username of the user.
	 * @param[in] $firstName The first name of the user.
	 * @param[in] $lastName The last name of the user.
	 * @param[in] $userPermissions The permissions array of the user.
	 */
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

	/**
	 * Generates a string to act as a POST token for CSRF protection.
	 * @return A POST token string.
	 */
	private function generatePostToken()
	{
		// This should use something more secure like a random uuid generated from dev/random or something, but this should be fine for this.
		return sha1(mt_rand().microtime().$this->serverInstance->get("HTTP_USER_AGENT").$this->serverInstance->get("REMOTE_ADDR"));
	}

	/**
	 * Gets the username of the user.
	 * @return The user's username.
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Checks if the user has volunteer permissions.
	 * @return True if the user is a volunteer, otherwise false.
	 */
	public function isVolunteer()
	{
		if (isset($this->userPermissions["volunteer"]))
			return (bool)$this->userPermissions["volunteer"];
		return false;
	}

	/**
	 * Checks if the user has researcher permissions.
	 * @return True if the user is a researcher, otherwise false.
	 */
	public function isResearcher()
	{
		if (isset($this->userPermissions["researcher"]))
			return (bool)$this->userPermissions["researcher"];
		return false;
	}

	/**
	 * Checks if the user has teacher permissions.
	 * @return True if the user is a teacher, otherwise false.
	 */
	public function isTeacher()
	{
		if (isset($this->userPermissions["teacher"]))
			return (bool)$this->userPermissions["teacher"];
		return false;
	}

	/**
	 * Gets the first name of the user.
	 * @return The user's first name.
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * Gets the last name of the user.
	 * @return The user's last name.
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * Gets the user agent of the user's browser.
	 * @return The user's browser's user agent.
	 */
	public function getUserAgent()
	{
		return $this->userAgent;
	}

	/**
	 * Gets the IP address of the user's computer.
	 * @return The user's computer's IP address.
	 */
	public function getIpAddress()
	{
		return $this->ipAddress;
	}

	/**
	 * Gets the login time of the user.
	 * @return The user's login time as a unix timestamp.
	 */
	public function getLoginTime()
	{
		return $this->loginTime;
	}

	/**
	 * Gets the last access time of the user.
	 * @return The user's last access time as a unix timestamp.
	 */
	public function getAccessTime()
	{
		return $this->accessTime;
	}

	/**
	 * Sets the last access time of the user.
	 * @param[in] $time The time to set as the last access time.
	 */
	public function setAccessTime($time)
	{
		if ($time >= $this->accessTime)
			$this->accessTime = $time;
	}

	/**
	 * Gets the POST token of the user.
	 * @return The user's POST token.
	 */
	public function getPostToken()
	{
		return $this->postToken;
	}
}
?>