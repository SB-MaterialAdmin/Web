<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function AddBan($nickname, $type, $steam, $ip, $length, $dfile, $dname, $reason, $fromsub, $udemo=false)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался добавить бан, не имея на то прав.");
        return $objResponse;
    }
    
    $steam = trim($steam);
    
    $error = 0;
    // If they didnt type a steamid
    if(empty($steam) && $type == 0)
    {
        $error++;
        $objResponse->addAssign("steam.msg", "innerHTML", "Введите Steam ID или Community ID");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
    }
    else if(($type == 0 
    && !is_numeric($steam) 
    && !validate_steam($steam))
    || (is_numeric($steam) 
    && (strlen($steam) < 15
    || !validate_steam($steam = FriendIDToSteamID($steam)))))
    {
        $error++;
        $objResponse->addAssign("steam.msg", "innerHTML", "Введите действительный Steam ID или Community ID");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
    }
    else if (empty($ip) && $type == 1)
    {
        $error++;
        $objResponse->addAssign("ip.msg", "innerHTML", "Введите IP");
        $objResponse->addScript("$('ip.msg').setStyle('display', 'block');");
    }
    else if($type == 1 && !validate_ip($ip))
    {
        $error++;
        $objResponse->addAssign("ip.msg", "innerHTML", "Введите действительный IP");
        $objResponse->addScript("$('ip.msg').setStyle('display', 'block');");
    }
    else
    {
        $objResponse->addAssign("steam.msg", "innerHTML", "");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'none');");
        $objResponse->addAssign("ip.msg", "innerHTML", "");
        $objResponse->addScript("$('ip.msg').setStyle('display', 'none');");
    }
    if ($udemo && ! checkdnsrr($udemo,'A') && ! @get_headers($udemo, 1)){
        $error++;
        $objResponse->addAssign("demo_link.msg", "innerHTML", "Введите действительный URL к демо файлу, либо оставьте поле пустым!");
        $objResponse->addScript("$('demo_link.msg').setStyle('display', 'block');");
    }
    
    if($error > 0)
        return $objResponse;

    $nickname = RemoveCode($nickname);
    $ip = preg_replace('#[^\d\.]#', '', $ip);//strip ip of all but numbers and dots
    $dname = RemoveCode($dname);
    $reason = RemoveCode($reason);
    if(!$length)
        $len = 0;
    else
        $len = $length*60;

    // prune any old bans
    PruneBans();
    if((int)$type==0) {
        // Check if the new steamid is already banned
        $chk = $GLOBALS['db']->GetRow("SELECT count(bid) AS count FROM ".DB_PREFIX."_bans WHERE authid = ? AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND type = '0'", array($steam));

        if(intval($chk[0]) > 0)
        {
            $objResponse->addScript("ShowBox('Ошибка', 'SteamID: $steam уже забанен.', 'red', '', true);");
            return $objResponse;
        }
        
        // Check if player is immune
        $admchk = $userbank->GetAllAdmins();
        foreach($admchk as $admin)
            if($admin['authid'] == $steam && $userbank->GetProperty('srv_immunity') < $admin['srv_immunity'])
            {
                $objResponse->addScript("ShowBox('Ошибка', 'SteamID: админ ".$admin['user']." ($steam) под иммунитетом.', 'red', '');");
                return $objResponse;
            }
    }
    if((int)$type==1) {
        $chk = $GLOBALS['db']->GetRow("SELECT count(bid) AS count FROM ".DB_PREFIX."_bans WHERE ip = ? AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND type = '1'", array($ip));

        if(intval($chk[0]) > 0)
        {
            $objResponse->addScript("ShowBox('Ошибка', 'Этот IP ($ip) уже забанен.', 'red', '', true);");
            return $objResponse;
        }
    }

    $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_bans(created,type,ip,authid,name,ends,length,reason,aid,adminIp ) VALUES
                                    (UNIX_TIMESTAMP(),?,?,?,?,(UNIX_TIMESTAMP() + ?),?,?,?,?)");
    $GLOBALS['db']->Execute($pre,array($type,
                                       $ip,
                                       $steam,
                                       $nickname,
                                       $length*60,
                                       $len,
                                       $reason,
                                       $userbank->GetAid(),
                                       $_SERVER['REMOTE_ADDR']));
    $subid = $GLOBALS['db']->Insert_ID();

    if($dname && $dfile && preg_match('/^[a-z0-9]*$/i', $dfile))
    //Thanks jsifuentes: http://jacobsifuentes.com/sourcebans-1-4-lfi-exploit/
    //Official Fix: https://code.google.com/p/sourcebans/source/detail?r=165
    {
        $GLOBALS['db']->Execute("INSERT INTO ".DB_PREFIX."_demos(demid,demtype,filename,origname)
                             VALUES(?,'B', ?, ?)", array((int)$subid, $dfile, $dname));
    }elseif(!$dname && !$dfile && $udemo){
        $GLOBALS['db']->Execute("INSERT INTO ".DB_PREFIX."_demos(demid,demtype,filename,origname)
                             VALUES(?,'U', '', ?)", array((int)$subid, $udemo));
    }
    if($fromsub) {
        $submail = $GLOBALS['db']->Execute("SELECT name, email FROM ".DB_PREFIX."_submissions WHERE subid = '" . (int)$fromsub . "'");
        // Send an email when ban is accepted
        $requri = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], ".php")+4);
        $headers = 'From: submission@' . $_SERVER['HTTP_HOST'] . "\n" .
        'X-Mailer: PHP/' . phpversion();

        $message = "Привет,\n";
        $message .= "Ваша заявка на бан подтверждена админом.\nПерейдите по ссылке, чтобы посмотреть банлист.\n\nhttp://" . $_SERVER['HTTP_HOST'] . $requri . "?p=banlist";

        EMail($submail->fields['email'], "[SourceBans] Бан добавлен", $message, $headers);
        $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_submissions` SET archiv = '2', archivedby = '".$userbank->GetAid()."' WHERE subid = '" . (int)$fromsub . "'");
    }

    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_submissions` SET archiv = '3', archivedby = '".$userbank->GetAid()."' WHERE SteamId = ?;", array($steam));

    $kickit = isset($GLOBALS['config']['config.enablekickit']) && $GLOBALS['config']['config.enablekickit'] == "1";
    if ($kickit)
        $objResponse->addScript("ShowKickBox('".((int)$type==0?$steam:$ip)."', '".(int)$type."');");
    else
        $objResponse->addScript("ShowBox('Бан добавлен', 'Бан успешно добавлен', 'green', 'index.php?p=admin&c=bans');");

    $objResponse->addScript("TabToReload();");
    $log = new CSystemLog("m", "Бан добавлен", "Бан против (" . ((int)$type==0?$steam:$ip) . ") был добавлен, причина: $reason, срок: $length", true, $kickit);
    return $objResponse;
}

function SetupBan($subid)
{
    $objResponse = new xajaxResponse();
    $subid = (int)$subid;

    $ban = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_submissions WHERE subid = $subid");
    $demo = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_demos WHERE demid = $subid AND demtype = \"S\"");
    // clear any old stuff
    $objResponse->addScript("$('nickname').value = ''");
    $objResponse->addScript("$('fromsub').value = ''");
    $objResponse->addScript("$('steam').value = ''");
    $objResponse->addScript("$('ip').value = ''");
    $objResponse->addScript("$('txtReason').value = ''");
    $objResponse->addAssign("demo.msg", "innerHTML",  "");
    // add new stuff
    $objResponse->addScript("$('nickname').value = '" . $ban['name'] . "'");
    $objResponse->addScript("$('steam').value = '" . $ban['SteamId']. "'");
    $objResponse->addScript("$('ip').value = '" . $ban['sip'] . "'");
    if(trim($ban['SteamId']) == "")
        $type = "1";
    else
        $type = "0";
    $objResponse->addScriptCall("selectLengthTypeReason", "0", $type, addslashes($ban['reason']));

    $objResponse->addScript("$('fromsub').value = '$subid'");
    if($demo)
    {
        $objResponse->addAssign("demo.msg", "innerHTML",  $demo['origname']);
        $objResponse->addScript("demo('" . $demo['filename'] . "', '" . $demo['origname'] . "');");
    }
    $objResponse->addScript("SwapPane(0);");
    return $objResponse;
}

function PrepareReban($bid)
{
    $objResponse = new xajaxResponse();
    $bid = (int)$bid;

    $ban = $GLOBALS['db']->GetRow("SELECT type, ip, authid, name, length, reason FROM ".DB_PREFIX."_bans WHERE bid = '".$bid."';");
    $demo = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_demos WHERE demid = '".$bid."' AND demtype = \"B\";");
    // clear any old stuff
    $objResponse->addScript("$('nickname').value = ''");
    $objResponse->addScript("$('ip').value = ''");
    $objResponse->addScript("$('fromsub').value = ''");
    $objResponse->addScript("$('steam').value = ''");
    $objResponse->addScript("$('txtReason').value = ''");
    $objResponse->addAssign("demo.msg", "innerHTML",  "");
    $objResponse->addAssign("txtReason", "innerHTML",  "");

    // add new stuff
    $objResponse->addScript("$('nickname').value = '" . $ban['name'] . "'");
    $objResponse->addScript("$('steam').value = '" . $ban['authid']. "'");
    $objResponse->addScript("$('ip').value = '" . $ban['ip']. "'");
    $objResponse->addScriptCall("selectLengthTypeReason", $ban['length'], $ban['type'], addslashes($ban['reason']));

    if($demo)
    {
        $objResponse->addAssign("demo.msg", "innerHTML",  $demo['origname']);
        $objResponse->addScript("demo('" . $demo['filename'] . "', '" . $demo['origname'] . "');");
    }
    $objResponse->addScript("SwapPane(0);");
    return $objResponse;
}

function SendMail($subject, $message, $type, $id)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    
    $id = (int)$id;
    
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_PROTESTS|ADMIN_BAN_SUBMISSIONS))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался отправить e-mail, не имея на это прав.");
        return $objResponse;
    }
    
    // Don't mind wrong types
    if($type != 's' && $type != 'p')
    {
        return $objResponse;
    }
    
    // Submission
    $email = "";
    if($type == 's')
    {
        $email = $GLOBALS['db']->GetOne('SELECT email FROM `'.DB_PREFIX.'_submissions` WHERE subid = ?', array($id));
    }
    // Protest
    else if($type == 'p')
    {
        $email = $GLOBALS['db']->GetOne('SELECT email FROM `'.DB_PREFIX.'_protests` WHERE pid = ?', array($id));
    }
    
    if(empty($email))
    {
        $objResponse->addScript("ShowBox('Ошибка', 'Не выбран e-mail..', 'red', 'index.php?p=admin&c=bans');");
        return $objResponse;
    }
    
    $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\n" . 'X-Mailer: PHP/' . phpversion();
    $m = @EMail($email, '[SourceBans] ' . $subject, $message, $headers);

    
    if($m)
    {
        $objResponse->addScript("ShowBox('E-mail отправлен', 'E-mail успешно отправлен пользователю.', 'green', 'index.php?p=admin&c=bans');");
        $log = new CSystemLog("m", "E-mail отправлен", $username . " отправил e-mail на ".htmlspecialchars($email).".<br />Тема: '[SourceBans] " . htmlspecialchars($subject) . "'<br />Сообщение: '" . nl2br(htmlspecialchars($message)) . "'");
    }
    else
        $objResponse->addScript("ShowBox('Ошибка', 'Не удалось отправить e-mail пользователю.', 'red', '');");
    
    return $objResponse;
}

function GroupBan($groupuri, $isgrpurl="no", $queue="no", $reason="", $last="")
{
    $objResponse = new xajaxResponse();
    if($GLOBALS['config']['config.enablegroupbanning']==0)
        return $objResponse;
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался забанить группу '".htmlspecialchars(addslashes(trim($groupuri)))."', не имея на это прав.");
        return $objResponse;
    }
    if($isgrpurl=="yes")
        $grpname = $groupuri;
    else {
        $url = parse_url($groupuri, PHP_URL_PATH);
        $url = explode("/", $url);
        $grpname = $url[2];
    }
    if(empty($grpname)) {
        $objResponse->addAssign("groupurl.msg", "innerHTML", "Ошибка преобразования URL группы.");
        $objResponse->addScript("$('groupurl.msg').setStyle('display', 'block');");
        return $objResponse;
    }
    else {
        $objResponse->addScript("$('groupurl.msg').setStyle('display', 'none');");
    }

    if($queue=="yes")
        $objResponse->addScript("ShowBox('Ждите...', 'Банятся все участники выбранной группы... <br>Ждите...<br>Внимание: Это может занять 15 минут или дольше, в зависимости от количества участников группы!', 'info', '', false);");
    else
        $objResponse->addScript("ShowBox('Ждите...', 'Банятся все участники группы ".$grpname."...<br>Ждите...<br>Внимание: Это может занять 15 минут или дольше, в зависимости от количества участников группы!', 'info', '', false);");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'none');");
    $objResponse->addScriptCall("xajax_BanMemberOfGroup", $grpname, $queue, htmlspecialchars(addslashes($reason)), $last);
    return $objResponse;

}

function BanMemberOfGroup($grpurl, $queue, $reason, $last)
{
    set_time_limit(0);
    $objResponse = new xajaxResponse();
    if($GLOBALS['config']['config.enablegroupbanning']==0)
        return $objResponse;
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался забанить группу '".$grpurl."', не имея на это прав.");
        return $objResponse;
    }
    $bans = $GLOBALS['db']->GetAll("SELECT CAST(MID(authid, 9, 1) AS UNSIGNED) + CAST('76561197960265728' AS UNSIGNED) + CAST(MID(authid, 11, 10) * 2 AS UNSIGNED) AS community_id FROM ".DB_PREFIX."_bans WHERE RemoveType IS NULL;");
    foreach($bans as $ban) {
        $already[] = $ban["community_id"];
    }
    $doc = new DOMDocument();
    // This could be changed to use the memberlistxml
    // https://partner.steamgames.com/documentation/community_data
    // http://steamcommunity.com/groups/<GroupName>/memberslistxml/?xml=1
    // but we'd need to open every single profile of every member to get the name..
    $raw = file_get_contents("http://steamcommunity.com/groups/".$grpurl."/members"); // get the members page
    @$doc->loadHTML($raw); // load it into a handy object so we can maintain it
    // the memberlist is paginated, so we need to check the number of pages
    $pagetag = $doc->getElementsByTagName('div');
    foreach($pagetag as $pageclass) {
        if($pageclass->getAttribute('class') == "pageLinks") { //search for the pageLinks div
            $pageclasselmt = $pageclass;
            break;
        }
    }
    $pagelinks = $pageclasselmt->getElementsByTagName('a'); // get all page links
    $pagenumbers = array();
    $pagenumbers[] = 1; // add at least one page for the loop. if the group doesn't have 50 members -> no paginating
    foreach($pagelinks as $pagelink) {
        $pagenumber = str_replace("?p=", "", $pagelink->childNodes->item(0)->nodeValue); // remove the get variable stuff so we only have the pagenumber
        if(strpos($pagenumber, ">") === false) // don't want the "next" button ;)
            $pagenumbers[] = $pagenumber;
    }
    $members = array();
    $total = 0;
    $bannedbefore = 0;
    $error = 0;
    for($i=1;$i<=max($pagenumbers);$i++) { // loop through all the pages
        if($i!=1) { // if we are on page 1 we don't need to reget the content as we did above already.
            $raw = file_get_contents("http://steamcommunity.com/groups/".$grpurl."/members?p=".$i); // open the memberpage
            @$doc->loadHTML($raw);
        }
        $tags = $doc->getElementsByTagName('a');
        foreach ($tags as $tag) {
            // search for the member profile links
            if((strstr($tag->getAttribute('href'), "http://steamcommunity.com/id/") || strstr($tag->getAttribute('href'), "http://steamcommunity.com/profiles/")) && $tag->hasChildNodes() && $tag->childNodes->length == 1 && $tag->childNodes->item(0)->nodeValue != "") {
                $total++;
                $url = parse_url($tag->getAttribute('href'), PHP_URL_PATH);
                $url = explode("/", $url);
                if(in_array($url[2], $already)) {
                    $bannedbefore++;
                    continue;
                }
                if(strstr($tag->getAttribute('href'), "http://steamcommunity.com/id/")) {
                    // we don't have the friendid as this player is using a custom id :S need to get the friendid
                    if($tfriend = GetFriendIDFromCommunityID($url[2])) {
                        if(in_array($tfriend, $already)) {
                            $bannedbefore++;
                            continue;
                        }
                        $cust = $url[2];
                        $steamid = FriendIDToSteamID($tfriend);
                        $urltag = $tfriend;
                    } else {
                        $error++;
                        continue;
                    }
                } else {
                    // just a normal friendid profile =)
                    $cust = NULL;
                    $steamid = FriendIDToSteamID($url[2]);
                    $urltag = $url[2];
                }
                $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_bans(created,type,ip,authid,name,ends,length,reason,aid,adminIp ) VALUES
                                    (UNIX_TIMESTAMP(),?,?,?,?,UNIX_TIMESTAMP(),?,?,?,?)");
                $GLOBALS['db']->Execute($pre,array(0,
                                                   "",
                                                   $steamid,
                                                   utf8_decode($tag->childNodes->item(0)->nodeValue),
                                                   0,
                                                   "Steam Community Group Ban (".$grpurl.") ".$reason,
                                                   $userbank->GetAid(),
                                                   $_SERVER['REMOTE_ADDR']));
            }
        }
    }
    if($queue=="yes") {
        $objResponse->addScript("$('steamGroupStatus').setStyle('display', 'block');");
        $objResponse->addAppend("steamGroupStatus", "innerHTML", "<p>Забанено ".($total-$bannedbefore-$error)." из ".$total." участников группы '".$grpurl."'. <br/> ".$bannedbefore." были забанены ранее. <br /> ".$error." ошибок.</p>");
        if($grpurl==$last) {
            $objResponse->addScript("ShowBox('Группы успешно забанены', 'Выбранные группы были успешно забанены. Детали банов выведены в зеленое окно.', 'green', '', true);");
            $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
        }
    } else {
        $objResponse->addScript("ShowBox('Группа забанена', 'Забанено ".($total-$bannedbefore-$error)." из ".$total." участников группы \'".$grpurl."\'.<br>".$bannedbefore." были забанены ранее.<br>".$error." ошибок.', 'green', '', true);");
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    }
    $log = new CSystemLog("m", "Группа забанена", "Забанено ".($total-$bannedbefore-$error)." из ".$total." участников группы \'".$grpurl."\'.<br>".$bannedbefore." были забанены ранее.<br>".$error." ошибок.");
    return $objResponse;
}

function GetGroups($friendid)
{
    set_time_limit(0);
    $objResponse = new xajaxResponse();
    if($GLOBALS['config']['config.enablegroupbanning']==0 || !is_numeric($friendid))
        return $objResponse;
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался получить список групп '".$friendid."', не имея на это прав.");
        return $objResponse;
    }
    // check if we're getting redirected, if so there is $result["Location"] (the player uses custom id)  else just use the friendid. !We can't get the xml with the friendid url if the player has a custom one!
    $result = get_headers("http://steamcommunity.com/profiles/".$friendid."/", 1);
    $raw = file_get_contents((!empty($result["Location"])?$result["Location"]:"http://steamcommunity.com/profiles/".$friendid."/")."?xml=1");
    preg_match("/<privacyState>([^\]]*)<\/privacyState>/", $raw, $status);
    if(($status && $status[1] != "public") || strstr($raw, "<groups>")) {
        $raw = str_replace("&", "", $raw);
        $raw = strip_31_ascii($raw);
        $raw = utf8_encode($raw);
        $xml = simplexml_load_string($raw); // parse xml
        $result = $xml->xpath('/profile/groups/group'); // go to the group nodes
        $i = 0;
        while(list( , $node) = each($result)) {
            // Steam only provides the details of the first 3 groups of a players profile. We need to fetch the individual groups seperately to get the correct information.
            if(empty($node->groupName)) {
                $memberlistxml = file_get_contents("http://steamcommunity.com/gid/".$node->groupID64."/memberslistxml/?xml=1");
                $memberlistxml = str_replace("&", "", $memberlistxml);
                $memberlistxml = strip_31_ascii($memberlistxml);
                $memberlistxml = utf8_encode($memberlistxml);
                $groupxml = simplexml_load_string($memberlistxml); // parse xml
                $node = $groupxml->xpath('/memberList/groupDetails');
                $node = $node[0];
            }
            
            // Checkbox & Groupname table cols
            $objResponse->addScript('var e = document.getElementById("steamGroupsTable");
                                                    var tr = e.insertRow("-1");
                                                        var td = tr.insertCell("-1");
                                                            td.className = "listtable_1";
                                                            td.style.padding = "0px";
                                                            td.style.width = "3px";
                                                                var input = document.createElement("input");
                                                                input.setAttribute("type","checkbox");
                                                                input.setAttribute("id","chkb_'.$i.'");
                                                                input.setAttribute("value","'.$node->groupURL.'");
                                                            td.appendChild(input);
                                                        var td = tr.insertCell("-1");
                                                            td.className = "listtable_1";
                                                            var a = document.createElement("a");
                                                                a.href = "http://steamcommunity.com/groups/'.$node->groupURL.'";
                                                                a.setAttribute("target","_blank");
                                                                    var txt = document.createTextNode("'.utf8_decode($node->groupName).'");
                                                                a.appendChild(txt);
                                                            td.appendChild(a);
                                                                var txt = document.createTextNode(" (");
                                                            td.appendChild(txt);
                                                                var span = document.createElement("span");
                                                                span.setAttribute("id","membcnt_'.$i.'");
                                                                span.setAttribute("value","'.$node->memberCount.'");
                                                                    var txt3 = document.createTextNode("'.$node->memberCount.'");
                                                                span.appendChild(txt3);
                                                            td.appendChild(span);
                                                                var txt2 = document.createTextNode(" Участника)");
                                                            td.appendChild(txt2);
                                                        ');
            $i++;
        }
    } else {
        $objResponse->addScript("ShowBox('Ошибка', 'Ошибка получения информации о группе. <br>Возможно это участник другой группы, или его профиль скрыт?<br><a href=\"http://steamcommunity.com/profiles/".$friendid."/\" title=\"Профиль участника\" target=\"_blank\">Профиль участника</a>', 'red', 'index.php?p=banlist', true);");
        $objResponse->addScript("$('steamGroupsText').innerHTML = '<i>Нет групп...</i>';");
        return $objResponse;
    }
    $objResponse->addScript("$('steamGroupsText').setStyle('display', 'none');");
    $objResponse->addScript("$('steamGroups').setStyle('display', 'block');");
    return $objResponse;
}

function BanFriends($friendid, $name)
{
    set_time_limit(0);
    $objResponse = new xajaxResponse();
    if($GLOBALS['config']['config.enablefriendsbanning']==0 || !is_numeric($friendid))
        return $objResponse;
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался забанить друга '".RemoveCode($friendid)."', не имея на это прав.");
        return $objResponse;
    }
    $bans = $GLOBALS['db']->GetAll("SELECT CAST(MID(authid, 9, 1) AS UNSIGNED) + CAST('76561197960265728' AS UNSIGNED) + CAST(MID(authid, 11, 10) * 2 AS UNSIGNED) AS community_id FROM ".DB_PREFIX."_bans WHERE RemoveType IS NULL;");
    foreach($bans as $ban) {
        $already[] = $ban["community_id"];
    }
    $doc = new DOMDocument();
    $result = get_headers("http://steamcommunity.com/profiles/".$friendid."/", 1);
    $raw = file_get_contents(($result["Location"]!=""?$result["Location"]:"http://steamcommunity.com/profiles/".$friendid."/")."friends"); // get the friends page
    @$doc->loadHTML($raw);
    $divs = $doc->getElementsByTagName('div');
    foreach($divs as $div) {
        if($div->getAttribute('id') == "memberList") {
            $memberdiv = $div;
            break;
        }
    }

    $total = 0;
    $bannedbefore = 0;
    $error = 0;
    $links = $memberdiv->getElementsByTagName('a');
    foreach ($links as $link) {
        if(strstr($link->getAttribute('href'), "http://steamcommunity.com/id/") || strstr($link->getAttribute('href'), "http://steamcommunity.com/profiles/"))
        {
            $total++;
            $url = parse_url($link->getAttribute('href'), PHP_URL_PATH);
            $url = explode("/", $url);
            if(in_array($url[2], $already)) {
                $bannedbefore++;
                continue;
            }
            if(strstr($link->getAttribute('href'), "http://steamcommunity.com/id/")) {
                // we don't have the friendid as this player is using a custom id :S need to get the friendid
                if($tfriend = GetFriendIDFromCommunityID($url[2])) {
                    if(in_array($tfriend, $already)) {
                        $bannedbefore++;
                        continue;
                    }
                    $cust = $url[2];
                    $steamid = FriendIDToSteamID($tfriend);
                    $urltag = $tfriend;
                } else {
                    $error++;
                    continue;
                }
            } else {
                // just a normal friendid profile =)
                $cust = NULL;
                $steamid = FriendIDToSteamID($url[2]);
                $urltag = $url[2];
            }
            
            // get the name
            $friendName = $link->parentNode->childNodes->item(5)->childNodes->item(0)->nodeValue;
            $friendName = str_replace("&#13;", "", $friendName);
            $friendName = trim($friendName);
            
            $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_bans(created,type,ip,authid,name,ends,length,reason,aid,adminIp ) VALUES
                                    (UNIX_TIMESTAMP(),?,?,?,?,UNIX_TIMESTAMP(),?,?,?,?)");
            $GLOBALS['db']->Execute($pre,array(0,
                                               "",
                                               $steamid,
                                               utf8_decode($friendName),
                                               0,
                                               "Steam Community Friend Ban (".htmlspecialchars($name).")",
                                               $userbank->GetAid(),
                                               $_SERVER['REMOTE_ADDR']));
        }
    }
    if($total==0) {
        $objResponse->addScript("ShowBox('Ошибка выборки друзей', 'Ошибка выборки друзей из профиля STEAM. Возможно его профиль скрыт, или у него нет друзей!', 'red', 'index.php?p=banlist', true);");
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
        return $objResponse;
    }
    $objResponse->addScript("ShowBox('Дрзья были забанены', 'Забанено ".($total-$bannedbefore-$error)." из ".$total." друзей у \'".htmlspecialchars($name)."\'.<br>".$bannedbefore." были забанены до этого.<br>".$error." ошибок.', 'green', 'index.php?p=banlist', true);");
    $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    $log = new CSystemLog("m", "Друзья забанены", "Забанено ".($total-$bannedbefore-$error)." из ".$total." друзей у \'".htmlspecialchars($name)."\'.<br>".$bannedbefore." были забанены до этого.<br>".$error." ошибок.");
    return $objResponse;
}

function ViewCommunityProfile($sid, $name)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->is_admin())
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался посмотреть профиль '".htmlspecialchars($name)."', не имея на это прав.");
        return $objResponse;
    }
    $sid = (int)$sid;
  
    require INCLUDES_PATH.'/CServerControl.php';
    //get the server data
    $data = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = '".$sid."';");
    if(empty($data['rcon'])) {
        $objResponse->addScript("ShowBox('Ошибка', 'Невозможно получить информацию о игроке ".addslashes(htmlspecialchars($name)).". Не задан РКОН пароль!', 'red', '', true);");
        return $objResponse;
    }
    
    $r = new CServerControl();
    $r->Connect($data['ip'], $data['port']);

    if(!$r->AuthRcon($data['rcon']))
    {
        $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$sid."';");
        $objResponse->addScript("ShowBox('Ошибка', 'Невозможно получить информацию о игроке ".addslashes(htmlspecialchars($name)).". Неверный РКОН пароль!', 'red', '', true);");
        return $objResponse;
    }
    // search for the playername
    $ret = $r->SendCommand("status");
    $search = preg_match_all(STATUS_PARSE,$ret,$matches,PREG_PATTERN_ORDER);
    $i = 0;
    $found = false;
    $index = -1;
    foreach($matches[2] AS $match) {
        if($match == $name) {
            $found = true;
            $index = $i;
            break;
        }
        $i++;
    }
    if($found) {
        $steam = $matches[3][$index];
        // Hack to support steam3 [U:1:X] representation.
        if(strpos($steam, "[U:") === 0) {
            $steam = renderSteam2(getAccountId($steam), 0);
        }
        $objResponse->addScript("ShowBox('Profile', 'Ссылка на игрока \"".addslashes(htmlspecialchars($name))."\", была успешно создана: <a href=\"http://www.steamcommunity.com/profiles/".SteamIDToFriendID($steam)."/\" title=\"".addslashes(htmlspecialchars($name))."\'s Profile\" target=\"_blank\">Открыть</a>', 'green', '', true);");
        $objResponse->addScript("window.open('http://www.steamcommunity.com/profiles/".SteamIDToFriendID($steam)."/', 'Community_".$steam."');");
    } else {
        $objResponse->addScript("ShowBox('Ошибка', 'Невозможно получить информацию о игроке ".addslashes(htmlspecialchars($name)).". Игрок ушёл с сервера!', 'red', '', true);");
    }
    return $objResponse;
}

function SendMessage($sid, $name, $message)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->is_admin())
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался отправить сообщение '".addslashes(htmlspecialchars($name))."' (\"".RemoveCode($message)."\"), не имея на это прав.");
        return $objResponse;
    }
    $sid = (int)$sid;
    require INCLUDES_PATH.'/CServerControl.php';
    //get the server data
    $data = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = '".$sid."';");
    if(empty($data['rcon'])) {
        $objResponse->addScript("ShowBox('Ошибка', 'Невозможно отправить сообщение для ".addslashes(htmlspecialchars($name)).". Не задан РКОН пароль!', 'red', '', true);");
        return $objResponse;
    }
    
    $r = new CServerControl();
    $r->Connect($data['ip'], $data['port']);
    
    if(!$r->AuthRcon($data['rcon']))
    {
        $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$sid."';");
        $objResponse->addScript("ShowBox('Ошибка', 'Невозможно отправить сообщение для ".addslashes(htmlspecialchars($name)).". Неверноый РКОН пароль!', 'red', '', true);");
        return $objResponse;
    }
    $ret = $r->SendCommand('sm_psay "'.$name.'" "'.addslashes($message).'"');
    new CSystemLog("m", "Сообщение отправлено", "Данное сообщение было отправлено " . addslashes(htmlspecialchars($name)) . " on server " . $data['ip'] . ":" . $data['port'] . ": " . RemoveCode($message));
    $objResponse->addScript("ShowBox('Сообщение отправлено', 'Сообщение для игрока \'".addslashes(htmlspecialchars($name))."\' успешно отправлено!', 'green', '', true);$('dialog-control').setStyle('display', 'none');");
    return $objResponse;
}

function AddBlock($nickname, $type, $steam, $length, $reason)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался добавить блокировку, не имея на это прав.");
        return $objResponse;
    }
    
    $steam = trim($steam);
    
    $error = 0;
    // If they didnt type a steamid
    if(empty($steam))
    {
        $error++;
        $objResponse->addAssign("steam.msg", "innerHTML", "Введите Steam ID или Community ID");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
    }
    else if((!is_numeric($steam) 
    && !validate_steam($steam))
    || (is_numeric($steam) 
    && (strlen($steam) < 15
    || !validate_steam($steam = FriendIDToSteamID($steam)))))
    {
        $error++;
        $objResponse->addAssign("steam.msg", "innerHTML", "Введите действительный Steam ID или Community ID");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
    }
    else
    {
        $objResponse->addAssign("steam.msg", "innerHTML", "");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'none');");
    }
    
    if($error > 0)
        return $objResponse;

    $nickname = RemoveCode($nickname);
    $reason = RemoveCode($reason);
    if(!$length)
        $len = 0;
    else
        $len = $length*60;

    // prune any old bans
    PruneComms();

    $typeW = "";
    switch ((int)$type)
    {
        case 1:
            $typeW = "type = 1";
            break;
        case 2:
            $typeW = "type = 2";
            break;
        case 3:
            $typeW = "(type = 1 OR type = 2)";
            break;
        default:
            $typeW = "";
            break;
    }

    // Check if the new steamid is already banned
    $chk = $GLOBALS['db']->GetRow("SELECT count(bid) AS count FROM ".DB_PREFIX."_comms WHERE authid = ? AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND ".$typeW, array($steam));
    
    if(intval($chk[0]) > 0)
    {
        $objResponse->addScript("ShowBox('Ошибка', 'SteamID: $steam уже заблокирован.', 'red', '');");
        return $objResponse;
    }

    // Check if player is immune
    $admchk = $userbank->GetAllAdmins();
    foreach($admchk as $admin)
    if($admin['authid'] == $steam && $userbank->GetProperty('srv_immunity') < $admin['srv_immunity'])
        {
            $objResponse->addScript("ShowBox('Ошибка', 'SteamID: Админ ".$admin['user']." ($steam) имеет иммунитет.', 'red', '');");
            return $objResponse;
        }

    if((int)$type == 1 || (int)$type == 3)
    {
        $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_comms(created,type,authid,name,ends,length,reason,aid,adminIp ) VALUES
                                      (UNIX_TIMESTAMP(),1,?,?,(UNIX_TIMESTAMP() + ?),?,?,?,?)");
        $GLOBALS['db']->Execute($pre,array($steam,
                                           $nickname,
                                           $length*60,
                                           $len,
                                           $reason,
                                           $userbank->GetAid(),
                                           $_SERVER['REMOTE_ADDR']));
    }
    if ((int)$type == 2 || (int)$type ==3)
    {
        $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_comms(created,type,authid,name,ends,length,reason,aid,adminIp ) VALUES
                                      (UNIX_TIMESTAMP(),2,?,?,(UNIX_TIMESTAMP() + ?),?,?,?,?)");
        $GLOBALS['db']->Execute($pre,array($steam,
                                           $nickname,
                                           $length*60,
                                           $len,
                                           $reason,
                                           $userbank->GetAid(),
                                           $_SERVER['REMOTE_ADDR']));
    }

    $objResponse->addScript("ShowBlockBox('".$steam."', '".(int)$type."', '".(int)$len."');");
    $objResponse->addScript("TabToReload();");
    $log = new CSystemLog("m", "Блок добавлен", "Блок (" . $steam . ") был добавлен, причина: $reason, срок: $length", true, $kickit);
    return $objResponse;
}

function PrepareReblock($bid)
{
    $objResponse = new xajaxResponse();

    $ban = $GLOBALS['db']->GetRow("SELECT name, authid, type, length, reason FROM ".DB_PREFIX."_comms WHERE bid = '".$bid."';");

    // clear any old stuff
    $objResponse->addScript("$('nickname').value = ''");
    $objResponse->addScript("$('steam').value = ''");
    $objResponse->addScript("$('txtReason').value = ''");
    $objResponse->addAssign("txtReason", "innerHTML",  "");

    // add new stuff
    $objResponse->addScript("$('nickname').value = '" . $ban['name'] . "'");
    $objResponse->addScript("$('steam').value = '" . $ban['authid']. "'");
    $objResponse->addScriptCall("selectLengthTypeReason", $ban['length'], $ban['type']-1, addslashes($ban['reason']));

    $objResponse->addScript("SwapPane(0);");
    return $objResponse;
}

function PrepareBlockFromBan($bid)
{
    $objResponse = new xajaxResponse();

    // clear any old stuff
    $objResponse->addScript("$('nickname').value = ''");
    $objResponse->addScript("$('steam').value = ''");
    $objResponse->addScript("$('txtReason').value = ''");    
    $objResponse->addAssign("txtReason", "innerHTML",  "");

    $ban = $GLOBALS['db']->GetRow("SELECT name, authid FROM ".DB_PREFIX."_bans WHERE bid = '".$bid."';");

    // add new stuff
    $objResponse->addScript("$('nickname').value = '" . $ban['name'] . "'");
    $objResponse->addScript("$('steam').value = '" . $ban['authid']. "'");
    
    $objResponse->addScript("SwapPane(0);");
    return $objResponse;
}

function PastePlayerData($sid, $name) {
    global $userbank, $username;
    $objResponse = new xajaxResponse();

    if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался получить данные об игроке для добавления бана/блока , не имея на это прав.");
        return $objResponse;
    }
    
    sleep(1); // костыль против быстрого "пролёта" окошка о том, что игрок не найден
    
    $sid = (int) $sid;
    $data = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = ?;", array($sid));
    if (empty($data['rcon'])) {
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
        $objResponse->addScript("ShowBox('Ошибка', 'Нет РКОН пароля сервера <b>".$data['ip'].":".$data['port']."</b>! Получение данных об игроке невозможно!', 'red', '', true);");
        return $objResponse;
    }
    
    require(INCLUDES_PATH . '/CServerControl.php');
    $CSInstance = new CServerControl();
    $CSInstance->Connect($data['ip'], $data['port']);
    if (!$CSInstance->AuthRcon($data['rcon'])) {
        $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = ?;", array($sid));
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
        $objResponse->addScript("ShowBox('Ошибка', 'Неверный РКОН пароль сервера ".$data['ip'].":".$data['port']."!', 'red', '', true);");
        return $objResponse;
    }
    
    $client = getClientByName($CSInstance, $name);
    if (!$client) {
        $objResponse->addScript("ShowBox('Ошибка', 'Нельзя получить информацию о игроке ".addslashes(htmlspecialchars($name)).". Игрок ушел с сервера! (".$data['ip'].":".$data['port'].") ', 'red', '', true);");
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
        return $objResponse;
    }
    
    // nickname, steam, ip
    $objResponse->addAssign("nickname", "value", $client['name']);
    $objResponse->addAssign("steam",    "value", $client['steam']);
    $objResponse->addAssign("ip",       "value", $client['ip']);
    $objResponse->addScript("swal.close();");
    
    return $objResponse;
}

function AddComment($bid, $ctype, $ctext, $page)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->is_admin())
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался добавить комментарий, не имея на это прав.");
        return $objResponse;
    }
    
    $bid = (int)$bid;
    $page = (int)$page;
    
    $pagelink = "";
    if($page != -1)
        $pagelink = "&page=".$page;
        
    if($ctype=="B")
        $redir = "?p=banlist".$pagelink;
    elseif($ctype=="C")
        $redir = "?p=commslist".$pagelink;
    elseif($ctype=="S")
        $redir = "?p=admin&c=bans#^2";
    elseif($ctype=="P")
        $redir = "?p=admin&c=bans#^1";
    else
    {
        $objResponse->addScript("ShowBox('Ошибка', 'Плохой тип комментария.', 'red');");
        return $objResponse;
    }

    $ctext = trim($ctext);

    $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_comments(bid,type,aid,commenttxt,added) VALUES (?,?,?,?,UNIX_TIMESTAMP())");
    $GLOBALS['db']->Execute($pre,array($bid,
                                       $ctype,
                                       $userbank->GetAid(),
                                       $ctext));

    $objResponse->addScript("ShowBox('Комментарий добавлен', 'Комментарий успешно опубликован', 'green', 'index.php$redir');");
    $objResponse->addScript("TabToReload();");
    $log = new CSystemLog("m", "Комментарий добавлен", $username." добавил комментарий к бану №".$bid);
    return $objResponse;
}

function EditComment($cid, $ctype, $ctext, $page)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->is_admin())
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался редактировать комментарий, не имея на это прав.");
        return $objResponse;
    }

    $cid = (int)$cid;
    $page = (int)$page;
    
    $pagelink = "";
    if($page != -1)
        $pagelink = "&page=".$page;
    
    if($ctype=="B")
        $redir = "?p=banlist".$pagelink;
    elseif($ctype=="C")
        $redir = "?p=commslist".$pagelink;
    elseif($ctype=="S")
        $redir = "?p=admin&c=bans#^2";
    elseif($ctype=="P")
        $redir = "?p=admin&c=bans#^1";
    else
    {
        $objResponse->addScript("ShowBox('Ошибка', 'Плохой тип комментария.', 'red');");
        return $objResponse;
    }

    $ctext = trim($ctext);

    $pre = $GLOBALS['db']->Prepare("UPDATE ".DB_PREFIX."_comments SET `commenttxt` = ?, `editaid` = ?, `edittime`= UNIX_TIMESTAMP() WHERE cid = ?");
    $GLOBALS['db']->Execute($pre,array($ctext,
                                       $userbank->GetAid(),
                                       $cid));

    $objResponse->addScript("ShowBox('Комментарий отредактирован', 'Комментарий №".$cid." успешно отредактирован', 'green', 'index.php$redir');");
    $objResponse->addScript("TabToReload();");
    $log = new CSystemLog("m", "Комментарий отредактирован", $username." отредактировал комментарий №".$cid);
    return $objResponse;
}

function RemoveComment($cid, $ctype, $page)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if (!$userbank->HasAccess(ADMIN_OWNER))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался удалить комментарий, не имея на это прав.");
        return $objResponse;
    }

    $cid = (int)$cid;
    $page = (int)$page;
    
    $pagelink = "";
    if($page != -1)
        $pagelink = "&page=".$page;

    $res = $GLOBALS['db']->Execute("DELETE FROM `".DB_PREFIX."_comments` WHERE `cid` = ?",
                                array( $cid ));
    if($ctype=="B")
        $redir = "?p=banlist".$pagelink;
    elseif($ctype=="C")
        $redir = "?p=commslist".$pagelink;
    else
        $redir = "?p=admin&c=bans";
    if($res)
    {
        $objResponse->addScript("ShowBox('Комментарий удалён', 'Комментарий был успешно удалён из базы данных', 'green', 'index.php$redir', true);");
        $log = new CSystemLog("m", "Комментарий удален", $username." удалил комментарий №".$cid);
    }
    else
        $objResponse->addScript("ShowBox('Ошибка', 'Ошибка удаления комментария из базы данных. Смотрите лог для дополнительной информации', 'red', 'index.php$redir', true);");
    return $objResponse;
}

function RemoveSubmission($sid, $archiv)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_SUBMISSIONS))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался удалить предложение бана, не имея на это прав.");
        return $objResponse;
    }
    $sid = (int)$sid;
    if($archiv == "1") { // move submission to archiv
        $query1 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_submissions` SET archiv = '1', archivedby = '".$userbank->GetAid()."' WHERE subid = $sid");
        $query = $GLOBALS['db']->GetRow("SELECT count(subid) AS cnt FROM `" . DB_PREFIX . "_submissions` WHERE archiv = '0'");
        $objResponse->addScript("$('subcount').setHTML('" . $query['cnt'] . "');");

        $objResponse->addScript("SlideUp('sid_$sid');");
        $objResponse->addScript("SlideUp('sid_" . $sid . "a');");

        if($query1)
        {
            $objResponse->addScript("ShowBox('Заявка отправлена в архив', 'Выбранная заявка была перемещена в архив!', 'green', 'index.php?p=admin&c=bans', true);");
            $log = new CSystemLog("m", "Заявка отправлена в архив", "Заявка (" . $sid . ") была перемещена в архив");
        }
        else
            $objResponse->addScript("ShowBox('Ошибка', 'Не получилось переместить заявку. Смотрите системный лог для дополнительной информации', 'red', 'index.php?p=admin&c=bans', true);");
    } else if($archiv == "0") { // delete submission
        $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_submissions` WHERE subid = $sid");
        $query2 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_demos` WHERE demid = '".$sid."' AND demtype = 'S'");
        $query = $GLOBALS['db']->GetRow("SELECT count(subid) AS cnt FROM `" . DB_PREFIX . "_submissions` WHERE archiv = '1'");
        $objResponse->addScript("$('subcountarchiv').setHTML('" . $query['cnt'] . "');");

        $objResponse->addScript("SlideUp('asid_$sid');");
        $objResponse->addScript("SlideUp('asid_" . $sid . "a');");

        if($query1)
        {
            $objResponse->addScript("ShowBox('Заявка удалена', 'Выбранная заявка была удалена из базы данных', 'green', 'index.php?p=admin&c=bans', true);");
            $log = new CSystemLog("m", "Заявка удалена", "Заявка (" . $sid . ") была удалена");
        }
        else
            $objResponse->addScript("ShowBox('Ошибка', 'Не получилось удалить заявку. Смотрите системный лог для дополнительной информации', 'red', 'index.php?p=admin&c=bans', true);");
    } else if($archiv == "2") { // restore the submission
        $query1 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_submissions` SET archiv = '0', archivedby = NULL WHERE subid = $sid");
        $query = $GLOBALS['db']->GetRow("SELECT count(subid) AS cnt FROM `" . DB_PREFIX . "_submissions` WHERE archiv = '0'");
        $objResponse->addScript("$('subcountarchiv').setHTML('" . $query['cnt'] . "');");

        $objResponse->addScript("SlideUp('asid_$sid');");
        $objResponse->addScript("SlideUp('asid_" . $sid . "a');");

        if($query1)
        {
            $objResponse->addScript("ShowBox('Заявка восстановлена', 'Выбранная заявка была восстановлена из архива!', 'green', 'index.php?p=admin&c=bans', true);");
            $log = new CSystemLog("m", "Заявка восстановлена", "Заявка (" . $sid . ") была восстановлена из архива");
        }
        else
            $objResponse->addScript("ShowBox('Ошибка', 'Не получилось восстановить заявку. Смотрите системный лог для дополнительной информации', 'red', 'index.php?p=admin&c=bans', true);");
    }
    return $objResponse;
}

function RemoveProtest($pid, $archiv)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_PROTESTS))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался удалить протест, не имея на это прав.");
        return $objResponse;
    }
    $pid = (int)$pid;
    if($archiv == '0') { // delete protest
        $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_protests` WHERE pid = $pid");
        $query2 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_comments` WHERE type = 'P' AND bid = $pid;");
        $query = $GLOBALS['db']->GetRow("SELECT count(pid) AS cnt FROM `" . DB_PREFIX . "_protests` WHERE archiv = '1'");
        $objResponse->addScript("$('protcountarchiv').setHTML('" . $query['cnt'] . "');");
        $objResponse->addScript("SlideUp('apid_$pid');");
        $objResponse->addScript("SlideUp('apid_" . $pid . "a');");

        if($query1)
        {
            $objResponse->addScript("ShowBox('Протест удалён', 'Выбранный протест был удалён из базы данных', 'green', 'index.php?p=admin&c=bans', true);");
            $log = new CSystemLog("m", "Протест удалён", "Протест (" . $pid . ") был удалён");
        }
        else
            $objResponse->addScript("ShowBox('Ошибка', 'Не получилось удалить протест. Смотрите системный лог для дополнительной информации', 'red', 'index.php?p=admin&c=bans', true);");
    } else if($archiv == '1') { // move protest to archiv
        $query1 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_protests` SET archiv = '1', archivedby = '".$userbank->GetAid()."' WHERE pid = $pid");
        $query = $GLOBALS['db']->GetRow("SELECT count(pid) AS cnt FROM `" . DB_PREFIX . "_protests` WHERE archiv = '0'");
        $objResponse->addScript("$('protcount').setHTML('" . $query['cnt'] . "');");
        $objResponse->addScript("SlideUp('pid_$pid');");
        $objResponse->addScript("SlideUp('pid_" . $pid . "a');");

        if($query1)
        {
            $objResponse->addScript("ShowBox('Протест отправлен в архив', 'Выбранный протест был отправлен в архив.', 'green', 'index.php?p=admin&c=bans', true);");
            $log = new CSystemLog("m", "Протест в архиве", "Протест (" . $pid . ") был отправлен в архив.");
        }
        else
            $objResponse->addScript("ShowBox('Ошибка', 'Не получилось отправить в архив протест. Смотрите системный лог для дополнительной информации', 'red', 'index.php?p=admin&c=bans', true);");
    } else if($archiv == '2') { // restore protest
        $query1 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_protests` SET archiv = '0', archivedby = NULL WHERE pid = $pid");
        $query = $GLOBALS['db']->GetRow("SELECT count(pid) AS cnt FROM `" . DB_PREFIX . "_protests` WHERE archiv = '1'");
        $objResponse->addScript("$('protcountarchiv').setHTML('" . $query['cnt'] . "');");
        $objResponse->addScript("SlideUp('apid_$pid');");
        $objResponse->addScript("SlideUp('apid_" . $pid . "a');");

        if($query1)
        {
            $objResponse->addScript("ShowBox('Протест восстановлен', 'Выбранный протест был успешно восстановлен из архива.', 'green', 'index.php?p=admin&c=bans', true);");
            $log = new CSystemLog("m", "Протест восстановлен", "Протест (" . $pid . ") был восстановлен из архива.");
        }
        else
            $objResponse->addScript("ShowBox('Ошибка', 'Не получилось восстановить протест из архива. Смотрите системный лог для дополнительной информации', 'red', 'index.php?p=admin&c=bans', true);");
    }
    return $objResponse;
}

function KickPlayer($sid, $name)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    $sid = (int)$sid;
    
    //$objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
        
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался кикнуть ".htmlspecialchars($name).", не имея на это прав.");
        return $objResponse;
    }

    require INCLUDES_PATH.'/CServerControl.php';
    //get the server data
    $data = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = '".$sid."';");
    if(empty($data['rcon'])) {
        $objResponse->addScript("ShowBox('Ошибка', 'Невозможно кикнуть ".addslashes(htmlspecialchars($name)).". Не задан РКОН пароль!', 'red', '', true);");
        return $objResponse;
    }
    
    $r = new CServerControl();
    $r->Connect($data['ip'], $data['port']);

    if(!$r->AuthRcon($data['rcon']))
    {
        $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$sid."';");
        $objResponse->addScript("ShowBox('Ошибка', 'Невозможно кикнуть ".addslashes(htmlspecialchars($name)).". Неверный РКОН пароль!', 'red', '', true);");
        return $objResponse;
    }
    // search for the playername
    $ret = $r->SendCommand("status");
    $search = preg_match_all(STATUS_PARSE,$ret,$matches,PREG_PATTERN_ORDER);
    $i = 0;
    $found = false;
    $index = -1;
    foreach($matches[2] AS $match) {
        if($match == $name) {
            $found = true;
            $index = $i;
            break;
        }
        $i++;
    }
    if($found) {
        $steam = $matches[3][$index];
        $steam2 = $steam;
        // Hack to support steam3 [U:1:X] representation.
        if(strpos($steam, "[U:") === 0) {
            $steam2 = renderSteam2(getAccountId($steam), 0);
        }
        // check for immunity
        $admin = $GLOBALS['db']->GetRow("SELECT a.immunity AS pimmune, g.immunity AS gimmune FROM `".DB_PREFIX."_admins` AS a LEFT JOIN `".DB_PREFIX."_srvgroups` AS g ON g.name = a.srv_group WHERE authid = '".$steam2."' LIMIT 1;");
        if($admin && $admin['gimmune']>$admin['pimmune'])
            $immune = $admin['gimmune'];
        elseif($admin)
            $immune = $admin['pimmune'];
        else
            $immune = 0;

        if($immune <= $userbank->GetProperty('srv_immunity')) {
            $requri = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], ".php")+4);
            
            if(strpos($steam, "[U:") === 0) {
                $kick = $r->sendCommand("kickid \"".$steam."\" \"Вы были кикнуты с сервера. Перейтидте по адресу http://" . $_SERVER['HTTP_HOST'].$requri." для большей информации.\"");
            } else {
                $kick = $r->sendCommand("kickid ".$steam." \"Вы были кикнуты с сервера. Перейтидте по адресу http://" . $_SERVER['HTTP_HOST'].$requri." для большей информации.\"");
            }

            $log = new CSystemLog("m", "Игрок кикнут", $username . " кикнул игрока '".htmlspecialchars($name)."' (".$steam.") from ".$data['ip'].":".$data['port'].".", true, true);
            $objResponse->addScript("ShowBox('Игрок кикнут', 'Игрок \'".addslashes(htmlspecialchars($name))."\' был кикнут с сервера.', 'green', 'index.php?p=servers', 1500);$('dialog-control').setStyle('display', 'none');");
        } else {
            $objResponse->addScript("ShowBox('Ошибка', 'Невозможно кикнуть ".addslashes(htmlspecialchars($name)).". У него иммунитет!', 'red', '', true);");
        }
    } else {
        $objResponse->addScript("ShowBox('Ошибка', 'Невозможно кикнуть ".addslashes(htmlspecialchars($name)).". Игрок покинул сервер!', 'red', '', true);");
    }
    return $objResponse;
}
