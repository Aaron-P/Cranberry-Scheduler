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

error_reporting(E_ALL | E_STRICT);
require_once("classes/smarty/Smarty.class.php");
require_once("classes/PostHandler.class.php");
//require_once("classes/GetHandler.class.php");
require_once("classes/SessionHandler.class.php");
require_once("classes/DataManager.class.php");

$ph = new PostHandler();
$nl = "<br />";

if ($ph->exists('cancel'))
{
	header('Location: http://localhost/Cranberry-Scheduler');
	return;
}

//echo var_dump($_POST) . $nl . $nl;

$page = $ph->get('postSrc');
switch ($page)
{
	case "add_location":
		$loc = $ph->get('location');
		echo "Adding location: " . $loc . $nl;
		break;


	case "schedule_meeting":
		$loc = $ph->get('location');
		$date = $ph->get('date') . ' ';
		$start = $date . $ph->get('start');
		$finish = $date . $ph->get('finish');
		$type = $ph->get('meetingType');
		$numOfVolunteers = $ph->get('numOfVolunteers');
		$description = $ph->get('description');
		echo "Location: " . $loc . $nl;
		echo "Start: " . $start . $nl;
		echo "Finish: " . $finish . $nl;
		echo "Meeting type: " . $type . $nl;
		echo "Volunteer #: " . $numOfVolunteers . $nl;
		echo "Description: " . $description . $nl;
		break;


	case "confirm_volunteer":
		$vols = $ph->get('volunteers');
		echo "Volunteers: " . $nl;
		foreach ($vols as $v)
			echo $v . $nl;
		break;


	case "create_group":
		$class = $ph->get('class');
		$groupName = $ph->get('groupName');
		$members = $ph->get('members');
		echo "Class: " . $class . $nl;
		echo "Group name: " . $groupName . $nl;
		echo "Members:" . $nl;
		foreach ($members as $m)
			echo $m . $nl;
		break;


	case "login":
		$username = $ph->get('username');
		$password = $ph->get('password');

		$sh = new SessionHandler();
		$sh->set("username", $username);

		$smarty = new Smarty();
		$page = "main.tpl";
		if ($smarty->templateExists($page))
		{
			$dm = new DataManager();
			$upcomingEvents = $dm->getUpcomingTeamEvents($username);
			$userInfoArr = $dm->getPersonInfo($username);
			$userInfo = $userInfoArr[0];
			$smarty->assign('upcomingEvents', $upcomingEvents);
			$smarty->assign('eid', $userInfo['Eid']);
			$smarty->assign('firstName', $userInfo['FirstName']);
			$smarty->assign('lastName', $userInfo['LastName']);
			$sh->set("firstName", $firstName);
			$sh->set("lastName", $lastName);
			//echo var_dump($userInfo);
		}
		else
		{
			$page = "error_404.tpl";
		}
		$smarty->display($page);
		//$gh = new GetHandler();
		//$gh->set('page', 'main');
		//header('Location: index.php?page=main') ;
		break;


	case "settings":
		$notifyVia = $ph->get('notifyVia');
		$remind = $ph->get('remind');
		$reminderTime = $ph->get('reminderTime');
		echo "Notifying by:" . $nl;
		foreach ($notifyVia as $n)
			echo $n . $nl;
		echo "Sending notifications? ";
		if ($remind == "1")
			echo "Yes" . $nl;
		echo "Reminding " . $reminderTime . " hour(s) beforehand." . $nl;
		break;


	case "volunteer_confirm":
		echo "Signed up." . $nl;
		break;


	case "volunteer_signup":
		$name = $ph->get('name');
		$eid = $ph->get('eid');
		$class = $ph->get('class');
		echo "Name: " . $name . $nl;
		echo "E-id: " . $eid . $nl;
		echo "Class: " . $class . $nl;
		break;


	default:
		echo "Something else";
		break;
}

?>
