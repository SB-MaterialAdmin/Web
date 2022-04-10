<?php

/** @var Database $DB */
$DB = \DatabaseManager::GetConnection();
$DB->Query('ALTER TABLE `{{prefix}}vay4er`
	CHANGE COLUMN `value` `value` VARCHAR(16) NOT NULL DEFAULT "" AFTER `activ`;
');

$DB->Query("UPDATE `{{prefix}}vay4er`
SET `value` = LPAD(`value`, 16, 0)");
