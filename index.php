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

switch ($pageGet)
{
	case "main":
		$upcomingEvents = $dataManager->getUpcomingTeamEvents($username);
		$smarty->assign("upcomingEvents", $upcomingEvents);
		break;

	case "view_meetings":
		$upcomingEvents = $dataManager->getUpcomingTeamEventsDetailed($username);
		$smarty->assign("upcomingEvents", $upcomingEvents);
		break;

	case "meeting_overview":
		if (is_null($eventId = $getHandler->get("eventID")))
			$scriptUrls->redirectTo("index.php", array("page" => "main"));
		$event = $dataManager->getEventInfo($eventId);
		$volunteers = $dataManager->getMeetingVolunteers($eventId);
		$smarty->assign("eventId", $eventId);
		$smarty->assign("event", $event);
		$smarty->assign("volunteers", $volunteers);
		break;

	case "volunteer_opportunities":
		$opportunities = $dataManager->getVolunteerOpportunities();
		$smarty->assign("opportunities", $opportunities);
		break;

	case "schedule_meeting":
		$locations = $dataManager->getAllLocations();
		$smarty->assign("locations", $locations);
		break;

	case "settings":
		$settings = $dataManager->getSettings($username);
		$smarty->assign("settings", $settings);
		break;

	case "login":
		if (is_null($return = $getHandler->get("return")))
			$return = "";
		$smarty->assign("return", $return);
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