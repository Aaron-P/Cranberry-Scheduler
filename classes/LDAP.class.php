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

class LDAP
{
	private $handle;
	private $bind;
	private $username;

	public function __construct($hostname = null, $port = 389)
	{
		$this->handle = null;
		$this->bind = null;
		$this->username = null;
		if (!is_null($hostname))
			if (!$this->connect($hostname, $port))
				throw new Exception("Could not connect to LDAP server at {$hostname}:{$port}.");
	}

	public function connect($hostname, $port = 389)
	{
		$this->handle = ldap_connect($hostname, $port);
		if ($this->handle === false)
		{
			$this->handle = null;
			return false;
		}
		return true;
	}

	public function bind($username, $password)
	{
		if (is_null($this->handle))
			throw new Exception("Could not bind, not connected to a LDAP server.");
		$this->bind = @ldap_bind($this->handle, $username, $password);
		if ($this->bind === false)
		{
			$this->bind = null;
			return false;
		}
		$this->username = $username;
		return true;
	}

	public function getUserName()
	{
		if (is_null($this->handle))
			throw new Exception("Could not bind, not connected to a LDAP server.");
		if (is_null($this->bind))
			throw new Exception("Could not search ldap directory, no bind established.");
		if (is_null($this->username))
			throw new Exception("Could not search ldap directory, no username established.");

		$search = ldap_search($this->handle, "CN=".$this->username.",OU=Students,OU=SIUE Users,DC=campus,DC=siue,DC=edu", "(cn=".$this->username.")");
		$info = ldap_get_entries($this->handle, $search);
		if (isset($info[0]) && isset($info[0]["displayname"]) && isset($info[0]["displayname"][0]))
		{
			$name = explode(", ", $info[0]["displayname"][0]);
			return array("firstName" => $name[1], "lastName" => $name[0]);
		}
		return null;
	}

	public function __destruct()
	{
		if (!is_null($this->handle))
		{
			ldap_unbind($this->handle);
			$this->handle = null;
		}
	}
}
?>