<?php
	$DB = \DatabaseManager::GetConnection();

	$query1 = $DB->Query('
		ALTER TABLE `{{prefix}}menu`
			ADD `onlyadmin` TINYINT(1) NOT NULL DEFAULT \'0\' AFTER `enabled`;
	');

	if (!$query1) {
		return false;
	}

	$search_admin_page_sql = "
		SELECT
			COUNT(*)
		AS
			'count'
		FROM
			`{{prefix}}menu`
		WHERE
			`url`
		LIKE
			'index.php?p=admin'
	";

	$stmt = $DB->Query($search_admin_page_sql);
	if (!$stmt) {
		return false;
	}

	$count_admin_page_sql = (int)$stmt->Single()['count'];

	// This page already exists.
	// The code is needed to prevent repeated writing to the database.
	if ($count_admin_page_sql > 0) {
		return true;
	}

	$insert_sql = "
		INSERT INTO `{{prefix}}menu`
			(`text`, `description`, `url`, `system`, `enabled`, `onlyadmin`, `priority`)
		VALUES
	";

	//priority - set to 1 so that the link to the page in the menu is always at the end
	$insert_sql .= "
	(
			'<i class=\'zmdi zmdi-star zmdi-hc-fw\'></i> Админ-Панель',
			'Панель для администраторов. Управление серверами, администраторами, настройками.',
			'index.php?p=admin',
			1,
			1,
			1,
			1
		);
	";

	$query2 = $DB->Query($insert_sql);
	if (!$query2) {
		return false;
	}

	return true;
