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

//$smarty->force_compile = true;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 120;

$userSession = new UserSession();

if ($getHandler->exists("logout"))
{
	$userSession->destroy();
}


$scriptUrls = new ScriptUrls();

if (!$userSession->check() && $getHandler->get("page") !== "login")
{
	$scriptUrls->redirectTo("login", $getHandler->get("page"));
}

$sh = new SessionHandler();
$dm = new DataManager();

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
		$upcomingEvents = $dm->getUpcomingTeamEvents($username);
		$smarty->assign("upcomingEvents", $upcomingEvents);
		break;

	case "view_meetings":
		$upcomingEvents = $dm->getUpcomingTeamEventsDetailed($username);
		$smarty->assign("upcomingEvents", $upcomingEvents);
		break;

	case "meeting_overview":
		if (is_null($eventId = $getHandler->get("eventID")))
			$scriptUrls->redirectTo("main");
		$event = $dm->getEventInfo($eventId);
		$volunteers = $dm->getMeetingVolunteers($eventId);
		$smarty->assign("eventId", $eventId);
		$smarty->assign("event", $event);
		$smarty->assign("volunteers", $volunteers);
		break;

	case "volunteer_opportunities":
		$opportunities = $dm->getVolunteerOpportunities();
		$smarty->assign("opportunities", $opportunities);
		break;

	case "schedule_meeting":
		$locations = $dm->getAllLocations();
		$smarty->assign("locations", $locations);
		break;

	case "settings":
		$settings = $dm->getSettings($username);
		$smarty->assign("settings", $settings);
		break;

	case "login":
		if (is_null($return = $getHandler->get("return")))
			$return = "";
		$smarty->assign("return", $return);
		break;

	default:
		$scriptUrls->redirectTo("main");
}

// if (is_null($pageGet)) $pageGet = "main";
$page = $pageGet . ".tpl";

if (!$smarty->templateExists($page))
	$page = "error_404.tpl";
$smarty->display($page);
?>