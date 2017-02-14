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

global $userbank, $theme, $xajax,$user,$start;
$time = microtime();
$time = explode(" ", $time);
$time = $time[1] + $time[0];
$start = $time;
ob_start(); 

if(!defined("IN_SB"))
{
	echo "Ошибка доступа!";
	die();
}
	
if($GLOBALS['config']['template.global'] == "1"){
	$def_ch = "";
	$def_body = 'class="toggled sw-toggled"';
}else{
	$def_ch = '
				<li id="toggle-width" class="p-t-5">
					<div class="toggle-switch" data-trigger="hover" data-toggle="popover" data-placement="bottom" data-content="Включить полноэкранную работу шаблона? Ваш браузер запомнит данный выбор." title="" data-original-title="Управление">
						<input id="tw-switch" type="checkbox" hidden="hidden" />
						<label for="tw-switch" class="ts-helper"></label>
					</div>
				</li>';
	$def_body = "";
}

$th_style = $GLOBALS['config']['theme.style'];
$th_style_color = $GLOBALS['config']['theme.style.color'];

if($th_style != "" || $th_style == ""){
	if($th_style_color != ""){
		$th_style = 'style="background-color:'.$th_style_color.';"';
	}else{
		$th_style = 'data-current-skin="'.$th_style.'"';
	}
}

$bg_value = "style=\"";
if($GLOBALS['config']['theme.bg'] != ""){
		if((stristr($GLOBALS['config']['theme.bg'], '#') || stristr($GLOBALS['config']['theme.bg'], 'RGBA(') || stristr($GLOBALS['config']['theme.bg'], 'rgb(') || stristr($GLOBALS['config']['theme.bg'], 'rgba(')) == FALSE){
			$bg_value .= "background-image: url('".$GLOBALS['config']['theme.bg']."');";
		}else{
			$bg_value .= "background-color: ".$GLOBALS['config']['theme.bg'].";";
		}
	}
	
if($GLOBALS['config']['theme.bg.rep'] != ""){
	$bg_value .= "background-repeat: ".$GLOBALS['config']['theme.bg.rep'].";";
	}

if($GLOBALS['config']['theme.bg.att'] != ""){
	$bg_value .= "background-attachment: ".$GLOBALS['config']['theme.bg.att'].";";
	}

if($GLOBALS['config']['theme.bg.pos'] != ""){
	$bg_value .= "background-position: ".$GLOBALS['config']['theme.bg.pos'].";";
	}

if($GLOBALS['config']['theme.bg.size'] != ""){
	$bg_value .= "-moz-background-size: ".$GLOBALS['config']['theme.bg.size'].";";
    $bg_value .= "-webkit-background-size: ".$GLOBALS['config']['theme.bg.size'].";";
    $bg_value .= "-o-background-size: ".$GLOBALS['config']['theme.bg.size'].";";
    $bg_value .= "background-size: ".$GLOBALS['config']['theme.bg.size'].";";
	}

$bg_value .= "\"";

/////////
/////////
/////////

function toCommunityID($id) {
    if (preg_match('/^STEAM_/', $id)) {
        $parts = explode(':', $id);
        return bcadd(bcadd(bcmul($parts[2], '2'), '76561197960265728'), $parts[1]);
    } elseif (is_numeric($id) && strlen($id) < 16) {
        return bcadd($id, '76561197960265728');
    } else {
        return $id; // We have no idea what this is, so just return it.
    }
}

$dbres = $GLOBALS['db']->query("SELECT authid, vk, comment, skype, user FROM `".DB_PREFIX."_admins` WHERE `support` = '1'");
$supports = [];
while ($res = $dbres->fetch(PDO::FETCH_LAZY)) {
	$suppurt_inf = [];
	
	$suppurt_inf['user'] = stripslashes($res->user);
	$suppurt_inf['comment'] = $res->comment;
	$suppurt_inf['vk'] = $res->vk;
	$suppurt_inf['skype'] = $res->skype;
	$suppurt_inf['authid'] = toCommunityID($res->authid);
	$suppurt_inf['avatarka'] = $GLOBALS['AvatarMgr']->getUserAvatar($res->authid);
	
	array_push($supports,$suppurt_inf);
	$res->MoveNext();
}


$theme->assign('supports_list', $supports);
$theme->assign('supports_count', count($supports));

$theme->assign('avatar', $GLOBALS['AvatarMgr']->getUserAvatar($userbank->GetProperty('authid')));
$theme->assign('theme_bg',  $bg_value);
$theme->assign('theme_color',  $th_style);
$theme->assign('def_ch_chenger',  $def_ch);
$theme->assign('def_body_chenger',  $def_body);
$theme->assign('xajax_functions',  $xajax->printJavascript("scripts", "xajax.js"));
$theme->assign('header_title', str_replace("[{(page_title)}]", "[{(REWRITE_page_title)}]", $GLOBALS['config']['template.title']));
$theme->assign('vay4er_act', $GLOBALS['config']['page.vay4er']);
$theme->assign('header_logo', $GLOBALS['config']['template.logo']);
$theme->assign('username', $userbank->GetProperty("user"));
$theme->assign('logged_in', $userbank->is_logged_in());
$theme->display('page_header.tpl');
?>
