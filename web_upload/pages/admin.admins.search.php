<?php
// *************************************************************************
//  This file is part of SourceBans++.
//
//  Copyright (C) 2014-2016 Sarabveer Singh <me@sarabveer.me>
//
//  SourceBans++ is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, per version 3 of the License.
//
//  SourceBans++ is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with SourceBans++. If not, see <http://www.gnu.org/licenses/>.
//
//  This file is based off work covered by the following copyright(s):  
//
//   SourceBans 1.4.11
//   Copyright (C) 2007-2015 SourceBans Team - Part of GameConnect
//   Licensed under GNU GPL version 3, or later.
//   Page: <http://www.sourcebans.net/> - <https://github.com/GameConnect/sourcebansv1>
//
// *************************************************************************

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
$webflags = [];

$webflags[] = ["name" => "Главный админ", "flag"=>"ADMIN_OWNER"];
$webflags[] = ["name" => "Просмотр админов", "flag"=>"ADMIN_LIST_ADMINS"];
$webflags[] = ["name" => "Добавление админов", "flag"=>"ADMIN_ADD_ADMINS"];
$webflags[] = ["name" => "Редактирование админов", "flag"=>"ADMIN_EDIT_ADMINS"];
$webflags[] = ['name' => 'Выдача предупреждений админам', 'flag' => 'ADMIN_ISSUE_WARNS_ADMINS'];
$webflags[] = ["name" => "Удаление админов", "flag"=>"ADMIN_DELETE_ADMINS"];
$webflags[] = ["name" => "Просмотр серверов", "flag"=>"ADMIN_LIST_SERVERS"];
$webflags[] = ["name" => "Добавление серверов", "flag"=>"ADMIN_ADD_SERVER"];
$webflags[] = ["name" => "Редактирование серверов", "flag"=>"ADMIN_EDIT_SERVERS"];
$webflags[] = ["name" => "Удаление серверов", "flag"=>"ADMIN_DELETE_SERVERS"];
$webflags[] = ["name" => "Добавление банов", "flag"=>"ADMIN_ADD_BAN"];
$webflags[] = ["name" => "Редактирование своих банов", "flag"=>"ADMIN_EDIT_OWN_BANS"];
$webflags[] = ["name" => "Редактирование банов групп", "flag"=>"ADMIN_EDIT_GROUP_BANS"];
$webflags[] = ["name" => "Редактирование всех банов", "flag"=>"ADMIN_EDIT_ALL_BANS"];
$webflags[] = ["name" => "Протесты банов", "flag"=>"ADMIN_BAN_PROTESTS"];
$webflags[] = ["name" => "Предложение банов", "flag"=>"ADMIN_BAN_SUBMISSIONS"];
$webflags[] = ["name" => "Удаление банов", "flag"=>"ADMIN_DELETE_BAN"];
$webflags[] = ["name" => "Разбан своих банов", "flag"=>"ADMIN_UNBAN_OWN_BANS"];
$webflags[] = ["name" => "Разбан банов групп", "flag"=>"ADMIN_UNBAN_GROUP_BANS"];
$webflags[] = ["name" => "Разбан всех банов", "flag"=>"ADMIN_UNBAN"];
$webflags[] = ["name" => "Импорт банов", "flag"=>"ADMIN_BAN_IMPORT"];
$webflags[] = ["name" => "Уведомление по e-mail о предложении бана", "flag"=>"ADMIN_NOTIFY_SUB"];
$webflags[] = ["name" => "Уведомление по e-mail о протесте бана", "flag"=>"ADMIN_NOTIFY_PROTEST"];
$webflags[] = ["name" => "Просмотр групп", "flag"=>"ADMIN_LIST_GROUPS"];
$webflags[] = ["name" => "Добавление групп", "flag"=>"ADMIN_ADD_GROUP"];
$webflags[] = ["name" => "Редактирование групп", "flag"=>"ADMIN_EDIT_GROUPS"];
$webflags[] = ["name" => "УДаление групп", "flag"=>"ADMIN_DELETE_GROUPS"];
$webflags[] = ["name" => "Настройки ВЕБ", "flag"=>"ADMIN_WEB_SETTINGS"];
$webflags[] = ["name" => "Просмотр МОДов", "flag"=>"ADMIN_LIST_MODS"];
$webflags[] = ["name" => "Добавление МОДов", "flag"=>"ADMIN_ADD_MODS"];
$webflags[] = ["name" => "Редактирование МОДов", "flag"=>"ADMIN_EDIT_MODS"];
$webflags[] = ["name" => "Удаление МОДов", "flag"=>"ADMIN_DELETE_MODS"];

//server permissions
$serverflags = [];
$serverflags[] = ["name" => "[z] Главный админ", "flag" => "SM_ROOT"];
$serverflags[] = ["name" => "[a] Резервный слот", "flag" => "SM_RESERVED_SLOT"];
$serverflags[] = ["name" => "[b] Админ", "flag" => "SM_GENERIC"];
$serverflags[] = ["name" => "[c] Кик", "flag" => "SM_KICK"];
$serverflags[] = ["name" => "[d] Бан", "flag" => "SM_BAN"];
$serverflags[] = ["name" => "[e] Разбан", "flag" => "SM_UNBAN"];
$serverflags[] = ["name" => "[f] Слэй", "flag" => "SM_SLAY"];
$serverflags[] = ["name" => "[g] Смена карты", "flag" => "SM_MAP"];
$serverflags[] = ["name" => "[h] Изменение КВАРов", "flag" => "SM_CVAR"];
$serverflags[] = ["name" => "[i] Исполнение конфигов", "flag" => "SM_CONFIG"];
$serverflags[] = ["name" => "[j] Админский чат", "flag" => "SM_CHAT"];
$serverflags[] = ["name" => "[k] Голосования", "flag" => "SM_VOTE"];
$serverflags[] = ["name" => "[l] Пароль сервера", "flag" => "SM_PASSWORD"];
$serverflags[] = ["name" => "[m] РКОН", "flag" => "SM_RCON"];
$serverflags[] = ["name" => "[n] Включение читов", "flag" => "SM_CHEATS"];
$serverflags[] = ["name" => "[o] Дополнительный флаг 1", "flag" => "SM_CUSTOM1"];
$serverflags[] = ["name" => "[p] Дополнительный флаг 2", "flag" => "SM_CUSTOM2"];
$serverflags[] = ["name" => "[q] Дополнительный флаг 3", "flag" => "SM_CUSTOM3"];
$serverflags[] = ["name" => "[r] Дополнительный флаг 4", "flag" => "SM_CUSTOM4"];
$serverflags[] = ["name" => "[s] Дополнительный флаг 5", "flag" => "SM_CUSTOM5"];
$serverflags[] = ["name" => "[t] Дополнительный флаг 6", "flag" => "SM_CUSTOM6"];

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
$theme->assign('can_warn', $userbank->HasAccess(ADMIN_ISSUE_WARNS_ADMINS|ADMIN_OWNER));

$theme->display('box_admin_admins_search.tpl');
