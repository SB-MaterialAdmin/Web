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
 
die("В РАЗРАБОТКЕ. НЕ ЗАПУСКАЙТЕ ЭТОТ ФАЙЛ, И НЕ ЗАГРУЖАЙТЕ ЕГО К СЕБЕ НА ВЕБ-СЕРВЕР!");
define('IS_UPDATE', true);
$INSTALLED = false;
require_once('init.php');
$res = $GLOBALS['db']->GetAll(sprintf("SHOW COLUMNS FROM `%s_admins`", DB_PREFIX));
foreach ($res as &$row) {
    if ($row['Field'] == "skype") {
        $INSTALLED = true;
        break;
    }
}

$setup = "";
$progress = "";
$log = [];

if ($INSTALLED) {
    $log = ['type' => 'w', 'title' => 'Попытка обновления', 'text' => 'Была произведена попытка обновить базу данных SB/SB++ до рефорка SB Material Design, когда обновление и так установлено.'];
    $setup = "Обновление структур таблиц для рефорка SB Material Design не требуется: таблицы и так пребывают в актуальном состоянии.";
} else {
    $log = ['type' => 'm', 'title' => 'Обновление с SB++ до SB Material Design', 'text' => 'Запущен процесс обновления структуры базы данных с SB++'];
}

new CSystemLog($log['type'], $log['title'], $log['text']);
$theme->assign('setup', $setup);
$theme->assign('progress', $progress);
$theme->display('updater.tpl');
?>
