<?php
$DB = \DatabaseManager::GetConnection();

$DB->Query("
  DELETE FROM
    `{{prefix}}settings`
  WHERE
    `setting` = 'config.summertime'
");