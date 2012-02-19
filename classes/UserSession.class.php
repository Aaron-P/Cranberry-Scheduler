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

/*

<?php
session_start();

function login($username, $password, &$dbh)
{
	if (is_null($dbh))
		return FALSE;

	$query = $dbh->prepare("SELECT * FROM users WHERE username = :username AND password = :password LIMIT 1");
	$query->execute(array(":username" => $username, ":password" => sha1($password)));

	if ($query->rowCount())
	{
		$user = $query->fetch(PDO::FETCH_ASSOC);

		session_regenerate_id();
		$_SESSION["SESSION_USERNAME"]	= $username;
		$_SESSION["SESSION_IP"]		= $_SERVER["REMOTE_ADDR"];
		$_SESSION["SESSION_USERAGENT"]	= $_SERVER["HTTP_USER_AGENT"];
		$_SESSION["SESSION_TIME"]	= time();
		$_SESSION["SESSION_LOGGEDIN"]	= TRUE;
		$_SESSION["SESSION_ADMIN"]	= (bool)$user["admin"];
		return TRUE;
	}
	return FALSE;
}

function logout()
{
	session_unset();
	session_destroy();
	$_SESSION["SESSION_LOGGEDIN"] = FALSE;
}

function loggedIn()
{
	$timeout = 30 * 60;

	if (!isset($_SESSION["SESSION_USERNAME"]) ||
		!isset($_SESSION["SESSION_IP"]) ||
		!isset($_SESSION["SESSION_USERAGENT"]) ||
		!isset($_SESSION["SESSION_TIME"]) ||
		!isset($_SESSION["SESSION_LOGGEDIN"]) ||
		!isset($_SESSION["SESSION_ADMIN"]) ||
		$_SESSION["SESSION_IP"] !== $_SERVER["REMOTE_ADDR"] ||
		$_SESSION["SESSION_USERAGENT"] !== $_SERVER["HTTP_USER_AGENT"] ||
		time() - $timeout > $_SESSION["SESSION_TIME"] ||
		!$_SESSION["SESSION_LOGGEDIN"])
	{
		logout();
		return FALSE;
	}
	else
	{
		$_SESSION["SESSION_TIME"] = time();
		return TRUE;
	}
}
?>

*/

?>