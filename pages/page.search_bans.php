<?php 
if(!defined("IN_SB"))
{
	echo "Ошибка доступа!";
	die();
}
$GLOBALS['TitleRewrite'] = "Подробный поиск банов";
require(TEMPLATES_PATH . "/admin.bans.search.php"); //Set theme vars from servers page
?>