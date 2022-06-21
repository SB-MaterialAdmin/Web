<?php

$DB = \DatabaseManager::GetConnection();
$DB->Query('
    ALTER TABLE `{{prefix}}bans`
		ADD INDEX `{{prefix}}bans__created` (`created`),
		ADD INDEX `{{prefix}}bans__ip_lookup` (`ip`, `type`),
		ADD INDEX `{{prefix}}bans__steam_lookup` (`authid`, `type`),
		ADD INDEX `{{prefix}}bans__ip_steam_lookup` (`ip`, `authid`, `type`);
');