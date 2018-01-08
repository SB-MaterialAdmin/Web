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

global $userbank, $theme;

if($userbank->GetAid() == -1){echo "Вы не должны быть здесь ><";die();}
		
$groupsTabMenu = new CTabsMenu();

$groupsTabMenu->addMenuItem("Информация об аккаунте", 0);
$groupsTabMenu->addMenuItem("Редактирование данных", 1);
$groupsTabMenu->outputMenu();

$res = \MaterialAdmin\DataStorage::ADOdb()->Execute("SELECT `srv_password`, `email` FROM `".DB_PREFIX."_admins` WHERE `aid` = '".$userbank->GetAid()."'");
$srvpwset = (!empty($res->fields['srv_password'])?true:false);

$user_time = $userbank->GetProperty("expired", $userbank->GetAid());
if($user_time == '' || $user_time == '0') {
	$user_time = "Навсегда";
} elseif($user_time > '0' && $user_time > time()) {
	$user_time = "Через&nbsp;".round((($user_time - time()) / 86400),0) . "&nbsp;дней&nbsp;(".date('До d.m.Y в <b>H:i</b>',$user_time).")";
} else {
	$user_time = "Истекла";
}

$theme->assign('allow_change_inf',		$GLOBALS['config']['config.changeadmininfos']);
$theme->assign('srvpwset',				$srvpwset);
$theme->assign('email',					$res->fields['email']);
$theme->assign('vk',					$userbank->GetProperty("vk", $userbank->GetAid()));
$theme->assign('skype',					$userbank->GetProperty("skype", $userbank->GetAid()));
$theme->assign('user_aid',				$userbank->GetAid());
$theme->assign('expired_time',			$user_time);
$theme->assign('web_permissions',		BitToString($userbank->GetProperty("extraflags")));
$theme->assign('server_permissions',	SmFlagsToSb($userbank->GetProperty("srv_flags")));
$theme->assign('min_pass_len',			MIN_PASS_LENGTH);

// WARNINGS
$theme->assign('warnings_enabled',		$GLOBALS['config']['admin.warns'] == "1");
$theme->assign('max_warnings',			$GLOBALS['config']['admin.warns.max']);
$theme->assign('warnings',				\MaterialAdmin\DataStorage::ADOdb()->GetAll("SELECT `reason`, `expires` FROM `" . DB_PREFIX . "_warns` WHERE (`expires` > ? OR `expires` = 0) AND `arecipient` = ?;", array(time(), $userbank->GetAid())));

$theme->left_delimiter = "-{";
$theme->right_delimiter = "}-";
$theme->display('page_youraccount.tpl');
$theme->left_delimiter = "{";
$theme->right_delimiter = "}";