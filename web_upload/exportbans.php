<?php
include_once('init.php');

$type = filterInput(INPUT_GET, 'type', FILTER_SANITIZE_STRING);

if (!$userbank->HasAccess(ADMIN_OWNER) && !$GLOBALS['config']['config.exportpublic'])
  exit('У Вас нет доступа к данной функции.');
else if (is_null($type))
	exit('Используйте только ссылки в самой системе!');

$cmd    = '';
$file   = '';
$type   = 0;
$column = '';

switch ($type)
{
    case "steam":
       $cmd    = 'banid';
       $file   = 'banned_user.cfg';
       $type   = 0;
       $column = 'authid';
       break;
    case "ip":
       $cmd    = 'addip';
       $file   = 'banned_ip.cfg';
       $type   = 1;
       $column = 'ip';
       break;
    default:
       exit("Unknown type $type");
}

$DB = \DatabaseManager::GetConnection();
$Result = $DB->Query("SELECT $column AS id FROM `{{prefix}}bans` WHERE `length` = 0 AND `RemoveType` IS NULL AND `type` = $type");

Header('Content-Type: text/plain; charset=UTF8');
Header("Content-Disposition: attachment; filename=$file");
foreach ($Result->All() as $ban)
  echo("$cmd 0 $ban[id]\r\n");
