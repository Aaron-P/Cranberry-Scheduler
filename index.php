<?php
/**
 * @copyright University of Illinois/NCSA Open Source License
 */

error_reporting(E_ALL | E_STRICT);
require_once("classes/smarty/Smarty.class.php");

$smarty = new Smarty();

//$smarty->force_compile = true;
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 120;

$page = "login.tpl";
if (isset($_GET["page"]))
{
	if ($_GET["page"] === "")
	{
		header("Location: http://localhost/Cranberry-Scheduler/index.php");
		die();
	}
	else
		$page = $_GET["page"] . ".tpl";
}

if (!$smarty->templateExists($page))
	$page = "error_404.tpl";
$smarty->display($page);
?>