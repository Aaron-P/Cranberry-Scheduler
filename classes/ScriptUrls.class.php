<?php
/**
 * Defines the ScriptUrls class.
 * @file      ScriptUrls.class.php
 * @author    Aaron Papp
 * @version   1.0
 * @date      2011-2012
 * @copyright University of Illinois/NCSA Open Source License
 */

require_once("GetHandler.class.php");
require_once("ServerHandler.class.php");

/**
 * Defines a useful set of URL generation methods, and a method for internal redirects.
 * @class ScriptUrls
 * @todo Add proper checks for port number inclusion.
 * @todo Change the query string fetching to use the GetHandler.
 */
class ScriptUrls
{
	private $serverHandler; /**< Holds an instance of a ServerSingleton object. */
	private $ssl; /**< Holds whether or not the visited page is using SSL/TLS. */
	private $scheme; /**< Holds the scheme of the visited page. (http(s)://) */
	private $host; /**< Holds the host name of the visited page. */
	private $port; /**< Holds the port number of the visited page. */
	private $base; /**< Holds the base path of the visited page. */
	private $path; /**< Holds the path of the visited page. */
	private $query; /**< Holds the query value of the visited page. */

	/**
	 * Initializes all of the internal variables for the object.
	 */
	public function __construct()
	{
		$this->serverHandler = new ServerHandler();
		$this->ssl = $this->isSSL();
		$this->scheme = $this->getScheme();
		$this->host = $this->getHost();
		$this->port = $this->getPort();
		$this->base = $this->getBase("\\..");
		$this->path = $this->getPath();
		$this->query = $this->getQuery();
	}

	/**
	 * Checks if the visited page was requested using SSL/TLS.
	 * @return False if the visited page is not using SSL/TLS, otherwise true.
	 */
	private function isSSL()
	{
		if (is_null($this->serverHandler->get("HTTPS")) || $this->serverHandler->get("HTTPS") === "off")
			return false;
		return true;
	}

	/**
	 * Gets the scheme of the visited page.
	 * @return The scheme of the visited page.
	 */
	private function getScheme()
	{
		if ($this->ssl)
			return "https://";
		return "http://";
	}

	/**
	 * Gets the host name of the visited page.
	 * @return The host name of the visited page.
	 */
	private function getHost()
	{
		if (!is_null($this->serverHandler->get("HTTP_HOST")))
			$host = $this->serverHandler->get("HTTP_HOST");
		else if (!is_null($this->serverHandler->get("SERVER_NAME")))
			$host = $this->serverHandler->get("SERVER_NAME");
		/*else
			throw*/
		return preg_replace("/[^a-zA-Z0-9.-]/", "", $host);
	}

	/**
	 * Gets the port number of the visited page if it is not default (80 or 443).
	 * @return The port number of the visited page.
	 */
	private function getPort()
	{
		// TODO: Properly handle hosts with non-default ports, the host value may already include it.
		//if ((!$this->ssl && $this->serverHandler->get("SERVER_PORT") === "80") || ($this->ssl && $this->serverHandler->get("SERVER_PORT") === "443"))
		//	return "";
		//return ":".preg_replace("/[^0-9]/", "", $this->serverHandler->get("SERVER_PORT"));
		return "";
	}

	/**
	 * Gets the base path of the scheduler.
	 * @param[in] $relativeLocation The relative path of the schedulers main folder from the servers root main folder.
	 * @return The base path of the scheduler.
	 */
	private function getBase($relativeLocation = "")
	{
		$base = "";
		if (!is_null($this->serverHandler->get("DOCUMENT_ROOT")))
			$base = str_replace("\\", "/", str_replace(realpath($this->serverHandler->get("DOCUMENT_ROOT")), "", realpath(dirname(__FILE__).$relativeLocation)));
		return implode("/", array_map("rawurlencode", explode("/", $base)));
	}

	/**
	 * Gets the path of the visited page.
	 * @return The path of the visited page.
	 */
	private function getPath()
	{
		if (!is_null($this->serverHandler->get("SCRIPT_NAME")))
			$path = $this->serverHandler->get("SCRIPT_NAME");
		else if (!is_null($this->serverHandler->get("DOCUMENT_ROOT")) && !is_null($this->serverHandler->get("SCRIPT_FILENAME")))
			$path = str_replace(rtrim(str_replace("\\", "/", $this->serverHandler->get("DOCUMENT_ROOT")), "/"), "", str_replace("\\", "/", realpath($this->serverHandler->get("SCRIPT_FILENAME"))));
		else if (!is_null($this->serverHandler->get("PHP_SELF")) && !is_null($this->serverHandler->get("PATH_INFO")))
			$path = str_replace($this->serverHandler->get("PATH_INFO"), "", $this->serverHandler->get("PHP_SELF"));
		else if (!is_null($this->serverHandler->get("PHP_SELF")) && !is_null($this->serverHandler->get("SCRIPT_FILENAME")))
			$path = preg_replace("/".($file = basename($this->serverHandler->get("SCRIPT_FILENAME"))).".*/", "", $this->serverHandler->get("PHP_SELF")).$file;
		/*else if (!empty($this->serverHandler->get("PHP_SELF")))
			$path = $this->serverHandler->get("PHP_SELF");*/
		/*else
			throw*/
		return implode("/", array_map("rawurlencode", explode("/", $path)));
	}

	/**
	 * Gets the query string of the visited page.
	 * @return The query string of the visited page.
	 */
	private function getQuery()
	{
		// TODO: Remake this to do things 'properly'
		$query = "";
		if (!empty($_GET))
		{
			$query = array();
			foreach ($_GET as $var => $val)
				array_push($query, rawurlencode($var)."=".rawurlencode($val));
			$query = "?".implode("&", $query);
		}
		return $query;
	}

	/**
	 * Generates a url to the root domain the site.
	 * @return The url to the root domain the site.
	 */
	public function getDomainUrl()
	{
		return $this->scheme.$this->host.$this->port."/";
	}

	/**
	 * Generates a url to the base folder of the scheduler.
	 * @return The url to the base folder of the scheduler.
	 */
	public function getBaseUrl()
	{
		return $this->scheme.$this->host.$this->port.$this->base."/";
	}

	/**
	 * Generates a base url to the visited page.
	 * @return The base url to the visited page.
	 */
	public function getScriptUrl()
	{
		return $this->scheme.$this->host.$this->port.$this->path;
	}

	/**
	 * Generates a full url to the visited page.
	 * @return The full url of the visited page.
	 */
	public function getPageUrl()
	{
		return $this->scheme.$this->host.$this->port.$this->path.$this->query;
	}

	/**
	 * Redirects the browser to a specific page.
	 * @param[in] $location The name of the script to redirect to.
	 * @param[in] $variables An array of GET variables to pass to the target script.
	 */
	public function redirectTo($location, $variables = array())
	{
		$getVariables = array();
		foreach ($variables as $variable => $value)
		{
			if (!is_null($value))
				array_push($getVariables, rawurlencode($variable)."=".rawurlencode($value));
		}
		if (!empty($getVariables))
			$getVariables = "?".implode("&", $getVariables);
		else
			$getVariables = "";

		header("Location: ".$this->getBaseUrl().$location.$getVariables);
		die();
	}
}
?>