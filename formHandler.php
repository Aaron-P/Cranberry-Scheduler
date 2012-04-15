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

$postHandler = new PostHandler();
$nl = "<br />";

if ($postHandler->exists("cancel"))
{
	header("Location: http://localhost/Cranberry-Scheduler");
	die();
}

//echo var_dump($_POST) . $nl . $nl;

$source = $postHandler->get("source");

$userSession = new UserSession();
if (!$userSession->check() && $source !== "login")
{
//	print "Nope";
//	if ($getHandler->exists("page"))
//		;// add a get variable to login page so we can redirect to the correct page on login
	header("Location: http://localhost/Cranberry-Scheduler/index.php?page=login");
	die();
}

switch ($source)
{
	case "add_location":
		$loc = $postHandler->get("location");
		echo "Adding location: " . $loc . $nl;
		break;

	case "schedule_meeting":
		$dm = new DataManager();
		$dv = new DataValidator();
		$loc = $postHandler->get("location");
		$date = $postHandler->get("date") . " ";
		$start = $date . $postHandler->get("start");
		$finish = $date . $postHandler->get("finish");
		$type = $postHandler->get("meetingType");
		$numOfVolunteers = $postHandler->get("numOfVolunteers");
		$description = $postHandler->get("description");
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
		{
			$dm->insertMeeting($type, $description, $startTimestamp, $finishTimestamp, $loc, $numOfVolunteers);
			header("Location: /Cranberry-Scheduler");
		}
		else
			echo "Bad date/time: " . $start . " --- " . $finish;	// Throw a real exception sometime.
		break;

	case "confirm_volunteer":
		$vols = $postHandler->get("volunteers");
		echo "Volunteers: " . $nl;
		foreach ($vols as $v)
			echo $v . $nl;
		break;

	case "create_group":
		$class = $postHandler->get("class");
		$groupName = $postHandler->get("groupName");
		$members = $postHandler->get("members");
		echo "Class: " . $class . $nl;
		echo "Group name: " . $groupName . $nl;
		echo "Members:" . $nl;
		foreach ($members as $m)
			echo $m . $nl;
		break;

	case "login":
		$username = $postHandler->get("username");
		$password = $postHandler->get("password");

		// should use UserSession class
		$sh = new SessionHandler();
		$dm = new DataManager();
		$userSession = new UserSession();
		$userSession->auth($username, $password);

		$sh->set("username", $username);
		$userInfo = $dm->getPersonInfo($username);
		$sh->set("firstName", $userInfo["FirstName"]);
		$sh->set("lastName", $userInfo["LastName"]);

		if (is_null($return = $postHandler->get("return")))
			$return = "main";
		header("Location: http://localhost/Cranberry-Scheduler/index.php?page=".$return);
		break;

	case "settings":
		$userSession = new UserSession();
		$dm = new DataManager();
		$eid = $userSession->getUsername();
		$remind = $postHandler->get("remind");
		$reminderTime = $postHandler->get("reminderTime");
		$email = $postHandler->get("email");
		if ($email === "")
			$email = NULL;
		$enotify = isset($remind) ? 1 : 0;
		$dm->updateSettings($eid, $email, $enotify, $reminderTime);
		header("Location: http://localhost/Cranberry-Scheduler/index.php?page=settings");
		break;

	case "volunteer_confirm":
		echo "Signed up." . $nl;
		break;

	case "volunteer_signup":
		$name = $postHandler->get("name");
		$eid = $postHandler->get("eid");
		$class = $postHandler->get("class");
		echo "Name: " . $name . $nl;
		echo "E-id: " . $eid . $nl;
		echo "Class: " . $class . $nl;
		break;

	default:
		echo "Something else";
		break;
}
?>