<?php
/**************************************************************************
 * Эта программа является частью SourceBans ++.
 *
 * Все права защищены © 2014-2016 Sarabveer Singh <me@sarabveer.me>
 *
 * SourceBans++ распространяется под лицензией
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
 *  * SourceBans 1.4.11
 *    Copyright © 2007-2014 SourceBans Team - Part of GameConnect
 *    Выпущено под лицензией CC BY-NC-SA 3.0
 *    Страница: <http://www.sourcebans.net/> - <http://www.gameconnect.net/>
 *
 *  * SourceBans TF2 Theme v1.0
 *    Copyright © 2014 IceMan
 *    Страница: <https://forums.alliedmods.net/showthread.php?t=252533>
 *
 ***************************************************************************/

if(!defined("IN_SB")){echo "Ошибка доступа!";die();}

global $userbank, $theme;



if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_MODS)) {
    CreateRedBox("Доступ запрещен!", "У вас нету доступных привилегий на просмотр данной страницы.");
    PageDie();
}

/* Request */
$manifest = @file_get_contents("https://raw.githubusercontent.com/CrazyHackGUT/SB_Material_Design/master/updates.json");
$manifest = json_decode($manifest, true);
if (json_last_error() != JSON_ERROR_NONE) {
    CreateRedBox("Ошибка!", "Не удаётся получить доступ к манифесту обновлений");
    PageDie();
}

$manifest = @file_get_contents($manifest['mods_manifest']);
$manifest = json_decode($manifest, true);
if (json_last_error() != JSON_ERROR_NONE) {
    CreateRedBox("Ошибка!", "Не удаётся получить доступ к манифесту репозитория МОДов!");
    PageDie();
}

/* Prepare data to displaying */
$games = $manifest['games'];
foreach ($games as &$game) {
    $game['installed'] = ((int) ($GLOBALS['db']->GetOne(sprintf("SELECT COUNT(*) FROM `%s_mods` WHERE `modfolder` = %s", DB_PREFIX, $GLOBALS['db']->qstr($game['folder'])))) == 1);
}

/* Display */
$tabs = new CTabsMenu();
$tabs->addMenuItem("Назад",0,"","index.php?p=admin&c=mods", true);
$tabs->outputMenu();

$theme->assign('mirror_iconsdir',   $manifest['manifest']['icons_dir']);
$theme->assign('mirror',            $manifest['manifest']['mirror']);
$theme->assign('modlist',           $games);
$theme->display('page_admin_mods_repo.tpl');
