<?php
	if (!defined("IN_SB")) {
		echo "Ошибка доступа!";
		die();
	}

	global $userbank, $theme;

	$DB = \DatabaseManager::GetConnection();

	if (!$userbank->HasAccess(ADMIN_OWNER)) {
		CreateRedBox("Доступ запрещен!", "У вас нету доступных привилегий на просмотр данной страницы.");
	} else {
		if (isset($_POST['Link'])) {
			if ($_POST['Link'] == "edit") {
				// insert.
				$on_act = (isset($_POST['on_link']) && $_POST['on_link'] == "on" ? 1 : 0);

				$DB->Prepare('
					UPDATE
						`{{prefix}}menu`
					SET
						`text` = :text,
						`description` = :description,
						`url` = :url,
						`enabled` = :enabled,
						`onlyadmin` = :onlyadmin,
						`priority` = :priority
					WHERE
						`id` = :id'
				);

				$DB->BindMultipleData([
					'id'				=> $_GET['id'],
					'text'				=> $_POST['names_link'],
					'description'		=> $_POST['des_link'],
					'url'				=> $_POST['url_link'],
					'enabled'			=> $on_act,
					'onlyadmin'			=> ($_POST['onNewTab2'] == 'on') ? 1 : 0,
					'priority'			=> $_POST['priora_link']
				]);

				$DB->Finish();

				PushScriptToExecuteAfterLoadPage(sprintf("setTimeout(function() { %s; }, 1350);", generateMsgBoxJS("Успех!", "Ссылка успешно сохранена!", "green", "", true)));
				FatalRefresh("index.php?p=admin&c=menu");
			}
		}

		$DB->Prepare('SELECT * FROM `{{prefix}}menu` WHERE `id` = :id');
		$DB->BindData('id', $_GET['id']);
		$Result = $DB->Finish();
		$list_menu = $Result->Single();
		$Result->EndData();

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

			echo "<script>$('on_link').checked = " . (int)$list_menu['enabled'] . ";</script>";
			echo "<script>$('onNewTab').checked = " . (int)$list_menu['newtab'] . "</script>";
			echo "<script>$('onNewTab2').checked = " . (int)$list_menu['onlyadmin'] . "</script>";
		}
	}
