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

global $userbank, $theme;

//serverlist
$server_list = $GLOBALS['db']->Execute("SELECT sid, ip, port FROM `" . DB_PREFIX . "_servers` WHERE enabled = 1");
$servers = array();
$serverscript = "<script type=\"text/javascript\">";
while (!$server_list->EOF)
{
	$info = array();
    $serverscript .= "xajax_ServerHostPlayers('".$server_list->fields[0]."', 'id', 'ss".$server_list->fields[0]."', '', '', false, 200);";
	$info['sid'] = $server_list->fields[0];
	$info['ip'] = $server_list->fields[1];
	$info['port'] = $server_list->fields[2];
	array_push($servers,$info);
	$server_list->MoveNext();
}
$serverscript .= "</script>";

//webgrouplist
$webgroup_list = $GLOBALS['db']->Execute("SELECT gid, name FROM ". DB_PREFIX ."_groups WHERE type = '1'");
$webgroups = array();
while (!$webgroup_list->EOF)
{
	$data = array();
	$data['gid'] = $webgroup_list->fields['gid'];
	$data['name'] = $webgroup_list->fields['name'];

	array_push($webgroups,$data);
	$webgroup_list->MoveNext();
}

//serveradmingrouplist
$srvadmgroup_list = $GLOBALS['db']->Execute("SELECT name FROM ". DB_PREFIX ."_srvgroups ORDER BY name ASC");
$srvadmgroups = array();
while (!$srvadmgroup_list->EOF)
{
	$data = array();
	$data['name'] = $srvadmgroup_list->fields['name'];
	
	array_push($srvadmgroups,$data);
	$srvadmgroup_list->MoveNext();
}

//servergroup
$srvgroup_list = $GLOBALS['db']->Execute("SELECT gid, name FROM " . DB_PREFIX . "_groups WHERE type = '3'");
$srvgroups = array();
while (!$srvgroup_list->EOF)
{
	$data = array();
	$data['gid'] = $srvgroup_list->fields['gid'];
	$data['name'] = $srvgroup_list->fields['name'];
	
	array_push($srvgroups,$data);
	$srvgroup_list->MoveNext();
}

//webpermissions
$webflag[] = array("name" => "Главный админ", "flag"=>"ADMIN_OWNER");
$webflag[] = array("name" => "Просмотр админов", "flag"=>"ADMIN_LIST_ADMINS");
$webflag[] = array("name" => "Добавление админов", "flag"=>"ADMIN_ADD_ADMINS");
$webflag[] = array("name" => "Редактирование админов", "flag"=>"ADMIN_EDIT_ADMINS");
$webflag[] = array("name" => "Удаление админов", "flag"=>"ADMIN_DELETE_ADMINS");
$webflag[] = array("name" => "Просмотр серверов", "flag"=>"ADMIN_LIST_SERVERS");
$webflag[] = array("name" => "Добавление серверов", "flag"=>"ADMIN_ADD_SERVER");
$webflag[] = array("name" => "Редактирование серверов", "flag"=>"ADMIN_EDIT_SERVERS");
$webflag[] = array("name" => "Удаление серверов", "flag"=>"ADMIN_DELETE_SERVERS");
$webflag[] = array("name" => "Добавление банов", "flag"=>"ADMIN_ADD_BAN");
$webflag[] = array("name" => "Редактирование своих банов", "flag"=>"ADMIN_EDIT_OWN_BANS");
$webflag[] = array("name" => "Редактирование банов групп", "flag"=>"ADMIN_EDIT_GROUP_BANS");
$webflag[] = array("name" => "Редактирование всех банов", "flag"=>"ADMIN_EDIT_ALL_BANS");
$webflag[] = array("name" => "Протесты банов", "flag"=>"ADMIN_BAN_PROTESTS");
$webflag[] = array("name" => "Предложение банов", "flag"=>"ADMIN_BAN_SUBMISSIONS");
$webflag[] = array("name" => "Удаление банов", "flag"=>"ADMIN_DELETE_BAN");
$webflag[] = array("name" => "Разбан своих банов", "flag"=>"ADMIN_UNBAN_OWN_BANS");
$webflag[] = array("name" => "Разбан банов групп", "flag"=>"ADMIN_UNBAN_GROUP_BANS");
$webflag[] = array("name" => "Разбан всех банов", "flag"=>"ADMIN_UNBAN");
$webflag[] = array("name" => "Импорт банов", "flag"=>"ADMIN_BAN_IMPORT");
$webflag[] = array("name" => "Уведомление по e-mail о предложении бана", "flag"=>"ADMIN_NOTIFY_SUB");
$webflag[] = array("name" => "Уведомление по e-mail о протесте бана", "flag"=>"ADMIN_NOTIFY_PROTEST");
$webflag[] = array("name" => "Просмотр групп", "flag"=>"ADMIN_LIST_GROUPS");
$webflag[] = array("name" => "Добавление групп", "flag"=>"ADMIN_ADD_GROUP");
$webflag[] = array("name" => "Редактирование групп", "flag"=>"ADMIN_EDIT_GROUPS");
$webflag[] = array("name" => "УДаление групп", "flag"=>"ADMIN_DELETE_GROUPS");
$webflag[] = array("name" => "Настройки ВЕБ", "flag"=>"ADMIN_WEB_SETTINGS");
$webflag[] = array("name" => "Просмотр МОДов", "flag"=>"ADMIN_LIST_MODS");
$webflag[] = array("name" => "Добавление МОДов", "flag"=>"ADMIN_ADD_MODS");
$webflag[] = array("name" => "Редактирование МОДов", "flag"=>"ADMIN_EDIT_MODS");
$webflag[] = array("name" => "Удаление МОДов", "flag"=>"ADMIN_DELETE_MODS");
$webflags = array();
foreach($webflag AS $flag)
{
	$data['name'] = $flag["name"];
	$data['flag'] = $flag["flag"];
	
	array_push($webflags, $data);
}

//server permissions
$serverflag[] = array("name" => "[z] Главный админ", "flag" => "SM_ROOT");
$serverflag[] = array("name" => "[a] Резервный слот", "flag" => "SM_RESERVED_SLOT");
$serverflag[] = array("name" => "[b] Админ", "flag" => "SM_GENERIC");
$serverflag[] = array("name" => "[c] Кик", "flag" => "SM_KICK");
$serverflag[] = array("name" => "[d] Бан", "flag" => "SM_BAN");
$serverflag[] = array("name" => "[e] Разбан", "flag" => "SM_UNBAN");
$serverflag[] = array("name" => "[f] Слэй", "flag" => "SM_SLAY");
$serverflag[] = array("name" => "[g] Смена карты", "flag" => "SM_MAP");
$serverflag[] = array("name" => "[h] Изменение КВАРов", "flag" => "SM_CVAR");
$serverflag[] = array("name" => "[i] Исполнение конфигов", "flag" => "SM_CONFIG");
$serverflag[] = array("name" => "[j] Админский чат", "flag" => "SM_CHAT");
$serverflag[] = array("name" => "[k] Голосования", "flag" => "SM_VOTE");
$serverflag[] = array("name" => "[l] Пароль сервера", "flag" => "SM_PASSWORD");
$serverflag[] = array("name" => "[m] РКОН", "flag" => "SM_RCON");
$serverflag[] = array("name" => "[n] Включение читов", "flag" => "SM_CHEATS");
$serverflag[] = array("name" => "[o] Дополнительный флаг 1", "flag" => "SM_CUSTOM1");
$serverflag[] = array("name" => "[p] Дополнительный флаг 2", "flag" => "SM_CUSTOM2");
$serverflag[] = array("name" => "[q] Дополнительный флаг 3", "flag" => "SM_CUSTOM3");
$serverflag[] = array("name" => "[r] Дополнительный флаг 4", "flag" => "SM_CUSTOM4");
$serverflag[] = array("name" => "[s] Дополнительный флаг 5", "flag" => "SM_CUSTOM5");
$serverflag[] = array("name" => "[t] Дополнительный флаг 6", "flag" => "SM_CUSTOM6");
$serverflags = array();
foreach($serverflag AS $flag)
{
	$data['name'] = $flag["name"];
	$data['flag'] = $flag["flag"];
	
	array_push($serverflags, $data);
}

if($_GET['showexpiredadmins'] == 'true') {
	$plus_adm = "1";
}else {
	$plus_adm = "";
}

$theme->assign('exiperd_admins', $plus_adm);
$theme->assign('server_list', $servers);
$theme->assign('server_script', $serverscript);
$theme->assign('webgroup_list', $webgroups);
$theme->assign('srvadmgroup_list', $srvadmgroups);
$theme->assign('srvgroup_list', $srvgroups);
$theme->assign('admwebflag_list', $webflags);
$theme->assign('admsrvflag_list', $serverflags);
$theme->assign('can_editadmin', $userbank->HasAccess(ADMIN_EDIT_ADMINS|ADMIN_OWNER));

$theme->display('box_admin_admins_search.tpl');
?>
