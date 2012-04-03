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

class SessionSingleton
{
	private static $sessionInstance;

	private function __construct()
	{
		session_start();
	}

	protected static function Instance()
	{
		if (!self::$sessionInstance)
			self::$sessionInstance = new SessionSingleton();
		return self::$sessionInstance;
	}

	public function get($name)
	{
		if ($this->exists($name))
			return $_SESSION[$name];
		return null;
	}

	public function set($name, $value)
	{
		$_SESSION[$name] = $value;
	}

	public function exists($name)
	{
		if (isset($_SESSION[$name]))
			return true;
		return false;
	}

	public function regenerate()
	{
		session_regenerate_id();
	}

	public function destroy()
	{
		session_unset();
		session_destroy();
	}
}

class SessionHandler extends SessionSingleton
{
	public function __construct()
	{
		SessionSingleton::Instance();
	}
}
?>