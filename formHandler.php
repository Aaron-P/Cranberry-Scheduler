<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

error_reporting(E_ALL | E_STRICT);
require_once("classes/UserSession.class.php");
require_once("classes/smarty/Smarty.class.php");
require_once("classes/PostHandler.class.php");
//require_once("classes/GetHandler.class.php");
require_once("classes/SessionHandler.class.php");
require_once("classes/DataManager.class.php");
require_once("classes/DataValidator.class.php");

$ph = new PostHandler();
$nl = "<br />";

if ($ph->exists('cancel'))
{
	header('Location: http://localhost/Cranberry-Scheduler');
	die();
}

//echo var_dump($_POST) . $nl . $nl;

// $userSession = new UserSession();
// if (!$userSession->check())
// {
// 	print "Nope";
// 	if ($gh->exists("page"))
// 		;// add a get variable to login page so we can redirect to the correct page on login
// 	header('Location: http://localhost/Cranberry-Scheduler/index.php?page=login');
// 	die();
// }

$page = $ph->get('postSrc');
switch ($page)
{
	case "add_location":
		$loc = $ph->get('location');
		echo "Adding location: " . $loc . $nl;
		break;


	case "schedule_meeting":
		$dm = new DataManager();
		$dv = new DataValidator();
		$loc = $ph->get('location');
		$date = $ph->get('date') . ' ';
		$start = $date . $ph->get('start');
		$finish = $date . $ph->get('finish');
		$type = $ph->get('meetingType');
		$numOfVolunteers = $ph->get('numOfVolunteers');
		$description = $ph->get('description');
		$startTimestamp = $dv->validDateTime($start);
		$finishTimestamp = $dv->validDateTime($finish);
		// echo "Location: " . $loc . $nl;
		// echo "Start: " . $start . $nl;
		// echo "Finish: " . $finish . $nl;
		// echo "Meeting type: " . $type . $nl;
		// echo "Volunteer #: " . $numOfVolunteers . $nl;
		// echo "Description: " . $description . $nl;
		// echo $startTimestamp . $nl;
		// echo $finishTimestamp . $nl;
		if ($startTimestamp != false && $finishTimestamp != false)
			$dm->insertMeeting($type, $description, $startTimestamp, $finishTimestamp, $loc, $numOfVolunteers);
		else
			echo "Bad date/time: " . $start . " --- " . $finish;	// Throw a real exception sometime.
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

		// should use UserSession class
		$sh = new SessionHandler();
		$dm = new DataManager();
		$sh->set("username", $username);
		$userInfo = $dm->getPersonInfo($username);
		$sh->set("firstName", $userInfo['FirstName']);
		$sh->set("lastName", $userInfo['LastName']);

		header('Location: index.php?page=main');
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