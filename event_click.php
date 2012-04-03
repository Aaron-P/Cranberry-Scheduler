<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

error_reporting(E_ALL | E_STRICT);
//require_once("classes/SessionHandler.class.php");
require_once("classes/GetHandler.class.php");
require_once("classes/DataManager.class.php");
require_once("classes/smarty/Smarty.class.php");

//$sh = new SessionHandler();
$gh = new GetHandler();
$dm = new DataManager();

$eventID = $gh->get("eventID");
$eventArr = $dm->getEventInfo($eventID);
$event = $eventArr[0];
$volunteers = $dm->getMeetingVolunteers($eventID);

//if ($event != null && $volunteers != null)
//{
	$smarty = new Smarty();
	$page = "meeting_overview.tpl";
	if ($smarty->templateExists($page))
	{
		$smarty->assign('event', $event);
		$smarty->assign('volunteers', $volunteers);
	}
	else
	{
		$page = "error_404.tpl";
	}

	$smarty->display($page);
//}

//echo var_dump($event) . "\n\n" . var_dump($volunteers);
?>