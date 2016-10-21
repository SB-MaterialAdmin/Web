<?php 
if(!defined("IN_SB"))
{
	echo "You should not be here. Only follow links!";
	die();
}
$GLOBALS['TitleRewrite'] = "Подробный поиск банов";
require(TEMPLATES_PATH . "/admin.bans.search.php"); //Set theme vars from servers page
?>