<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

error_reporting(E_ALL | E_STRICT);
require_once("classes/UserSession.class.php");
require_once("classes/smarty/Smarty.class.php");
require_once("classes/GetHandler.class.php");
require_once("classes/SessionHandler.class.php");
require_once("classes/DataManager.class.php");
require_once("classes/ScriptUrls.class.php");

$smarty = new Smarty();
$getHandler = new GetHandler();
$dataManager = new DataManager();
$userSession = new UserSession();
$sessionHandler = new SessionHandler();

//$smarty->force_compile = true;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 120;


if ($getHandler->exists("logout"))
{
	$userSession->destroy();
}


$scriptUrls = new ScriptUrls();

if (!$userSession->check() && $getHandler->get("page") !== "login")
{
	$scriptUrls->redirectTo("index.php", array("page" => "login", "return" => $getHandler->get("page")));
}


$username = $userSession->getUsername();
$smarty->assign("username", $username);
$smarty->assign("firstName", $userSession->getFirstName());
$smarty->assign("lastName", $userSession->getLastName());
$smarty->assign("isVolunteer", $userSession->isVolunteer());
$smarty->assign("isResearcher", $userSession->isResearcher());
$smarty->assign("isTeacher", $userSession->isTeacher());

$pageGet = $getHandler->get("page");

if ($userSession->check())
	$smarty->assign("loggedIn", true);
else
	$smarty->assign("loggedIn", false);

$smarty->assign("token", $userSession->getPostToken());
$smarty->assign("baseUrl", $scriptUrls->getBaseUrl());
$smarty->assign("showConfirmDialog", false);

if (false && $dataManager->areUnconfirmedVolunteers('esutten'))
	$smarty->assign("confirmVolunteers", true);
else
	$smarty->assign("confirmVolunteers", false);

$smarty->loadFilter("variable", "htmlspecialchars");

switch ($pageGet)
{
	case "main":
		if ($userSession->isVolunteer() && !$userSession->isResearcher() && !$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "volunteer_opportunities"));

		$smarty->assign("showConfirmDialog", true);
		$upcomingEvents = $dataManager->getUpcomingTeamEvents($username);

		$volEvents = $dataManager->getVolEvents($username);
		$upcomingEvents = array_merge($upcomingEvents, $volEvents);

		$smarty->assign("upcomingEvents", $upcomingEvents);
		break;

	case "view_meetings":
		$smarty->assign("showConfirmDialog", true);
		$upcomingEvents = $dataManager->getUpcomingTeamEventsDetailed($username);

		$volEvents = $dataManager->getVolEventsDetailed($username);
		$upcomingEvents = array_merge($upcomingEvents, $volEvents);

		$smarty->assign("upcomingEvents", $upcomingEvents);
		break;

	case "meeting_overview":
		$smarty->assign("showConfirmDialog", true);
		if (is_null($eventId = $getHandler->get("eventID")))
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$event = $dataManager->getEventInfo($eventId);
		$volunteers = $dataManager->getMeetingVolunteers($eventId);
		$smarty->assign("eventId", $eventId);
		$smarty->assign("event", $event);
		$smarty->assign("volunteers", $volunteers);
		$smarty->assign("signUp", false);
		if ((bool)$event["InPast"])
		{
			$smarty->assign("editable", false);
		}
		else if (!$dataManager->ownsMeeting($eventId, $userSession->getUsername()))
		{
			$smarty->assign("editable", false);
			if (!$dataManager->isVolunteer($eventId, $userSession->getUsername()))
				$smarty->assign("signUp", true);
		}
		else
			$smarty->assign("editable", true);
		break;

	case "volunteer_opportunities":
		$upcomingEvents = $dataManager->getUpcomingTeamEvents($username);

		$volEvents = $dataManager->getVolEvents($username);
		$upcomingEvents = array_merge($upcomingEvents, $volEvents);

		$smarty->assign("upcomingEvents", $upcomingEvents);
		$opportunities = $dataManager->getVolunteerOpportunities();
		$smarty->assign("opportunities", $opportunities);
		break;

	case "schedule_meeting":
		if (!$userSession->isResearcher() && !$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));

		$smarty->assign("showConfirmDialog", true);
		if (!is_null($eventId = $getHandler->get("eventID")))
		{
			if (!$dataManager->isInMeeting($eventId, $userSession->getUsername()))
				$scriptUrls->redirectTo("index.php", array("page" => "main"));
			// populate the form

			$meetingData = $dataManager->getMeetingData($eventId);
			$smarty->assign("inputFields", $meetingData);
		}
		else
			$smarty->assign("inputFields", false);
		$locations = $dataManager->getAllLocations();
		$smarty->assign("locations", $locations);
		break;

	case "settings":
		$smarty->assign("showConfirmDialog", true);
		$settings = $dataManager->getSettings($username);
		$smarty->assign("settings", $settings);
		break;

	case "login":
		if ($userSession->check())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));

		if (is_null($return = $getHandler->get("return")))
			$return = "";
		$smarty->assign("return", $return);
		break;

	case "confirm_volunteer":
		if (!$userSession->isResearcher() && !$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));

		$unconfirmedVolunteers = $dataManager->getUnconfirmedVolunteers('esutten');//$userSession->getUsername());
		$meetings = array();
		foreach ($unconfirmedVolunteers AS $unconfirmedVolunteer)
		{
			if (!isset($meetings[$unconfirmedVolunteer["MeetingID"]]))
				$meetings[$unconfirmedVolunteer["MeetingID"]] = array(
					"MeetingID" => $unconfirmedVolunteer["MeetingID"],
					"MeetingType" => $unconfirmedVolunteer["MeetingType"],
					"Description" => $unconfirmedVolunteer["Description"],
					"StartTime" => $unconfirmedVolunteer["StartTime"],
					"EndTime" => $unconfirmedVolunteer["EndTime"],
					"LocationID" => $unconfirmedVolunteer["LocationID"],
					"Volunteers" => array());

			array_push($meetings[$unconfirmedVolunteer["MeetingID"]]["Volunteers"], array(
				"PersonID" => $unconfirmedVolunteer["PersonID"],
				"FirstName" => $unconfirmedVolunteer["FirstName"],
				"LastName" => $unconfirmedVolunteer["LastName"]
			));
		}
		$smarty->assign("meetings", $meetings);
		break;

	case "add_location":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		// check user level
		break;

	case "delete_location":
		// check user level
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$locations = $dataManager->getAllLocations();
		$smarty->assign("locations", $locations);
		break;

	case "add_course":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		// check user level
		break;

	case "delete_course":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		// check user level
		$courses = $dataManager->getAllCourses();
		$smarty->assign("courses", $courses);
		break;

	case "add_student":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$courses = $dataManager->getAllCourses();
		$people = $dataManager->getAllPeople();
		$smarty->assign("courses", $courses);
		$smarty->assign("people", $people);
		break;

	case "delete_student":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$courses = $dataManager->getAllCourses();
		$people = $dataManager->getAllPeople();
		$smarty->assign("courses", $courses);
		$smarty->assign("people", $people);
		break;

	case "add_group":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		// check user level
		$people = $dataManager->getAllPeople();
		$smarty->assign("people", $people);
		break;

	case "delete_group":
		if (!$userSession->isTeacher())
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		// check user level
		$groups = $dataManager->getAllGroups();
		$smarty->assign("groups", $groups);
		break;

	default:
		$scriptUrls->redirectTo("index.php", array("page" => "main"));
}

// if (is_null($pageGet)) $pageGet = "main";
$page = $pageGet . ".tpl";

if (!$smarty->templateExists($page))
	$page = "error_404.tpl";
$smarty->display($page);
?>