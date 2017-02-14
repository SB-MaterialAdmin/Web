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

if(defined('IN_HOME'))
	$number = -1;
else
{
    $GLOBALS['server_qry'] = "";
	if(isset($_GET['s']))				
		$number = (int)$_GET['s'];
	else 
		$number = -1;
}

$query = $GLOBALS['db']->query("SELECT se.sid, se.ip, se.port, se.modid, se.rcon, md.icon FROM ".DB_PREFIX."_servers se LEFT JOIN ".DB_PREFIX."_mods md ON md.mid=se.modid WHERE se.sid > 0 AND se.enabled = 1 ORDER BY se.modid, se.sid");
$servers = array();
$i=0;
while ($res = $query->fetch(PDO::FETCH_LAZY))
{
	if(isset($_SESSION['getInfo.' . $res[1] . '.' . $res[2]]))
	{
		$_SESSION['getInfo.' . $res[1] . '.' . $res[2]] = "";
	}
	$info = array();
	$info['sid'] = $res[0];
	$info['ip'] = $res[1];
	$info['port'] = $res[2];
	$info['icon'] = $res[5];
	$info['index'] = $i;
	if(defined('IN_HOME'))
		$info['evOnClick'] = "window.location = 'index.php?p=servers&s=".$info['index']."';";	
	
	$GLOBALS['server_qry'] .= "xajax_ServerHostPlayers({$info['sid']}, 'servers', '', '".$i."', '".$number."', '".defined('IN_HOME')."', 70);";
	array_push($servers,$info);
	$i++;
}

$theme->assign('access_bans', ($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN)?true:false));
$theme->assign('server_list', $servers);
$theme->assign('IN_SERVERS_PAGE', !defined('IN_HOME'));
$theme->assign('opened_server', $number);

if(!defined('IN_HOME'))
	$theme->display('page_servers.tpl');
?>
