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
				$add = $GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_menu` (`text`, `description`, `url`, `system`, `enabled`, `priority`) VALUES (?, ?, ?, 0, ?, ?);", array($_POST['names_link'], $_POST['des_link'], $_POST['url_link'], $on_act, $_POST['priora_link'])); 
				echo "ВСЕ ГУТ изменилось";
				exit(); // Чтобы крузя запилил сюда ShowBox с редериктом на "index.php/?p=admin&c=menu"
			}
		}
			
			
		$list_menu = $GLOBALS['db']->GetRow("SELECT text,url,description,priority,enabled FROM ".DB_PREFIX."_menu WHERE id = '".(int)$_GET['id']."';");
		
		$theme->assign('text', $list_menu['text']);
		$theme->assign('url', $list_menu['url']);
		$theme->assign('des', $list_menu['description']);
		$theme->assign('prior', $list_menu['priority']);
		$theme->assign('enab', $list_menu['enabled']);
		$theme->left_delimiter = "{";
		$theme->right_delimiter = "}";
		$theme->display('page_admin_menu_edit.tpl');
		echo "<script>$('on_link').checked = ".(int)$list_menu['enabled'].";</script>";
	}
?>