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

 define('IS_UPDATE', true);
 include "../init.php";
 $theme->clear_compiled_tpl();

 define('IS_AJAX',   isset($_GET['updater_ajax_call']));

 include INCLUDES_PATH . "/CUpdate.php";
 $updater = new CUpdater();
 
 $setup = "Проверка текущей версии SourceBans: <b> " . $updater->getCurrentRevision() . "</b>";
 if(!$updater->needsUpdate())
 {
	if (IS_AJAX)
		die(json_encode(['result' => false, 'reason' => "Система в обновлениях не нуждается."]));
	$setup .= "<br /><br />Обновление не требуется, удалите папку /<b>updater</b>";
	$theme->assign('setup', $setup);
	$theme->assign('progress', "");
	$theme->display('updater.tpl');
	die();
 }
 $setup .= "<br />Обновление до версии: <b>" . $updater->getLatestPackageVersion() . "</b>";
 
 $progress = $updater->doUpdates();
 
 if (IS_AJAX)
	die(json_encode(['result' => true]));
 $theme->assign('setup', $setup);
 $theme->assign('progress', $progress);
 $theme->display('updater.tpl');
?>
