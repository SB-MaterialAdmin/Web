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

	if(!$userbank->HasAccess(ADMIN_OWNER))
		CreateRedBox("Доступ запрещен!", "У вас нету доступных привилегий на просмотр данной страницы.");
	else {
	
		if(isset($_POST['Link']))
		{ 
			if ($_POST['Link'] == "edit"){
				$on_act = (isset($_POST['on_link']) && $_POST['on_link'] == "on" ? 1 : 0);
				$system = $GLOBALS['db']->GetRow("SELECT url,system FROM `" . DB_PREFIX . "_menu` WHERE `id` = " . (int) $_GET['id']);
				
				$add = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_menu` SET `text` = ?, `description` = ?, `url` = ?, `system` = ?, `enabled` = ?, `priority` = ?, `newtab` = ? WHERE `id` = ?", array($_POST['names_link'], $_POST['des_link'], ((int) $system['system']!=0)?$_POST['url_link']:$system['url'], $system['system'], $on_act, $_POST['priora_link'], (($_POST['onNewTab']=="on")?"1":"0"), (int) $_GET['id']));
				
				PushScriptToExecuteAfterLoadPage(sprintf("setTimeout(function() { %s; }, 1350);", generateMsgBoxJS("Успех!", "Ссылка успешно сохранена!", "green", "", true)));
				FatalRefresh("index.php?p=admin&c=menu");
			}
		}
			
			
		$list_menu = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_menu WHERE id = '".(int)$_GET['id']."';");
		if (count($list_menu) > 0) {
			$theme->assign('text', $list_menu['text']);
			$theme->assign('url', $list_menu['url']);
			$theme->assign('des', $list_menu['description']);
			$theme->assign('prior', $list_menu['priority']);
			$theme->assign('enab', $list_menu['enabled']);
			$theme->assign('system', ($list_menu['system']==1));
			$theme->left_delimiter = "{";
			$theme->right_delimiter = "}";
			$theme->display('page_admin_menu_edit.tpl');
			echo "<script>$('on_link').checked = ".(int)$list_menu['enabled'].";</script>";
			echo "<script>$('onNewTab').checked = ".(int)$list_menu['newtab']."</script>";
		}
	}
?>
