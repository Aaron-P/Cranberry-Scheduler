<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Copyright (c) 2012 Aaron Papp                                               *
 *                    De'Liyuon Hamb                                           *
 *                    Shawn LeMaster                                           *
 *               All rights reserved.                                          *
 *                                                                             *
 * Developed by: Web Dynamics                                                  *
 *               Southern Illinois University Edwardsville                     *
 *               https://github.com/Aaron-P/Cranberry-Scheduler                *
 *                                                                             *
 * Permission is hereby granted, free of charge, to any person obtaining a     *
 * copy of this software and associated documentation files (the "Software"),  *
 * to deal with the Software without restriction, including without limitation *
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,    *
 * and/or sell copies of the Software, and to permit persons to whom the       *
 * Software is furnished to do so, subject to the following conditions:        *
 *   1. Redistributions of source code must retain the above copyright notice, *
 *      this list of conditions and the following disclaimers.                 *
 *   2. Redistributions in binary form must reproduce the above copyright      *
 *      notice, this list of conditions and the following disclaimers in the   *
 *      documentation and/or other materials provided with the distribution.   *
 *   3. Neither the names of Web Dynamics, Southern Illinois University        *
 *      Edwardsville, nor the names of its contributors may be used to endorse *
 *      or promote products derived from this Software without specific prior  *
 *      written permission.                                                    *
 *                                                                             *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR  *
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,    *
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL    *
 * THE CONTRIBUTORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR   *
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,       *
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER *
 * DEALINGS WITH THE SOFTWARE.                                                 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

require_once("GetHandler.class.php");
require_once("ServerHandler.class.php");

class ScriptUrls
{
	private $serverHandler;
	private $ssl;
	private $scheme;
	private $host;
	private $port;
	private $path;
	private $query;

	public function __construct()
	{
		$this->serverHandler = new ServerHandler();
		$this->ssl = $this->isSSL();
		$this->scheme = $this->getScheme();
		$this->host = $this->getHost();
		$this->port = $this->getPort();
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

	// TODO: Try to find some method to get a base folder url

	public function getScriptUrl()
	{
		return $this->scheme.$this->host.$this->port.$this->path;
	}

	public function getPageUrl()
	{
		return $this->scheme.$this->host.$this->port.$this->path.$this->query;
	}
}
?>