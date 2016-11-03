<?php
if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
global $userbank, $theme;

	if(!$userbank->HasAccess(ADMIN_OWNER))
		CreateRedBox("Доступ запрещен!", "У вас нету доступных привилегий на просмотр данной страницы.");
	else {
		$objResponse = new xajaxResponse();
		
		$_GET['id'] = preg_replace('/[^0-9]/', '', $_GET['id']);

			if($_GET['o'] == "del"){
					$check_sys = $GLOBALS['db']->GetOne("SELECT system FROM `" . DB_PREFIX . "_menu` WHERE id = '".(int)$_GET['id']."'");
					if($check_sys != "1"){
						$gg_check_sys = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_menu` WHERE id = '".(int)$_GET['id']."'");
						if($gg_check_sys)
							ShowBox_ajx("Успешно", "Ссылка была успешно удалена из меню!", "green", "index.php/?p=admin&c=menu", false, $objResponse); // не работает 
					}else
						ShowBox_ajx("Ошибка", "Системную ссылку удалить невозможно!", "red", "index.php/?p=admin&c=menu"); // не работает 
			}elseif($_GET['o'] == "on"){
				$check_sys = $GLOBALS['db']->GetOne("SELECT enabled FROM `" . DB_PREFIX . "_menu` WHERE id = '".(int)$_GET['id']."'");
				if($check_sys == "0"){
					$gg_check_sys = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_menu` SET `enabled` = '1' WHERE id = '".(int)$_GET['id']."'");
					if($gg_check_sys)
						ShowBox_ajx("Успешно", "Ссылка была успешно включена в главное меню SourceBans!", "green", "index.php/?p=admin&c=menu", false, $objResponse); // не работает 
				}else
					ShowBox_ajx("Ошибка", "Данная ссылка уже и так отключена!", "red", "index.php/?p=admin&c=menu", false, $objResponse); // не работает 
			}elseif($_GET['o'] == "off"){
				$check_sys = $GLOBALS['db']->GetOne("SELECT enabled FROM `" . DB_PREFIX . "_menu` WHERE id = '".(int)$_GET['id']."'");
				if($check_sys == "1"){
					$gg_check_sys = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_menu` SET `enabled` = '0' WHERE id = '".(int)$_GET['id']."'");
					if($gg_check_sys)
						ShowBox_ajx("Успешно", "Ссылка была успешно удалена из главного меню SourceBans!", "green", "index.php/?p=admin&c=menu", false, $objResponse); // не работает 
				}else
					ShowBox_ajx("Ошибка", "Данная ссылка уже и так включена!", "red", "index.php/?p=admin&c=menu", false, $objResponse); // не работает 
			}
		}
		if(isset($_POST['Link']))
		{ 
			if ($_POST['Link'] == "add"){
				$on_act = (isset($_POST['on_link']) && $_POST['on_link'] == "on" ? 1 : 0);
				$add = $GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_menu` (`text`, `description`, `url`, `system`, `enabled`, `priority`) VALUES (?, ?, ?, 0, ?, ?);", array($_POST['names_link'], $_POST['des_link'], $_POST['url_link'], $on_act, $_POST['priora_link'])); 
				echo "ВСЕ ГУТ ДОБАВИЛОСЬ";
				exit(); // Чтобы крузя запилил сюда ShowBox с редериктом на "index.php/?p=admin&c=menu"
			}
		}
		
		$list_menus = $GLOBALS['db']->GetAll("SELECT * FROM ".DB_PREFIX."_menu ORDER BY `priority`");
		$theme->assign('list', $list_menus);
		$theme->display('page_admin_menu.tpl');
	}
?>
