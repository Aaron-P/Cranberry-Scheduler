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
$pageGet = $getHandler->get("page");

if ($userSession->check())
	$smarty->assign("loggedIn", true);
else
	$smarty->assign("loggedIn", false);

$smarty->assign("token", $userSession->getPostToken());
$smarty->assign("baseUrl", $scriptUrls->getBaseUrl());
$smarty->assign("showConfirmDialog", false);

if ($dataManager->areUnconfirmedVolunteers('esutten'))
	$smarty->assign("confirmVolunteers", true);
else
	$smarty->assign("confirmVolunteers", false);

switch ($pageGet)
{
	case "main":
		$smarty->assign("showConfirmDialog", true);
		$upcomingEvents = $dataManager->getUpcomingTeamEvents($username);
		$smarty->assign("upcomingEvents", $upcomingEvents);
		break;

	case "view_meetings":
		$smarty->assign("showConfirmDialog", true);
		$upcomingEvents = $dataManager->getUpcomingTeamEventsDetailed($username);
		$smarty->assign("upcomingEvents", $upcomingEvents);
		break;

	case "meeting_overview":
		$smarty->assign("showConfirmDialog", true);
		if (is_null($eventId = $getHandler->get("eventID")) || !$dataManager->isInMeeting($eventId, $userSession->getUsername()))
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$event = $dataManager->getEventInfo($eventId);
		$volunteers = $dataManager->getMeetingVolunteers($eventId);
		$smarty->assign("eventId", $eventId);
		$smarty->assign("event", $event);
		$smarty->assign("volunteers", $volunteers);
		if ((bool)$event["InPast"])
			$smarty->assign("editable", false);
		else
			$smarty->assign("editable", true);

		break;

	case "volunteer_opportunities":
		$opportunities = $dataManager->getVolunteerOpportunities();
		$smarty->assign("opportunities", $opportunities);
		break;

	case "schedule_meeting":
		$smarty->assign("showConfirmDialog", true);
		if (!is_null($eventId = $getHandler->get("eventID")))
		{
			if (!$dataManager->isInMeeting($eventId, $userSession->getUsername()))
				$scriptUrls->redirectTo("index.php", array("page" => "main"));
			// populate the form

		}
		$locations = $dataManager->getAllLocations();
		$smarty->assign("locations", $locations);
		break;

	case "settings":
		$smarty->assign("showConfirmDialog", true);
		$settings = $dataManager->getSettings($username);
		$smarty->assign("settings", $settings);
		break;

	case "login":
		if (is_null($return = $getHandler->get("return")))
			$return = "";
		$smarty->assign("return", $return);
		break;

	case "confirm_volunteer":
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

	default:
		$scriptUrls->redirectTo("index.php", array("page" => "main"));
}

// if (is_null($pageGet)) $pageGet = "main";
$page = $pageGet . ".tpl";

if (!$smarty->templateExists($page))
	$page = "error_404.tpl";
$smarty->display($page);
?>