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

$warnings = $GLOBALS['db']->GetAll("SELECT `id`, `reason`, `expires`, `user` AS `from` FROM `" . DB_PREFIX . "_warns` INNER JOIN `" . DB_PREFIX . "_admins` ON `" . DB_PREFIX . "_warns`.`afrom` = `" . DB_PREFIX . "_admins`.`aid` WHERE `arecipient` = " . (int) $_GET['id'] . ";");
foreach ($warnings as &$warning) {
	$expires = (int) $warning['expires'];
	if ($expires > time()) {
		$warning['expires'] = "Через&nbsp;".round((($expires - time()) / 86400),0) . "&nbsp;дней&nbsp;(".date('До d.m.Y в <b>H:i</b>', $expires).")";
		$warning['expired'] = false;
	} else if ($warning['expires'] == -1) {
		$warning['expires'] = "Снят";
		$warning['expired'] = true;
	} else {
		$warning['expires'] = "Истёк";
		$warning['expired'] = true;
	}
}
$theme->assign('Warnings', $warnings);
$theme->assign('count', count($warnings));

$theme->assign('myId', $userbank->GetAid());
$theme->assign('thisId', $_GET['id']);

$theme->display('page_admin_admins_warnings.tpl');
