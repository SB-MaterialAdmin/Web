<?php
$Result = [
  'GameBan' => [
    'Steam' => false,
    'IP'    => false
  ],

  'CommBan' => [
    'Gag'   => false,
    'Voice' => false
  ]
];

$ReqIP = GetRequesterIP();
$ReqSteam = GetRequesterSteam();

$Result['GameBan']['IP']  = $GLOBALS['db']->GetOne("SELECT `bid` FROM `" . DB_PREFIX . "_bans` WHERE `type` = 1 AND `ip` = ? AND (`length` = '0' OR `ends` > UNIX_TIMESTAMP()) AND `RemoveType` IS NULL", array($ReqIP));

// If user shared with own SteamID, check gameban on Steam and CommBan
if ($ReqSteam != false) {
  $SteamRegex = "^STEAM_[0-9]:" . substr($ReqSteam, 8, 0) . "$";
  $Result['GameBan']['Steam'] = $GLOBALS['db']->GetOne("SELECT `bid` FROM `" . DB_PREFIX . "_bans` WHERE `type` = 0 AND (`authid` = ? OR `authid` REGEXP ?) AND (`length` = '0' OR `ends` > UNIX_TIMESTAMP()) AND RemoveType IS NULL", array($ReqSteam, $SteamRegex));
  $Result['CommBan']['Voice'] = $GLOBALS['db']->GetOne("SELECT `bid` FROM `" . DB_PREFIX . "_comms` WHERE (`type` = 1 OR `type` = 3) AND (`authid` = ? OR `authid` REGEXP ?) AND (`length` = '0' OR `ends` > UNIX_TIMESTAMP()) AND RemoveType IS NULL", array($ReqSteam, $SteamRegex));
  $Result['CommBan']['Gag']   = $GLOBALS['db']->GetOne("SELECT `bid` FROM `" . DB_PREFIX . "_comms` WHERE (`type` = 2 OR `type` = 3) AND (`authid` = ? OR `authid` REGEXP ?) AND (`length` = '0' OR `ends` > UNIX_TIMESTAMP()) AND RemoveType IS NULL", array($ReqSteam, $SteamRegex));
}

global $theme;
$theme->assign('check_result', $Result);
$theme->assign('user', array('ip' => $ReqIP, 'steam' => $ReqSteam));
$theme->display('page_checkban.tpl');
// var_dump($Result);