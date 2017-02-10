<?php
/**************************************************************************
 * Эта программа является частью SourceBans MATERIAL Admin.
 *
 * Все права защищены © 2016-2017 Sergey Gut <webmaster@kruzefag.ru>
 *
 * SourceBans MATERIAL Admin распространяется под лицензией
 * Creative Commons Attribution-NonCommercial-ShareAlike 3.0.
 *
 * Вы должны были получить копию лицензии вместе с этой работой. Если нет,
 * см. <http://creativecommons.org/licenses/by-nc-sa/3.0/>.
 *
 * ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО
 * ГАРАНТИЙ, ЯВНЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ,
 * ГАРАНТИИ ПРИГОДНОСТИ ДЛЯ КОНКРЕТНЫХ ЦЕЛЕЙ И НЕНАРУШЕНИЯ. НИ ПРИ КАКИХ
 * ОБСТОЯТЕЛЬСТВАХ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ЗА
 * ЛЮБЫЕ ПРЕТЕНЗИИ, ИЛИ УБЫТКИ, НЕЗАВИСИМО ОТ ДЕЙСТВИЯ ДОГОВОРА,
 * ГРАЖДАНСКОГО ПРАВОНАРУШЕНИЯ ИЛИ ИНАЧЕ, ВОЗНИКАЮЩИЕ ИЗ, ИЛИ В СВЯЗИ С
 * ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ИЛИ ИНЫМИ ДЕЙСТВИЯМИ
 * ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ.
 *
 * Эта программа базируется на работе, охватываемой следующим авторским
 *                                                           правом (ами):
 *
 *  * SourceBans ++
 *    Copyright © 2014-2016 Sarabveer Singh
 *    Выпущено под лицензией CC BY-NC-SA 3.0
 *    Страница: <https://sbpp.github.io/>
 *
 ***************************************************************************/

if (!defined('IN_SB')) {echo("Вы не должны быть здесь. Используйте только ссылки внутри системы!");die();}
global $theme;

$first = true; 
$i=0;
$tabs = array();
foreach($var AS $v)
{ 
	if(empty($v['title']))
	{
		$i++; continue;
	} 
	if($first) 
		$GLOBALS['enable'] = $v['id']; 
	if(isset($v['external']) && $v['external'] == true) 
	{
		$lnk = $v['url']; 
		$click = "";
	} 
	else 
	{
		$lnk = "#^" . $v['id']; 
		$click = "SwapPane(". $v['id'] .");";
	} 
	if($i == 0) 
		$class = "active"; 
	else 
		$class = "";
	$itm = array();
	$itm['tab'] = "<li id='tab-". $v['id'] . "' class='" . $class . "'><a href='$lnk' id='admin_tab_".$v['id']."' onclick=\"$click\"> " . $v['title'] . "</a></li>";
	array_push($tabs, $itm) ;
	$i++;
	$first=false;
}

//if($_GET['p'] == "account")
	//$theme->assign('pane_image','<img src="themes/' . SB_THEME . '/images/admin/your_account.png"> </div>') ;
//else 
	//$theme->assign('pane_image', '<img src="themes/' . SB_THEME . '/images/admin/'.  $_GET['c'] . '.png"> </div>');
	
$theme->assign('tabs', $tabs);

$theme->display('item_admin_tabs.tpl');
?>
