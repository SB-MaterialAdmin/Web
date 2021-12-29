<?php
	$DB = \DatabaseManager::GetConnection();

	$DB->Query('
		ALTER TABLE `{{prefix}}menu`
			ADD `onlyadmin` TINYINT(1) NOT NULL DEFAULT \'0\' AFTER `enabled`;
	');