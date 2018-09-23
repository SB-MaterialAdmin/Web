<?php
$DB = \DatabaseManager::GetConnection();

$DB->Query("
  UPDATE
    `{{prefix}}settings`
  SET
    `value` = 'Europe/Moscow'
  WHERE
    `setting` = 'config.timezone';
");