<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

error_reporting(E_ALL | E_STRICT);
require_once("classes/SessionHandler.class.php");
require_once("classes/DataManager.class.php");
require_once("classes/PostHandler.class.php");

$sh = new SessionHandler();
$ph = new PostHandler();
$dm = new DataManager();

$eid = $sh->get('username');
$start = $ph->get('start');
$end = $ph->get('end');
//$page = $ph->get('page');     TODO

$events = $dm->getTeamEventsBetween($eid, $start, $end);
if (!$events)
	return json_encode(array());

print_r(json_encode($events));

return json_encode($events);
?>