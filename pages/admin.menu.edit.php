<?php
if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
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
