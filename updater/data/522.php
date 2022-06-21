<?php
$DB = \DatabaseManager::GetConnection();

$DB->Query('ALTER TABLE `{{prefix}}servers` ADD `priority` int(11) AFTER `sid`;');
$DB->Query('UPDATE `{{prefix}}servers` SET `priority` = `sid`;');
$DB->Query('ALTER TABLE `{{prefix}}servers` MODIFY `priority` int(11) NOT NULL;');