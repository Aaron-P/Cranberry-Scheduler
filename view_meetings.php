<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

require_once("classes/SessionHandler.class.php");
//require_once("classes/PostHandler.class.php");
require_once("classes/DataManager.class.php");
require_once("classes/smarty/Smarty.class.php");

$sh = new SessionHandler();
$dm = new DataManager();
$smarty = new Smarty();

$eid = $sh->get('username');
$upcomingEvents = $dm->getUpcomingTeamEventsDetailed($eid);
$smarty->assign('upcomingEvents', $upcomingEvents);
$smarty->display("view_meetings.tpl");

?>