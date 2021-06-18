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

if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
if($userbank->GetAid() == -1){echo "Вы не должны быть здесь ><";die();}
		
$groupsTabMenu = new CTabsMenu();
$groupsTabMenu->addMenuItem("Информация", 0);
$allow_change_infos = $GLOBALS['config']['config.changeadmininfos'];
if($allow_change_infos)
	$groupsTabMenu->addMenuItem("Связь", 4);
$groupsTabMenu->addMenuItem("Сменить пароль", 1);
$groupsTabMenu->addMenuItem("Серверный пароль", 2);
$groupsTabMenu->addMenuItem("Сменить E-mail", 3);
$groupsTabMenu->outputMenu();

$res = $GLOBALS['db']->Execute("SELECT `srv_password`, `email` FROM `".DB_PREFIX."_admins` WHERE `aid` = '".$userbank->GetAid()."'");
$srvpwset = (!empty($res->fields['srv_password'])?true:false);

$user_time = $userbank->GetProperty("expired", $userbank->GetAid());
if($user_time == '' || $user_time == '0') {
	$user_time = "Навсегда";
} elseif($user_time > '0' && $user_time > time()) {
	$user_time = "Через&nbsp;".round((($user_time - time()) / 86400),0) . "&nbsp;дней&nbsp;(".date('До d.m.Y в <b>H:i</b>',$user_time).")";
} else {
	$user_time = "Истекла";
}

$theme->assign('allow_change_inf',		$allow_change_infos);
$theme->assign('srvpwset',				$srvpwset);
$theme->assign('email',					$res->fields['email']);
$theme->assign('vk',					$userbank->GetProperty("vk", $userbank->GetAid()));
$theme->assign('skype',					$userbank->GetProperty("skype", $userbank->GetAid()));
$theme->assign('user_aid',				$userbank->GetAid());
$theme->assign('expired_time',			$user_time);
$theme->assign('web_permissions',		BitToString($userbank->GetProperty("extraflags")));
$theme->assign('server_permissions',	SmFlagsToSb($userbank->GetProperty("srv_flags")));
$theme->assign('min_pass_len',			MIN_PASS_LENGTH);

$theme->left_delimiter = "-{";
$theme->right_delimiter = "}-";
$theme->display('page_youraccount.tpl');
$theme->left_delimiter = "{";
$theme->right_delimiter = "}";
?>
