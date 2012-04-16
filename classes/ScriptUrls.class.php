<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

require_once("GetHandler.class.php");
require_once("ServerHandler.class.php");

class ScriptUrls
{
	private $serverHandler;
	private $ssl;
	private $scheme;
	private $host;
	private $port;
	private $base;
	private $path;
	private $query;

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

	private function isSSL()
	{
		if (is_null($this->serverHandler->get("HTTPS")) || $this->serverHandler->get("HTTPS") === "off")
			return false;
		return true;
	}

	private function getScheme()
	{
		if ($this->ssl)
			return "https://";
		return "http://";
	}

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

	private function getPort()
	{
		if ((!$this->ssl && $this->serverHandler->get("SERVER_PORT") === "80") || ($this->ssl && $this->serverHandler->get("SERVER_PORT") === "443"))
			return "";
		return ":".preg_replace("/[^0-9]/", "", $this->serverHandler->get("SERVER_PORT"));
	}

	private function getBase($relativeLocation = "")
	{
		$base = "";
		if (!is_null($this->serverHandler->get("DOCUMENT_ROOT")))
			$base = str_replace("\\", "/", str_replace(realpath($this->serverHandler->get("DOCUMENT_ROOT")), "", realpath(dirname(__FILE__).$relativeLocation)));
		return implode("/", array_map("rawurlencode", explode("/", $base)));
	}

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

	public function getDomainUrl()
	{
		return $this->scheme.$this->host.$this->port."/";
	}

	public function getBaseUrl()
	{
		return $this->scheme.$this->host.$this->port.$this->base."/";
	}

	public function getScriptUrl()
	{
		return $this->scheme.$this->host.$this->port.$this->path;
	}

	public function getPageUrl()
	{
		return $this->scheme.$this->host.$this->port.$this->path.$this->query;
	}

	public function redirectTo($page, $return = NULL)
	{
		if (!is_null($return))
			$return = "&return=".$return;
		else
			$return = "";
		header("Location: ".$this->getBaseUrl()."index.php?page=".$page.$return);
		die();
	}
}
?>