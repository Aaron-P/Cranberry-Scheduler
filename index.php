<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

error_reporting(E_ALL | E_STRICT);
require_once("classes/smarty/Smarty.class.php");
require_once("classes/GetHandler.class.php");
require_once("classes/SessionHandler.class.php");
require_once("classes/DataManager.class.php");

$smarty = new Smarty();
$gh = new GetHandler();

//$smarty->force_compile = true;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 120;

$page = "login.tpl";
if ($gh->exists("page"))
{
    $sh = new SessionHandler();
    $dm = new DataManager();

    $username = $sh->get("username");
    $smarty->assign('username', $username);
    $smarty->assign('firstName', $sh->get("firstName"));
    $smarty->assign('lastName', $sh->get("lastName"));
    $pageGet = $gh->get("page");

    switch ($pageGet)
    {
        case "main":
            $upcomingEvents = $dm->getUpcomingTeamEvents($username);
            $smarty->assign('upcomingEvents', $upcomingEvents);
            break;

        case "view_meetings":
            $upcomingEvents = $dm->getUpcomingTeamEventsDetailed($username);
            $smarty->assign('upcomingEvents', $upcomingEvents);
            break;

        case "meeting_overview":
            $eventID = $gh->get("eventID");
            $event = $dm->getEventInfo($eventID);
            $volunteers = $dm->getMeetingVolunteers($eventID);
            $smarty->assign('event', $event);
            $smarty->assign('volunteers', $volunteers);
            break;

        case "volunteer_opportunities":
            $opportunities = $dm->getVolunteerOpportunities();
            $smarty->assign('opportunities', $opportunities);
            // INCOMPLETE
            break;

        case "schedule_meeting":
            // TODO
            break;

        case "settings":
            // TODO
            break;

        default:
            header("Location: http://localhost/Cranberry-Scheduler/index.php");
            die();
    }

    $page = $pageGet . ".tpl";
}

if (!$smarty->templateExists($page))
	$page = "error_404.tpl";
$smarty->display($page);
?>