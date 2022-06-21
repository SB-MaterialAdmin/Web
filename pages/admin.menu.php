<?php
if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
global $userbank, $theme;

	if(!$userbank->HasAccess(ADMIN_OWNER))
		CreateRedBox("Доступ запрещен!", "У вас нету доступных привилегий на просмотр данной страницы.");
	else {
		if(!empty($_GET['o']) and !empty($_GET['id']) and !is_numeric($_GET['id'])){
			PageDie();
		}else{
			if(($_GET['o'] == "del") and !empty($_GET['o'])){
					$check_sys = $GLOBALS['db']->GetOne("SELECT system FROM `" . DB_PREFIX . "_menu` WHERE id = '".(int)$_GET['id']."'");
					if($check_sys != "1"){
						$gg_check_sys = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_menu` WHERE id = '".(int)$_GET['id']."'");
						if($gg_check_sys)
							AddScriptWithReload(sprintf("setTimeout(function() { %s; }, 1350);", generateMsgBoxJS("Успех!", "Ссылка была успешно удалена!", "green", "", true)), "index.php?p=admin&c=menu");
					}else
						AddScriptWithReload(sprintf("setTimeout(function() { %s; }, 1350);", generateMsgBoxJS("Ошибка", "Системную ссылку удалить невозможно!", "red", "", true)), "index.php?p=admin&c=menu");
			}elseif(($_GET['o'] == "on") and !empty($_GET['o'])){
				$check_sys = $GLOBALS['db']->GetOne("SELECT enabled FROM `" . DB_PREFIX . "_menu` WHERE id = '".(int)$_GET['id']."'");
				if($check_sys == "0"){
					$gg_check_sys = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_menu` SET `enabled` = '1' WHERE id = '".(int)$_GET['id']."'");
					if($gg_check_sys)
						AddScriptWithReload(sprintf("setTimeout(function() { %s; }, 1350);", generateMsgBoxJS("Успех!", "Ссылка была успешно добавлена в главное меню SourceBans!", "green", "", true)), "index.php?p=admin&c=menu");
				}else
					AddScriptWithReload(sprintf("setTimeout(function() { %s; }, 1350);", generateMsgBoxJS("Ошибка", "Данная ссылка уже и так отключена!", "red", "", true)), "index.php?p=admin&c=menu");
			}elseif(($_GET['o'] == "off") and !empty($_GET['o'])){
				$check_sys = $GLOBALS['db']->GetOne("SELECT enabled FROM `" . DB_PREFIX . "_menu` WHERE id = '".(int)$_GET['id']."'");
				if($check_sys == "1"){
					$gg_check_sys = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_menu` SET `enabled` = '0' WHERE id = '".(int)$_GET['id']."'");
					if($gg_check_sys)
						AddScriptWithReload(sprintf("setTimeout(function() { %s; }, 1350);", generateMsgBoxJS("Успех!", "Ссылка была успешно удалена из главного меню!", "green", "", true)), "index.php?p=admin&c=menu");
				}else
					AddScriptWithReload(sprintf("setTimeout(function() { %s; }, 1350);", generateMsgBoxJS("Ошибка", "Данная ссылка уже и так включена!", "red", "", true)), "index.php?p=admin&c=menu");
			}
		}
		if(isset($_POST['Link']))
		{ 
			if ($_POST['Link'] == "add"){
				$on_act = (isset($_POST['on_link']) && $_POST['on_link'] == "on" ? 1 : 0);
				$add = $GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_menu` (`text`, `description`, `url`, `system`, `enabled`, `priority`, `newtab`) VALUES (?, ?, ?, 0, ?, ?, ?);", array($_POST['names_link'], $_POST['des_link'], $_POST['url_link'], $on_act, $_POST['priora_link'], (($_POST['onNewTab']=="on")?"1":"0"))); 
				AddScriptWithReload(sprintf("setTimeout(function() { %s; }, 1350);", generateMsgBoxJS("Успех!", sprintf("Ссылка была успешно создана%s!", ($_POST[$on_act]==1)?" и добавлена в меню":""), "green", "", true)), "index.php?p=admin&c=menu");
			}
		}
		
		$list_menus = $GLOBALS['db']->GetAll("SELECT * FROM ".DB_PREFIX."_menu ORDER BY `priority` DESC");
		$theme->assign('list', $list_menus);
		$theme->assign('test', $test);
		$theme->display('page_admin_menu.tpl');
	}
?>
