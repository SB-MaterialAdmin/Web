<?php 
if(!defined("IN_SB"))
{
	echo "Ошибка доступа!";
	die();
}
$GLOBALS['TitleRewrite'] = "Подробный поиск мутов";
require(TEMPLATES_PATH . "/admin.comms.search.php"); //Set theme vars from servers page
?>