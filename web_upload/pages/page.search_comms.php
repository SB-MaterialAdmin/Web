<?php 
if(!defined("IN_SB"))
{
	echo "You should not be here. Only follow links!";
	die();
}
$GLOBALS['TitleRewrite'] = "Подробный поиск мутов";
require(TEMPLATES_PATH . "/admin.comms.search.php"); //Set theme vars from servers page
?>