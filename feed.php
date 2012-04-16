<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

error_reporting(E_ALL | E_STRICT);
require_once("classes/UserSession.class.php");
require_once("classes/DataManager.class.php");
require_once("classes/PostHandler.class.php");

$userSession = new UserSession();
$postHandler = new PostHandler();
$dataManager = new DataManager();

$eid = $userSession->getUsername();
$location = $postHandler->get("location");
$start = $postHandler->get("start");
$end = $postHandler->get("end");

if (is_null($location))
	$events = $dataManager->getTeamEventsBetween($eid, $start, $end);
else
	$events = $dataManager->getLocationEventsBetween($location, $start, $end);


// FIND A BETTER WAY OF DOING THIS
foreach ($events as $id => $stuff)
{
	$events[$id]["allDay"] = false;
}

//echo "<pre>";
//print_r($events);
//echo "</pre>";
//die();

echo json_encode($events);

//[{"id":111,"allDay":false,"title":"Rehearsal","start":"1334505600","end":"1334509200"},{"id":112,"allDay":false,"title":"Interview","start":"1334512800","end":"1334520000"}]

?>