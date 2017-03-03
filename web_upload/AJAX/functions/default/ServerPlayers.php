<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function ServerPlayers($sid) {
    $objResponse = new xajaxResponse();
    require INCLUDES_PATH.'/CServerControl.php';

    $sid = (int)$sid;

    $res = $GLOBALS['db']->GetRow("SELECT sid, ip, port FROM ".DB_PREFIX."_servers WHERE sid = $sid");
    if(empty($res[1]) || empty($res[2])) {
        ShowBox_ajx('Ошибка', 'IP или порт не назначен :o', 'red', '', true, $objResponse);
        return $objResponse;
    }

    $info = array();
    $sinfo = new CServerControl();
    $sinfo->Connect($res[1], $res[2]);
    $info = $sinfo->GetPlayers();

    $html = "";
    if(empty($info))
        return $objResponse;

    foreach($info AS $player) {
        $html .= '  <tr>
                        <td class="listtable_1">'.htmlentities($player['Name']).'</td>
                        <td class="listtable_1">'.(int)$player['Frags'].'</td>
                        <td class="listtable_1">'.$player['TimeF'].'</td>
                    </tr>';
    }

    $objResponse->addAssign("player_detail_$sid", "innerHTML", $html);

    $objResponse->addScript("setTimeout('xajax_ServerPlayers($sid)', 5000);");
    $objResponse->addScript("$('opener_$sid').setProperty('onclick', '');");
    return $objResponse;
}
