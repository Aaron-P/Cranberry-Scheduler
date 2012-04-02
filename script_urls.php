<?php
function generate_url($page = false)
{
	// Scheme
	$ssl = true;
	if (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] === "off")
		$ssl = false;
	$scheme = (($ssl) ? "https://" : "http://");

	// Host
	if (!empty($_SERVER["HTTP_HOST"]))
		$host = $_SERVER["HTTP_HOST"];
	else if (!empty($_SERVER["SERVER_NAME"]))
		$host = $_SERVER["SERVER_NAME"];
	else
		return false;
	$host = preg_replace("/[^a-zA-Z0-9.-]/", "", $host);

	// Port
	$port = ":".$_SERVER["SERVER_PORT"];
	if ((!$ssl && $_SERVER["SERVER_PORT"] === "80") || ($ssl && $_SERVER["SERVER_PORT"] === "443"))
		$port = "";
	$port = preg_replace("/[^0-9:]/", "", $port);

	// Path
	if (!empty($_SERVER["SCRIPT_NAME"]))
		$path = $_SERVER["SCRIPT_NAME"];
	else if (!empty($_SERVER["DOCUMENT_ROOT"]) && !empty($_SERVER["SCRIPT_FILENAME"]))
		$path = str_replace(rtrim(str_replace("\\", "/", $_SERVER["DOCUMENT_ROOT"]), "/"), "", str_replace("\\", "/", realpath($_SERVER["SCRIPT_FILENAME"])));
	else if (!empty($_SERVER["PHP_SELF"]) && !empty($_SERVER["PATH_INFO"]))
		$path = str_replace($_SERVER["PATH_INFO"], "", $_SERVER["PHP_SELF"]);
	else if (!empty($_SERVER["PHP_SELF"]) && !empty($_SERVER["SCRIPT_FILENAME"]))
		$path = preg_replace("/".($file = basename($_SERVER["SCRIPT_FILENAME"])).".*/", "", $_SERVER["PHP_SELF"]).$file;
	/*else if (!empty($_SERVER["PHP_SELF"]))
		$path = $_SERVER["PHP_SELF"];*/
	else
		return false;
	$path = implode("/", array_map("rawurlencode", explode("/", $path)));

	// Query
	$query = "";
	if ($page && !empty($_GET))
	{
		$query = array();
		foreach ($_GET as $var => $val)
			array_push($query, rawurlencode($var)."=".rawurlencode($val));
		$query = "?".implode("&", $query);
	}

	// Url
	return $scheme.$host.$port.$path.$query;
}

if (!defined("SCRIPT_URL"))
	define("SCRIPT_URL", generate_url(false));
if (!defined("PAGE_URL"))
	define("PAGE_URL", generate_url(true));

//echo SCRIPT_URL;
//echo "<br>";
//echo PAGE_URL;
?>