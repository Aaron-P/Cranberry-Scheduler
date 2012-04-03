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

require_once("LDAP.class.php");
require_once("UserInfo.class.php");

define("ACCESS_TIMEOUT_LIMIT", 30 * 60);
define("SESSION_TIMEOUT_LIMIT", 60 * 60);
define("USER_INFO_SESSION_VARIABLE", "UserInfo");

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
		if (!$this->check())
		{
			$ldap = new LDAP();
			if ($ldap->connect(LDAP_SERVER) && $ldap->bind($username, $password))
			{
				if (!is_null($userFullName = $ldap->getUserName()))
					$userInfo = new UserInfo($username, $userFullName["firstName"], $userFullName["lastName"], "");
				else
					$userInfo = new UserInfo($username, "", "", "");

				$this->sessionInstance->regenerate();
				$this->sessionInstance->set(USER_INFO_SESSION_VARIABLE, $userInfo);
				return true;
			}
		}
		return false;
	}

	public function destroy()
	{
		// Do other stuff?
		$this->sessionInstance->destroy();
	}

	public function check()
	{
		if (!is_null($userInfo = $this->sessionInstance->get(USER_INFO_SESSION_VARIABLE)))
		{
			$currentTime = time();

//			!is_object($userInfo) ||
//			!($userInfo instanceof UserInfo) ||

			if ($currentTime - SESSION_TIMEOUT_LIMIT > $userInfo->getLoginTime() ||
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

	public function getUserLevel()
	{
		if (!is_null($userInfo = $this->sessionInstance->get(USER_INFO_SESSION_VARIABLE)))
			return $userInfo->getUserLevel();
		return false;// False, null, or throw?
	}

	public function getUsername()
	{
		if (!is_null($userInfo = $this->sessionInstance->get(USER_INFO_SESSION_VARIABLE)))
			return $userInfo->getUsername();
		return false;// False, null, or throw?
	}

	public function getFirstName()
	{
		if (!is_null($userInfo = $this->sessionInstance->get(USER_INFO_SESSION_VARIABLE)))
			return $userInfo->getFirstName();
		return false;// False, null, or throw?
	}

	public function getLastName()
	{
		if (!is_null($userInfo = $this->sessionInstance->get(USER_INFO_SESSION_VARIABLE)))
			return $userInfo->getLastName();
		return false;// False, null, or throw?
	}
}
?>