<?php
$DB = \DatabaseManager::GetConnection();
$DB->BeginTxn();

// CRON Manager update
$DB->Query('CREATE TABLE IF NOT EXISTS `{{prefix}}cron` (`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, `enabled` tinyint(1) NOT NULL DEFAULT "0", `name` varchar(256) NOT NULL, `class` varchar(256) NOT NULL, `function` varchar(256) NOT NULL, `data` text, `frequency` int(11) NOT NULL, `lastexec` int(11) NOT NULL DEFAULT "0", PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;');

// Create tasks.
\CronManager::newTask('Core_PruneBans')
  ->setClass('SBCronJob')->setFunction('PruneBans')
  ->setData([])->setFrequency(60)
  ->enable()->save();

\CronManager::newTask('Core_PruneComms')
  ->setClass('SBCronJob')->setFunction('PruneComms')
  ->setData([])->setFrequency(60)
  ->enable()->save();

\CronManager::newTask('Core_ServerCache')
  ->setClass('SBCronJob')->setFunction('ServerCache')
  ->setData([])->setFrequency(60)
  ->save();

\CronManager::newTask('Core_UpdateChecker')
  ->setClass('SBCronJob')->setFunction('UpdateChecker')
  ->setData([])->setFrequency(1800)
  ->enable()->save();

\CronManager::newTask('Core_StatsCollector')
  ->setClass('SBCronJob')->setFunction('CollectStats')
  ->setData([])->setFrequency(86400)
  ->save();

\CronManager::newTask('Core_SteamAvatarLoader')
  ->setClass('SBCronJob')->setFunction('UpdateAvatarCache')
  ->setData([])->setFrequency(86400)
  ->save();

$DB->EndTxn();