<?php
error_reporting(E_ALL | E_STRICT);
require_once("classes/smarty/Smarty.class.php");
require_once("classes/DataManager.class.php");

$dm = new DataManager();
$opportunities = $dm->getVolunteerOpportunities();

$smarty = new Smarty();
$smarty->assign('opportunities', $opportunities);
$smarty->display('volunteer_opportunities.tpl');

// incomplete

?>
