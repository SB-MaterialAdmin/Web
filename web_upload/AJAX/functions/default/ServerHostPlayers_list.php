<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function ServerHostPlayers_list($sid, $type="servers", $obId="") {
    $objResponse = new xajaxResponse();
    require INCLUDES_PATH.'/CServerControl.php';

    $sids = explode(";", $sid, -1);
    if(count($sids) < 1)
        return $objResponse;

    $ret = "";
    $sinfo = new CServerControl();
    for($i=0;$i<count($sids);$i++) {
        $sid = (int)$sids[$i];

        $res = $GLOBALS['db']->GetRow("SELECT sid, ip, port FROM ".DB_PREFIX."_servers WHERE sid = $sid");
        if(empty($res[1]) || empty($res[2]))
            return $objResponse;

        $info = array();
        $sinfo->Connect($res[1], $res[2]);
        $info = $sinfo->GetInfo();

        if($info)
            $ret .= trunc($info['HostName'], 48, false) . "<br />";
        else
            $ret .= "<b>Ошибка соединения</b> (<i>" . $res[1] . ":" . $res[2]. "</i>) <br />";
    }

    if($type=="id") {
        $objResponse->addAssign($obId, "innerHTML", $ret);
    } else {
        $objResponse->addAssign("ban_server_$type", "innerHTML", $ret);
    }

    return $objResponse;
}
