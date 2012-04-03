<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

error_reporting(E_ALL | E_STRICT);
require_once("classes/smarty/Smarty.class.php");
require_once("classes/GetHandler.class.php");

$smarty = new Smarty();
$gh = new GetHandler();

//$smarty->force_compile = true;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 120;

$page = "login.tpl";
if ($gh->exists("page"))
{
    $pageGet = $gh->get("page");
	if ($pageGet === "")
	{
		header("Location: http://localhost/Cranberry-Scheduler/index.php");
		die();
	}
	else
		$page = $pageGet . ".tpl";
}

if (!$smarty->templateExists($page))
	$page = "error_404.tpl";
$smarty->display($page);
?>