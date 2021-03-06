<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

error_reporting(E_ALL | E_STRICT);
require_once("classes/UserSession.class.php");
//require_once("classes/smarty/Smarty.class.php");
require_once("classes/PostHandler.class.php");
//require_once("classes/GetHandler.class.php");
require_once("classes/SessionHandler.class.php");
require_once("classes/DataManager.class.php");
require_once("classes/DataValidator.class.php");
require_once("classes/ScriptUrls.class.php");

$scriptUrls = new ScriptUrls();
$dataManager = new DataManager();
$userSession = new UserSession();
$postHandler = new PostHandler();
$dataValidator = new DataValidator();
$sessionHandler = new SessionHandler();

$nl = "<br />";

if ($postHandler->exists("cancel"))
	$scriptUrls->redirectTo("index.php", array("page" => "main"));

//echo var_dump($_POST) . $nl . $nl;

$source = $postHandler->get("source");

if (!$userSession->check() && $source !== "login")
{
//	print "Nope";
//	if ($getHandler->exists("page"))
//		;// add a get variable to login page so we can redirect to the correct page on login
	$scriptUrls->redirectTo("index.php", array("page" => "login"));
}

if ($postHandler->get("token") !== $userSession->getPostToken())
{
	// should probably make some sort of error page so they know something went wrong
	if (is_null($location = $postHandler->get("source")))
		$location = "main";
	$scriptUrls->redirectTo("index.php", array("page" => $location));
}

/**
 * Checks the source page of the POST and handles the data accordingly.
 */
switch ($source)
{
	case "add_location":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$location = $postHandler->get("location");
		$dataManager->addLocation($location);
		$scriptUrls->redirectTo("index.php", array("page" => $return));
		break;

	case "delete_location":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$locations = $postHandler->get("locations");
		$delete = $postHandler->get("delete") === "yes";
		$dataManager->updateDisabledLocations($locations, $delete);
		$scriptUrls->redirectTo("index.php", array("page" => $return));
		break;

	case "add_course":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$course = $postHandler->get("course");
		$dataManager->addCourse($course);
		$scriptUrls->redirectTo("index.php", array("page" => $return));
		break;

	case "delete_course":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$courses = $postHandler->get("courses");
		foreach ($courses as $courseID)
			$dataManager->deleteCourse($courseID);
		$scriptUrls->redirectTo("index.php", array("page" => $return));
		break;

	case "add_student":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));

		$courseID = $postHandler->get("courseID");
		$people = $postHandler->get("people");
		$isResearcher = $postHandler->get("researcher") === "on";
		$isTeacher = $postHandler->get("teacher") === "on";
		foreach ($people as $personID)
		{
			$dataManager->addPerson($personID, $courseID, $isResearcher, $isTeacher);
		}
		$scriptUrls->redirectTo("index.php", array("page" => $return));
		break;

	case "delete_student":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		
		$courseID = $postHandler->get("courseID");
		$people = $postHandler->get("people");
		$isResearcher = $postHandler->get("researcher") === "on";
		$isTeacher = $postHandler->get("teacher") === "on";
		foreach ($people as $personID)
		{
			$dataManager->deletePerson($personID, $courseID, $isResearcher, $isTeacher);
		}
		$scriptUrls->redirectTo("index.php", array("page" => $return));
		break;

	case "add_group":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$people = $postHandler->get("people");
		$groupName = $postHandler->get("name");
		$dataManager->addGroup($groupName);
		$groupID = $dataManager->getGroupIDByName($groupName);
		foreach ($people as $personID)
			$dataManager->addGroupPerson($groupID, $personID);
		$scriptUrls->redirectTo("index.php", array("page" => $return));
		break;

	case "delete_group":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$groups = $postHandler->get("groups");
		foreach ($groups as $teamID)
			$dataManager->deleteGroup($teamID);
		$scriptUrls->redirectTo("index.php", array("page" => $return));
		break;

	case "schedule_meeting":
		if (!$userSession->isResearcher() && !$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		if (!is_null($eventId = $postHandler->get("eventId")))
		{
			if (!$dataManager->isInMeeting($eventId, $userSession->getUsername()))
				$scriptUrls->redirectTo("index.php", array("page" => "main"));
			// update existing meeting with id
		}
		$loc = $postHandler->get("location");
		$date = $postHandler->get("date") . " ";
		$start = $date . $postHandler->get("start");
		$finish = $date . $postHandler->get("finish");
		$type = $postHandler->get("meetingType");
		$numOfVolunteers = $postHandler->get("numOfVolunteers");
		$description = $postHandler->get("description");
		$startTimestamp = $dataValidator->validDateTime($start);
		$finishTimestamp = $dataValidator->validDateTime($finish);

//		updateMeeting

		if ($startTimestamp != false && $finishTimestamp != false)
		{
			if (!is_null($eventId))
				$dataManager->updateMeeting($eventId, $type, $description, $startTimestamp, $finishTimestamp, $loc, $numOfVolunteers);
			else
				$dataManager->insertMeeting($type, $description, $startTimestamp, $finishTimestamp, $loc, $numOfVolunteers, $userSession->getUsername());
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		}
		else
			echo "Bad date/time: " . $start . " --- " . $finish;	// Throw a real exception sometime.
		break;

	case "confirm_volunteer":
		if (!$userSession->isResearcher() && !$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$vols = $postHandler->get("volunteers");
		echo "Volunteers: " . $nl;
		foreach ($vols as $v)
			echo $v . $nl;
		break;

	case "create_group":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
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
		if ($userSession->check())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));

		$username = $postHandler->get("username");
		$password = $postHandler->get("password");

		$userSession->auth($username, $password);

		if (is_null($return = $postHandler->get("return")))
			$return = "main";
		$scriptUrls->redirectTo("index.php", array("page" => $return));
		break;

	case "settings":
		$eid = $userSession->getUsername();
		$remind = $postHandler->get("remind");
		$reminderTime = $postHandler->get("reminderTime");
		$email = $postHandler->get("email");
		if ($email === "")
			$email = NULL;
		$enotify = isset($remind) ? 1 : 0;
		$dataManager->updateSettings($eid, $email, $enotify, $reminderTime);
		$scriptUrls->redirectTo("index.php", array("page" => "settings"));
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

	case "sign_up":
		$meetingID = $postHandler->get("eventId");
		$eid = $userSession->getUsername();
		$dataManager->volunteerSignUp($eid, $meetingID);
		$scriptUrls->redirectTo("index.php", array("page" => "main"));
		break;

	default:
		echo "Something else";
		break;
}
?>