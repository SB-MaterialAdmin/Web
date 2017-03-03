<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function ServerHostPlayers($sid, $type="servers", $obId="", $tplsid="", $open="", $inHome=false, $trunchostname=48) {
    global $userbank;

    $objResponse = new xajaxResponse();
    require INCLUDES_PATH.'/CServerControl.php';
    
    $sid = (int)$sid;

    $res = $GLOBALS['db']->GetRow("SELECT se.sid, se.ip, se.port, se.modid, md.modfolder FROM ".DB_PREFIX."_servers se LEFT JOIN ".DB_PREFIX."_mods md ON md.mid=se.modid WHERE se.sid = $sid");
    if(empty($res[1]) || empty($res[2]))
        return $objResponse;

    $info = array();
    $sinfo = new CServerControl();
    $sinfo->Connect($res[1], $res[2]);
    $info = $sinfo->GetInfo();
    if($type == "servers") {
        if($info) {
            $objResponse->addAssign("host_$sid", "innerHTML", trunc($info['HostName'], $trunchostname, false));
            $objResponse->addAssign("players_$sid", "innerHTML", $info['Players'] . "/" . $info['MaxPlayers']);
            $objResponse->addAssign("os_$sid", "innerHTML", "<img src='images/" . (!empty($info['Os'])?$info['Os']:'server_small') . ".png'>");
            if($info['Secure'])
                $objResponse->addAssign("vac_$sid", "innerHTML", "<img src='images/shield.png' />");
            else
                $objResponse->addAssign("vac_$sid", "innerHTML", "<img src='images/noshield.png' />");
            $objResponse->addAssign("map_$sid", "innerHTML", basename($info['Map'])); // Strip Steam Workshop folder
            if(!$inHome) {
                $objResponse->addScript("$('mapimg_$sid').setProperty('src', '".GetMapImage(basename($info['Map']), $res[4])."').setProperty('alt', '".$info['Map']."').setProperty('title', '".basename($info['Map'])."');");
                $objResponse->addAssign("mapimg_$sid", "innerHTML", GetMapImage(basename($info['Map']), $res[4]));
                if($info['Players'] == 0) {
                    $objResponse->addScript("$('sinfo_$sid').setStyle('display', 'none');");
                    $objResponse->addScript("$('noplayer_$sid').setStyle('display', 'block');");
                    $objResponse->addScript("$('serverwindow_$sid').setStyle('height', '64px');");
                } else {
                    $objResponse->addScript("$('sinfo_$sid').setStyle('display', 'block');");
                    $objResponse->addScript("$('noplayer_$sid').setStyle('display', 'none');");
                    if(!defined('IN_HOME')) {
                        $players = $sinfo->GetPlayers();
                        if ($players !== false) {
                            $objResponse->addScript('var toempty = document.getElementById("playerlist_'.$sid.'");
                            var empty = toempty.cloneNode(false);
                            toempty.parentNode.replaceChild(empty,toempty);');

                            $objResponse->addScript('var e = document.getElementById("playerlist_'.$sid.'");
                            var tr = e.insertRow("-1");
                                // Name Top TD
                                var td = tr.insertCell("-1");
                                td.setAttribute("width","50%");
                                td.className = "text-center p-5 bgm-bluegray c-white";
                                var b = document.createElement("b");
                                var txt = document.createTextNode("Имя");
                                b.appendChild(txt);
                                td.appendChild(b);

                                // Score Top TD
                                var td = tr.insertCell("-1");
                                td.setAttribute("width","15%");
                                td.className = "p-5 bgm-bluegray c-white";
                                var b = document.createElement("b");
                                var txt = document.createTextNode("Счет");
                                b.appendChild(txt);
                                td.appendChild(b);

                                // Time Top TD
                                var td = tr.insertCell("-1");
                                td.className = "p-5 bgm-bluegray c-white";
                                var b = document.createElement("b");
                                var txt = document.createTextNode("Время");
                                b.appendChild(txt);
                                td.appendChild(b);');

                                $playercount = 0;

                            $needAddPlayerManaging = (($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN) && $GLOBALS['db']->GetOne(sprintf("SELECT COUNT(*) FROM `%s_admins_servers_groups` WHERE `admin_id` = %d AND `server_id` = %d", DB_PREFIX, $userbank->GetAid(), (int)$sid)) == 1) || $userbank->HasAccess(ADMIN_OWNER));

                            if($needAddPlayerManaging) {
                                $dl = "a";
                                $dl2 = 'var i_i = document.createElement("i");
                                        i_i.className = "zmdi zmdi-label c-lightblue p-r-10 p-l-5";
                                        i_i.style = "font-size: 17px;";
                                        a.appendChild(i_i);
                                        td.appendChild(a);';
                                $dl_fix = 'p-l-5 ';
                            }else{
                                $dl = "span";
                                $dl2 = "";
                                $dl_fix = 'p-l-10 ';
                            }
                            $id = 0;
                            foreach($players as $player) {
                                if (empty($player['Name'])) continue;
                                $id++;
                                $objResponse->addScript('var e = document.getElementById("playerlist_'.$sid.'");
                                                        var tr = e.insertRow("-1");
                                                        tr.id = "player_s'.$sid.'p'.$id.'";

                                                        // Name TD
                                                        var td = tr.insertCell("-1");
                                                        td.className = "'.$dl_fix.'p-t-5";
                                                        var txt = document.createTextNode("'.str_replace('"', '\"', $player["Name"]).'");
                                                        var a = document.createElement("'.$dl.'");
                                                        a.href = "#player_s' . $sid . 'p' . $id . '_t";
                                                        var att = document.createAttribute("data-toggle");
                                                        att.value = "modal"; 
                                                        a.setAttributeNode(att);
                                                        '.$dl2.'
                                                        td.appendChild(txt);

                                                        // Score TD
                                                        var td = tr.insertCell("-1");
                                                        td.className = "listtable_1";
                                                        var txt = document.createTextNode("'.$player["Frags"].'");
                                                        td.appendChild(txt);

                                                        // Time TD
                                                        var td = tr.insertCell("-1");
                                                        td.className = "p-l-10";
                                                        var txt = document.createTextNode("'.SecondsToString($player['Time']).'");
                                                        td.appendChild(txt);');

                                if($needAddPlayerManaging) {
                                    $objResponse->addScript('
                                        var div = document.createElement("div");
                                        div.className = "modal fade";
                                        div.id = "player_s' . $sid . 'p' . $id . '_t";
                                        var att = document.createAttribute("tabindex");
                                        var att1 = document.createAttribute("role");
                                        var att2 = document.createAttribute("aria-hidden");
                                        att.value = "-1"; 
                                        att1.value = "dialog"; 
                                        att2.value = "true"; 
                                        div.setAttributeNode(att);   
                                        div.setAttributeNode(att1);   
                                        div.setAttributeNode(att2);   
                                        div.innerHTML = "\
                                            <div class=\'modal-dialog modal-sm\'>\
                                                <div class=\'modal-content\'>\
                                                    <div class=\'modal-header\'>\
                                                        <h4 class=\'modal-title\'>'.str_replace('"', '\"', $player["Name"]).'</h4>\
                                                    </div>\
                                                    <div class=\'modal-body\'>\
                                                        <p class=\"m-b-10\"><button class=\"btn btn-link btn-block\" data-dismiss=\"modal\" onclick=\"KickPlayerConfirm('.$sid.', \''.str_replace('"', '"', $player["Name"]).'\', 0);\">Кикнуть</button></p>\
                                                        <p class=\"m-b-10\"><button class=\"btn btn-link btn-block\" href=\"#\" data-dismiss=\'modal\' onclick=\"ViewCommunityProfile('.$sid.', \''.str_replace('"', '\"', $player["Name"]).'\');\">Профиль</button></p>\
                                                        <p class=\"m-b-10\"><a href=\"index.php?p=admin&c=bans&action=pasteBan&sid='.$sid.'&pName='.urlencode(str_replace('"', '\"', $player["Name"])).'\"><button class=\"btn btn-link btn-block\">Бан</button></a></p>\
                                                        <p class=\"m-b-10\"><a href=\"index.php?p=admin&c=comms&action=pasteBan&sid='.$sid.'&pName='.urlencode(str_replace('"', '\"', $player["Name"])).'\"><button class=\"btn btn-link btn-block\">Заглушить</button></a></p>\
                                                        <p class=\"m-b-10\"><button class=\"btn btn-link btn-block\" href=\"#\" data-dismiss=\'modal\' onclick=\"OpenMessageBox('.$sid.', \''.str_replace('"', '\"', $player["Name"]).'\', 1);\">Отправить сообщение</button></p>\
                                                    </div>\
                                                    <!--<div class=\'modal-footer\'>\
                                                        <button type=\'button\' class=\'btn btn-link\' data-dismiss=\'modal\'>Отмена</button>\
                                                    </div>-->\
                                                </div>\
                                            </div>\
                                        ";

                                        document.body.appendChild(div);');
                                }
                                $playercount++;
                            }
                        }
                    }

                    if($playercount>15)
                        $height = 329 + 16 * ($playercount-15) + 4 * ($playercount-15) . "px";
                    else
                        $height = 329 . "px";
                }
            }
        }else{
            if($userbank->HasAccess(ADMIN_OWNER))
                $objResponse->addAssign("host_$sid", "innerHTML", "<b>Ошибка соединения</b> (<i>" . $res[1] . ":" . $res[2]. "</i>) <small><a href=\"" . $GLOBALS['urls']['FAQ'] . "\" title=\"Какие порты должны быть открыты в ВЕБ панели SourceBans?\">Помощь</a></small>");
            else
                $objResponse->addAssign("host_$sid", "innerHTML", "<b>Ошибка соединения</b> (<i>" . $res[1] . ":" . $res[2]. "</i>)");
            $objResponse->addAssign("players_$sid", "innerHTML", "Н/Д");
            $objResponse->addAssign("os_$sid", "innerHTML", "Н/Д");
            $objResponse->addAssign("vac_$sid", "innerHTML", "Н/Д");
            $objResponse->addAssign("map_$sid", "innerHTML", "Н/Д");
            if(!$inHome) {
                $connect = "onclick = \"document.location = 'steam://connect/" .  $res['ip'] . ":" . $res['port'] . "'\"";
                $objResponse->addScript("$('sinfo_$sid').setStyle('display', 'none');");
                $objResponse->addScript("$('noplayer_$sid').setStyle('display', 'block');");
                $objResponse->addScript("$('serverwindow_$sid').setStyle('height', '64px');");
                $objResponse->addScript("if($('sid_$sid'))$('sid_$sid').setStyle('color', '#adadad');");
            }
        }
        if($tplsid != "" && $open != "" && $tplsid==$open)
            $objResponse->addScript("InitAccordion('tr.opener', 'div.opener', 'content', '".$open."');");
        $objResponse->addScript("$('dialog-placement').setStyle('display', 'none');");
    } elseif($type=="id") {
        if($info) {
            $objResponse->addAssign("$obId", "innerHTML", trunc($info['HostName'], $trunchostname, false));
        }else{
            $objResponse->addAssign("$obId", "innerHTML", "<b>!!!</b> <i>Ошибка соединения</i> (<i>" . $res[1] . ":" . $res[2]. "</i>) <b>!!!</b>");
        }
    } else {
        if($info)
        {
            $objResponse->addAssign("ban_server_$type", "innerHTML", trunc($info['HostName'], $trunchostname, false));
        }else{
            $objResponse->addAssign("ban_server_$type", "innerHTML", "<b>!!!</b> <i>Ошибка соединения</i> (<i>" . $res[1] . ":" . $res[2]. "</i>) <b>!!!</b>");
        }
    }

    return $objResponse;
}
