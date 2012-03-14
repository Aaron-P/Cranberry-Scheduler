<?php
error_reporting(E_ALL | E_STRICT);
require_once("classes/SessionHandler.class.php");
require_once("classes/GetHandler.class.php");
require_once("classes/DataManager.class.php");

$sh = new SessionHandler();
$gh = new GetHandler();
$dm = new DataManager();

$eid = $sh->get('username');
$start = $gh->get('start');
$end = $gh->get('end');

//echo $eid . " " . $start . " " . $end;

$events = $dm->getTeamEventsBetween($eid, $start, $end);
//echo " got em";
if (!$events) {
//	echo var_dump($events);
	return json_encode(array());
}

//echo "yeah" ;

//echo "<br /><br />";

//echo print_r(json_encode($events));

//echo "<br /><br />";

//$evs = array();

//for ($i = 0; $events[$i] != null; $i++)
//	$evs[] = $events[$i];

print_r(json_encode($events));

return json_encode($events);
?>