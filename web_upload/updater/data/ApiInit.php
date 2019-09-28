<?php

$DB = \DatabaseManager::GetConnection();
$DB->Query('
ALTER TABLE `{{prefix}}servers`
	ADD COLUMN `token` VARCHAR(64) NULL DEFAULT NULL AFTER `enabled`
');