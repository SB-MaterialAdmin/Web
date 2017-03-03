<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function RefreshServer($sid) {
    $objResponse = new xajaxResponse();
    $sid = (int)$sid;

    $data = $GLOBALS['db']->GetRow("SELECT ip, port FROM `".DB_PREFIX."_servers` WHERE sid = ?;", array($sid));
    if (isset($_SESSION['getInfo.' . $data['ip'] . '.' . $data['port']]) && is_array($_SESSION['getInfo.' . $data['ip'] . '.' . $data['port']]))
        unset($_SESSION['getInfo.' . $data['ip'] . '.' . $data['port']]);

    $objResponse->addScript("xajax_ServerHostPlayers('".$sid."');");
    return $objResponse;
}
