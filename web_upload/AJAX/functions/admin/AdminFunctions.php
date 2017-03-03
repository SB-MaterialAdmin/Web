<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function AddAdmin($mask, $srv_mask, $a_name, $a_steam, $a_email, $a_password, $a_password2,    $a_sg, $a_wg, $a_serverpass, $a_webname, $a_servername, $server, $singlesrv, $a_period, $skype, $comment, $vk)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_ADMINS))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался добавить админа, не имея на то прав.");
        return $objResponse;
    }
    $vk = str_replace(array("http://","https://","/","vk.com"), "", $vk);
    $a_name = RemoveCode($a_name);
    $a_steam = RemoveCode($a_steam);
    $a_email = RemoveCode($a_email);
    $a_servername = ($a_servername=="0" ? null : RemoveCode($a_servername));
    $a_webname = RemoveCode($a_webname);
    $mask = (int)$mask;

    $error=0;
    
    //No name
    if(empty($a_name))
    {
        $error++;
        $objResponse->addAssign("name.msg", "innerHTML", "Введите имя админа.");
        $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
    }
    else{
        if(strstr($a_name, '/'))
        {
            $error++;
            $objResponse->addAssign("name.msg", "innerHTML", "Имя админа не должно содержать символы \" / \".");
            $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
        }
        elseif(strstr($a_name, "'"))
        {
            $error++;
            $objResponse->addAssign("name.msg", "innerHTML", "Имя админа не должно содержать символы \" ' \".");
            $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
        }
        else
        {
            if(is_taken("admins", "user", $a_name))
            {
                    $error++;
                    $objResponse->addAssign("name.msg", "innerHTML", "Администратор с таким именем уже существует");
                    $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
            }
            else
            {
                    $objResponse->addAssign("name.msg", "innerHTML", "");
                    $objResponse->addScript("$('name.msg').setStyle('display', 'none');");
            }
        }
    }
    // If they didnt type a steamid
    if((empty($a_steam) || strlen($a_steam) < 10))
    {
        $error++;
        $objResponse->addAssign("steam.msg", "innerHTML", "Введите Steam ID или Community ID админа.");
        $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
    }
    else
    {
        // Validate the steamid or fetch it from the community id
        if((!is_numeric($a_steam) 
        && !validate_steam($a_steam))
        || (is_numeric($a_steam) 
        && (strlen($a_steam) < 15
        || !validate_steam($a_steam = FriendIDToSteamID($a_steam)))))
        {
            $error++;
            $objResponse->addAssign("steam.msg", "innerHTML", "Введите действительный Steam ID или Community ID.");
            $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
        }
        else
        {
            if(is_taken("admins", "authid", $a_steam))
            {
                $admins = $userbank->GetAllAdmins();
                foreach($admins as $admin)
                {
                    if($admin['authid'] == $a_steam)
                    {
                        $name = $admin['user'];
                        break;
                    }
                }
                $error++;
                $objResponse->addAssign("steam.msg", "innerHTML", "Этот Steam ID уже используется админом ".htmlspecialchars(addslashes($name)).".");
                $objResponse->addScript("$('steam.msg').setStyle('display', 'block');");
            }
            else
            {
                $objResponse->addAssign("steam.msg", "innerHTML", "");
                $objResponse->addScript("$('steam.msg').setStyle('display', 'none');");
            }
        }
    }
    
    // No email
    if(empty($a_email))
    {
        // An E-Mail address is only required for users with web permissions.
        if($mask != 0)
        {
            $error++;
            $objResponse->addAssign("email.msg", "innerHTML", "Введите адрес e-mail.");
            $objResponse->addScript("$('email.msg').setStyle('display', 'block');");
        }
    }
    else{
        // Is an other admin already registred with that email address?
        if(is_taken("admins", "email", $a_email))
        {
            $admins = $userbank->GetAllAdmins();
            foreach($admins as $admin)
            {
                if($admin['email'] == $a_email)
                {
                    $name = $admin['user'];
                    break;
                }
            }
            $error++;
            $objResponse->addAssign("email.msg", "innerHTML", "Этот e-mail уже используется админом ".htmlspecialchars(addslashes($name)).".");
            $objResponse->addScript("$('email.msg').setStyle('display', 'block');");
        }
        else
        {
            $objResponse->addAssign("email.msg", "innerHTML", "");
            $objResponse->addScript("$('email.msg').setStyle('display', 'none');");
        /*    if(!validate_email($a_email))
            {
                $error++;
                $objResponse->addAssign("email.msg", "innerHTML", "Please enter a valid email address.");
                $objResponse->addScript("$('email.msg').setStyle('display', 'block');");
            }
            else
            {
                $objResponse->addAssign("email.msg", "innerHTML", "");
                $objResponse->addScript("$('email.msg').setStyle('display', 'none');");

            }*/
        }
    }
    
    // no pass
    if(empty($a_password))
    {
        // A password is only required for users with web permissions.
        if($mask != 0)
        {
            $error++;
            $objResponse->addAssign("password.msg", "innerHTML", "Введите пароль.");
            $objResponse->addScript("$('password.msg').setStyle('display', 'block');");
        }
    }
    // Password too short?
    else if(strlen($a_password) < MIN_PASS_LENGTH)
    {
        $error++;
        $objResponse->addAssign("password.msg", "innerHTML", "Длина пароля должна быть не менее " . MIN_PASS_LENGTH . " символов.");
        $objResponse->addScript("$('password.msg').setStyle('display', 'block');");
    }
    else 
    {
        $objResponse->addAssign("password.msg", "innerHTML", "");
        $objResponse->addScript("$('password.msg').setStyle('display', 'none');");
        
        // No confirmation typed
        if(empty($a_password2))
        {
            $error++;
            $objResponse->addAssign("password2.msg", "innerHTML", "Подтвердите пароль");
            $objResponse->addScript("$('password2.msg').setStyle('display', 'block');");
        }
        // Passwords match?
        else if($a_password != $a_password2)
        {
            $error++;
            $objResponse->addAssign("password2.msg", "innerHTML", "Пароли не совпадают");
            $objResponse->addScript("$('password2.msg').setStyle('display', 'block');");
        }
        else
        {
            $objResponse->addAssign("password2.msg", "innerHTML", "");
            $objResponse->addScript("$('password2.msg').setStyle('display', 'none');");
        }
    }

    // Choose to use a server password
    if($a_serverpass != "-1")
    {
        // No password given?
        if(empty($a_serverpass))
        {
            $error++;
            $objResponse->addAssign("a_serverpass.msg", "innerHTML", "Введите пароль сервера, либо снимите галочку.");
            $objResponse->addScript("$('a_serverpass.msg').setStyle('display', 'block');");
        }
        // Password too short?
        else if(strlen($a_serverpass) < MIN_PASS_LENGTH)
        {
            $error++;
            $objResponse->addAssign("a_serverpass.msg", "innerHTML", "Длина пароля должна быть не менее " . MIN_PASS_LENGTH . " символов.");
            $objResponse->addScript("$('a_serverpass.msg').setStyle('display', 'block');");
        }
        else 
        {
            $objResponse->addAssign("a_serverpass.msg", "innerHTML", "");
            $objResponse->addScript("$('a_serverpass.msg').setStyle('display', 'none');");
        }
    }
    else
    {
        $objResponse->addAssign("a_serverpass.msg", "innerHTML", "");
        $objResponse->addScript("$('a_serverpass.msg').setStyle('display', 'none');");
        // Don't set "-1" as password ;)
        $a_serverpass = "";
    }
    
    // didn't choose a server group
    if($a_sg == "-2")
    {
        $error++;
        $objResponse->addAssign("server.msg", "innerHTML", "Выберите группу.");
        $objResponse->addScript("$('server.msg').setStyle('display', 'block');");
    }
    else
    {
        $objResponse->addAssign("server.msg", "innerHTML", "");
        $objResponse->addScript("$('server.msg').setStyle('display', 'none');");
    }
    
    // chose to create a new server group
    if($a_sg == 'n')
    {
        // didn't type a name
        if(empty($a_servername))
        {
            $error++;
            $objResponse->addAssign("servername_err", "innerHTML", "Введите имя новой группы.");
            $objResponse->addScript("$('servername_err').setStyle('display', 'block');");
        }
        // Group names can't contain ,
        else if(strstr($a_servername, ','))
        {
            $error++;
            $objResponse->addAssign("servername_err", "innerHTML", "Имя группы не может содержать запятую.");
            $objResponse->addScript("$('servername_err').setStyle('display', 'block');");
        }
        else
        {
            $objResponse->addAssign("servername_err", "innerHTML", "");
            $objResponse->addScript("$('servername_err').setStyle('display', 'none');");
        }
    }
    
    // didn't choose a web group
    if($a_wg == "-2")
    {
        $error++;
        $objResponse->addAssign("web.msg", "innerHTML", "Выберите группу.");
        $objResponse->addScript("$('web.msg').setStyle('display', 'block');");
    }
    else
    {
        $objResponse->addAssign("web.msg", "innerHTML", "");
        $objResponse->addScript("$('web.msg').setStyle('display', 'none');");
    }
    
    // Choose to create a new webgroup
    if($a_wg == 'n')
    {
        // But didn't type a name
        if(empty($a_webname))
        {
            $error++;
            $objResponse->addAssign("webname_err", "innerHTML", "Введите имя новой группы.");
            $objResponse->addScript("$('webname_err').setStyle('display', 'block');");
        }
        // Group names can't contain ,
        else if(strstr($a_webname, ','))
        {
            $error++;
            $objResponse->addAssign("webname_err", "innerHTML", "Имя группы не может содержать запятую.");
            $objResponse->addScript("$('webname_err').setStyle('display', 'block');");
        }
        else
        {
            $objResponse->addAssign("webname_err", "innerHTML", "");
            $objResponse->addScript("$('webname_err').setStyle('display', 'none');");
        }
    }
    
    // Проверка срока админки
    if(!preg_match("#^([0-9]+)$#i",$a_period))
    {
        $error++;
        $objResponse->addAssign("a_period.msg", "innerHTML", "Только цифры.");
        $objResponse->addScript("$('a_period.msg').setStyle('display', 'block');");
    }
    else 
    {
        $objResponse->addAssign("a_period.msg", "innerHTML", "");
        $objResponse->addScript("$('a_period.msg').setStyle('display', 'none');");
    }
    
    // Ohnoes! something went wrong, stop and show errs
    if($error)
    {
        ShowBox_ajx("Ошибка", "Допущены ошибки. Пожалуйста, исправьте их.", "red", "", true, $objResponse);
        return $objResponse;
    }

// ##############################################################
// ##                     Start adding to DB                   ##
// ##############################################################
    
    $gid = 0;
    $groupID = 0;
    $inGroup = false;
    $wgid = NextAid();
    $immunity = 0;
    $a_period = intval($a_period);
    
    // Extract immunity from server mask string
    if(strstr($srv_mask, "#"))
    {
        $immunity = "0";
        $immunity = substr($srv_mask, strpos($srv_mask, "#")+1);
        $srv_mask = substr($srv_mask, 0, strlen($srv_mask) - strlen($immunity)-1);
    }
    
    // Avoid negative immunity
    $immunity = ($immunity>0) ? $immunity : 0;
    
    // Handle Webpermissions
    // Chose to create a new webgroup
    if($a_wg == 'n')
    {
        $add_webgroup = $GLOBALS['db']->Execute("INSERT INTO ".DB_PREFIX."_groups(type, name, flags)
                                        VALUES (?,?,?)", array(1, $a_webname, $mask));
        $web_group = (int)$GLOBALS['db']->Insert_ID();
        
        // We added those permissons to the group, so don't add them as custom permissions again
        $mask = 0;
    }
    // Chose an existing group
    else if($a_wg != 'c' && $a_wg > 0)
    {
        $web_group = (int)$a_wg;
    }
    // Custom permissions -> no group
    else
    {
        $web_group = -1;
    }
    
    // Handle Serverpermissions
    // Chose to create a new server admin group
    if($a_sg == 'n')
    {
        $add_servergroup = $GLOBALS['db']->Execute("INSERT INTO ".DB_PREFIX."_srvgroups(immunity, flags, name, groups_immune)
                    VALUES (?,?,?,?)", array($immunity, $srv_mask, $a_servername, " "));
        
        $server_admin_group = $a_servername;
        $server_admin_group_int = (int)$GLOBALS['db']->Insert_ID();
        
        // We added those permissons to the group, so don't add them as custom permissions again
        $srv_mask = "";
    }
    // Chose an existing group
    else if($a_sg != 'c' && $a_sg > 0)
    {
        $server_admin_group = $GLOBALS['db']->GetOne("SELECT `name` FROM ".DB_PREFIX."_srvgroups WHERE id = '" . (int)$a_sg . "'");
        $server_admin_group_int = (int)$a_sg;
    }
    // Custom permissions -> no group
    else
    {
        $server_admin_group = "";
        $server_admin_group_int = -1;
    }
    
    // Срок админки
    if($a_period == 0) {
        $period = 0;
    }
    else {
        $period = $a_period * 86400 + time();
    }

    
    // Add the admin
    $aid = $userbank->AddAdmin($a_name, $a_steam, $a_password, $a_email, $web_group, $mask, $server_admin_group, $srv_mask, $immunity, $a_serverpass, $period, $skype, $comment, $vk);
    
    if($aid > -1)
    {
        // Grant permissions to the selected server groups
        $srv_groups = explode(",", $server);
        $addtosrvgrp = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_admins_servers_groups(admin_id,group_id,srv_group_id,server_id) VALUES (?,?,?,?)");
        foreach($srv_groups AS $srv_group)
        {
            if(!empty($srv_group))
                $GLOBALS['db']->Execute($addtosrvgrp,array($aid, $server_admin_group_int, substr($srv_group, 1), '-1'));
        }
        
        // Grant permissions to individual servers
        $srv_arr = explode(",", $singlesrv);
        $addtosrv = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_admins_servers_groups(admin_id,group_id,srv_group_id,server_id) VALUES (?,?,?,?)");
        foreach($srv_arr AS $server)
        {
            if(!empty($server))
                $GLOBALS['db']->Execute($addtosrv,array($aid, $server_admin_group_int, '-1', substr($server, 1)));
        }
        if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1)
        {
            // rehash the admins on the servers
            $serveraccessq = $GLOBALS['db']->GetAll("SELECT s.sid FROM `".DB_PREFIX."_servers` s
                                                LEFT JOIN `".DB_PREFIX."_admins_servers_groups` asg ON asg.admin_id = '".(int)$aid."'
                                                LEFT JOIN `".DB_PREFIX."_servers_groups` sg ON sg.group_id = asg.srv_group_id
                                                WHERE ((asg.server_id != '-1' AND asg.srv_group_id = '-1')
                                                OR (asg.srv_group_id != '-1' AND asg.server_id = '-1'))
                                                AND (s.sid IN(asg.server_id) OR s.sid IN(sg.server_id)) AND s.enabled = 1");
            $allservers = array();
            foreach($serveraccessq as $access) {
                if(!in_array($access['sid'], $allservers)) {
                    $allservers[] = $access['sid'];
                }
            }
            $objResponse->addScript("ShowRehashBox('".implode(",", $allservers)."','Админ добавлен', 'Админ успешно добавлен', 'green', 'index.php?p=admin&c=admins');TabToReload();");
        } else
            $objResponse->addScript("ShowBox('Админ добавлен', 'Админ успешно добавлен', 'green', 'index.php?p=admin&c=admins');TabToReload();");
        
        $log = new CSystemLog("m", "Админ добавлен", "Админ (" . $a_name . ") добавлен");
        return $objResponse;
    }
    else
    {
        $objResponse->addScript("ShowBox('Пользователь не добавлен', 'Ошибка при добавлении админа в базу данных. Проверьте лог на наличие SQL ошибок.', 'red', 'index.php?p=admin&c=admins');");
    }
}

function AddSupport($aid)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    $aid = (int)$aid;
    if(!$userbank->is_logged_in())
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытается назначить администратора ".$userbank->GetProperty('user', $aid)." в Support-List, не имея на это прав.");
        return $objResponse;
    }elseif(!$userbank->HasAccess(ADMIN_OWNER)){
        $objResponse->addScript('ShowBox("Ошибка!", "У Вас недостаточно прав для выполнения этой операции!", "red", "index.php");');
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался назначить администратора в Support-List, не имея на это прав.");
        return $objResponse;
    }
    

    $resStmt = $GLOBALS['db']->prepare("SELECT `support` FROM `".DB_PREFIX."_admins` WHERE `aid` = ?");
    $res = $resStmt->execute([$aid]);
    $res = $resStmt->fetch(PDO::FETCH_LAZY);
    if($res == "1"){
        $chek = "0";
        $chek1 = "убран";
    }else{
        $chek = "1";
        $chek1 = "добавлен";
    }    
    $queryStmt = $GLOBALS['db']->prepare("UPDATE `" . DB_PREFIX . "_admins` SET `support` = ? WHERE `aid` = ?");
    $query = $queryStmt->execute([intval($chek), $aid]);
    if($query)
        $objResponse->addScript('ShowBox("Support-List", "Администратор был '.$chek1.', обновите страницу, чтобы увидеть результат, либо продолжайте дальнейшую работу.", "blue", "", true);');
    
    return $objResponse;
}

function RemoveAdmin($aid)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_ADMINS))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался удалить админа, не имея на это прав.");
        return $objResponse;
    }
    $aid = (int)$aid;
    $gid = $GLOBALS['db']->GetRow("SELECT gid, authid, extraflags, user FROM `" . DB_PREFIX . "_admins` WHERE aid = $aid");
    if((intval($gid[2]) & ADMIN_OWNER) != 0)
    {
        $objResponse->addAlert("Ошибка: Вы не можете удалить владельца.");
        return $objResponse;
    }

    $delquery = $GLOBALS['db']->Execute(sprintf("DELETE FROM `%s_admins` WHERE aid = %d LIMIT 1", DB_PREFIX, $aid));
    if($delquery) {
        if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1)
        {
            // rehash the admins for the servers where this admin was on
            $serveraccessq = $GLOBALS['db']->GetAll("SELECT s.sid FROM `".DB_PREFIX."_servers` s
                                                LEFT JOIN `".DB_PREFIX."_admins_servers_groups` asg ON asg.admin_id = '".(int)$aid."'
                                                LEFT JOIN `".DB_PREFIX."_servers_groups` sg ON sg.group_id = asg.srv_group_id
                                                WHERE ((asg.server_id != '-1' AND asg.srv_group_id = '-1')
                                                OR (asg.srv_group_id != '-1' AND asg.server_id = '-1'))
                                                AND (s.sid IN(asg.server_id) OR s.sid IN(sg.server_id)) AND s.enabled = 1");
            $allservers = array();
            foreach($serveraccessq as $access) {
                if(!in_array($access['sid'], $allservers)) {
                    $allservers[] = $access['sid'];
                }
            }
            $rehashing = true;
        }

        $GLOBALS['db']->Execute(sprintf("DELETE FROM `%s_admins_servers_groups` WHERE admin_id = %d", DB_PREFIX, $aid));
     }

    $query = $GLOBALS['db']->GetRow("SELECT count(aid) AS cnt FROM `" . DB_PREFIX . "_admins`");
    $objResponse->addScript("SlideUp('aid_$aid');");
    $objResponse->addScript("$('admincount').setHTML('" . $query['cnt'] . "');");
    if($delquery)
    {
        if(isset($rehashing))
            $objResponse->addScript("ShowRehashBox('".implode(",", $allservers)."', 'Админ удалён', 'Выбранный админ был удалён из базы данных', 'green', 'index.php?p=admin&c=admins', true);");
        else
            $objResponse->addScript("ShowBox('Админ удалён', 'Выбранный админ был удалён из базы данных', 'green', 'index.php?p=admin&c=admins', true);");
        $log = new CSystemLog("m", "Админ удалён", "Админ (" . $gid['user'] . ") был удалён");
    }
    else
        $objResponse->addScript("ShowBox('Ошибка', 'Не получилось удалить админа. Смотрите системный лог для дополнительной информации', 'red', 'index.php?p=admin&c=admins', true);");
    return $objResponse;
}

function RehashAdmins($server, $do=0)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    $do = (int)$do;
    if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ADMINS|ADMIN_EDIT_GROUPS|ADMIN_ADD_ADMINS))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался обновить админов, не имея на это прав.");
        return $objResponse;
    }
    $servers = explode(",",$server);
    if(sizeof($servers)>0) {
        if(sizeof($servers)-1 > $do)
            $objResponse->addScriptCall("xajax_RehashAdmins", $server, $do+1);

        $serv = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = '".(int)$servers[$do]."';");
        if(empty($serv['rcon'])) {
            $objResponse->addAppend("rehashDiv", "innerHTML", "".$serv['ip'].":".$serv['port']." (".($do+1)."/".sizeof($servers).") <font color='red'>Ошибка: не задан РКОН пароль</font>.<br />");
            if($do >= sizeof($servers)-1) {
                $objResponse->addAppend("rehashDiv", "innerHTML", "<b>Выполнено, переадресация....</b>");
                $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
                $objResponse->addScript("setTimeout(\"window.location = 'index.php?p=admin&c=admins';\", 1800);");
            }
            return $objResponse;
        }

        $test = @fsockopen($serv['ip'], $serv['port'], $errno, $errstr, 2);
        if(!$test) {
            $objResponse->addAppend("rehashDiv", "innerHTML", "".$serv['ip'].":".$serv['port']." (".($do+1)."/".sizeof($servers).") <font color='red'>Ошибка: нет соединения</font>.<br />");
            if($do >= sizeof($servers)-1) {
                $objResponse->addAppend("rehashDiv", "innerHTML", "<b>Выполнено, переадресация....</b>");
                $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
                $objResponse->addScript("setTimeout(\"window.location = 'index.php?p=admin&c=admins';\", 1800);");
            }
            return $objResponse;
        }

        require INCLUDES_PATH.'/CServerControl.php';
        
        $r = new CServerControl();
        $r->Connect($serv['ip'], $serv['port']);
        
        if(!$r->AuthRcon($serv['rcon']))
        {
            $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$serv['sid']."';");
            $objResponse->addAppend("rehashDiv", "innerHTML", "".$serv['ip'].":".$serv['port']." (".($do+1)."/".sizeof($servers).") <font color='red'>Ошибка: неверный РКОН пароль</font>.<br />");
            if($do >= sizeof($servers)-1) {
                $objResponse->addAppend("rehashDiv", "innerHTML", "<b>Выполнено, переадресация....</b>");
                $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
                $objResponse->addScript("setTimeout(\"window.location = 'index.php?p=admin&c=admins';\", 1800);");
            }
            return $objResponse;
        }

        if ($GLOBALS['config']['feature.old_serverside'] == "1") {
            $r->SendCommand("sm_rehash");
            $r->SendCommand("sm_reloadadmins");
        } else
            $r->SendCommand("ma_wb_rehashadm");

        $objResponse->addAppend("rehashDiv", "innerHTML", "".$serv['ip'].":".$serv['port']." (".($do+1)."/".sizeof($servers).") <font color='green'>успешно</font>.<br />");
        if($do >= sizeof($servers)-1) {
            $objResponse->addAppend("rehashDiv", "innerHTML", "<b>Выполнено, переадресация....</b>");
            $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
            $objResponse->addScript("setTimeout(\"window.location = 'index.php?p=admin&c=admins';\", 1800);");
        }
    } else {
        $objResponse->addAppend("rehashDiv", "innerHTML", "Не выбран сервер.");
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    }
    return $objResponse;
}

function EditAdminPerms($aid, $web_flags, $srv_flags)
{
    if(empty($aid))
        return;
    $aid = (int)$aid;
    $web_flags = (int)$web_flags;

    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ADMINS))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался изменить разрешения админа, не имея на это прав.");
        return $objResponse;
    }

    if(!$userbank->HasAccess(ADMIN_OWNER) && (int)$web_flags & ADMIN_OWNER )
    {
            $objResponse->redirect("index.php?p=login&m=no_access", 0);
            $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался сменить разрешения главного админа, не имея на это прав.");
            return $objResponse;
    }

    // Users require a password and email to have web permissions
    $password = $GLOBALS['userbank']->GetProperty('password', $aid);
    $email = $GLOBALS['userbank']->GetProperty('email', $aid);
    if($web_flags > 0 && (empty($password) || empty($email)))
    {
        $objResponse->addScript("ShowBox('Ошибка', 'Админ должен ввести E-mail и пароль для получения прав доступа к сайту.<br /><a href=\"index.php?p=admin&c=admins&o=editdetails&id=" . $aid . "\" title=\"Редактировать детали админа\">Измените детали админа</a> сначала и попробуйте снова.', 'red', '');");
        return $objResponse;
    }
    
    // Update web stuff
    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `extraflags` = $web_flags WHERE `aid` = $aid");


    if(strstr($srv_flags, "#"))
    {
        $immunity = "0";
        $immunity = substr($srv_flags, strpos($srv_flags, "#")+1);
        $srv_flags = substr($srv_flags, 0, strlen($srv_flags) - strlen($immunity)-1);
    }
    $immunity = ($immunity>0) ? $immunity : 0;
    // Update server stuff
    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `srv_flags` = ?, `immunity` = ? WHERE `aid` = $aid", array($srv_flags, $immunity));

    if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1)
    {
        // rehash the admins on the servers
        $serveraccessq = $GLOBALS['db']->GetAll("SELECT s.sid FROM `".DB_PREFIX."_servers` s
                                                LEFT JOIN `".DB_PREFIX."_admins_servers_groups` asg ON asg.admin_id = '".(int)$aid."'
                                                LEFT JOIN `".DB_PREFIX."_servers_groups` sg ON sg.group_id = asg.srv_group_id
                                                WHERE ((asg.server_id != '-1' AND asg.srv_group_id = '-1')
                                                OR (asg.srv_group_id != '-1' AND asg.server_id = '-1'))
                                                AND (s.sid IN(asg.server_id) OR s.sid IN(sg.server_id)) AND s.enabled = 1");
        $allservers = array();
        foreach($serveraccessq as $access) {
            if(!in_array($access['sid'], $allservers)) {
                $allservers[] = $access['sid'];
            }
        }
        $objResponse->addScript("ShowRehashBox('".implode(",", $allservers)."', 'Разрешения обновлены', 'Разрешения пользователя успешно обновлены', 'green', 'index.php?p=admin&c=admins');TabToReload();");
    } else
        $objResponse->addScript("ShowBox('Разрешения обновлены', 'Разрешения пользователя успешно обновлены', 'green', 'index.php?p=admin&c=admins');TabToReload();");
    $admname = $GLOBALS['db']->GetRow("SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = ?", array((int)$aid));
    $log = new CSystemLog("m", "Разрешения обновлены", "Разрешения обновлены для (".$admname['user'].")");
    return $objResponse;
}

function removeExpiredAdmins()
{
    global $userbank, $username;
    $objResponse = new xajaxResponse();

    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_ADMINS))
    {
        $objResponse->addScript('ShowBox("Ошибка!", "У Вас недостаточно прав для выполнения этой операции!.", "red", "index.php?p=admin&c=admins");');
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался удалить истёкших админов, не имея на это прав.");
        return $objResponse;
    }
    if($GLOBALS['db']->exec("DELETE FROM `".DB_PREFIX."_admins` WHERE `expired` < " . intval(time()) . " AND `expired` <> 0")) {
        $objResponse->addScript('ShowBox("Успешно!", "Все истёкшие админки удалены.", "green", "index.php?p=admin&c=admins");');
        $log = new CSystemLog("m", "Удаление админов", $username . " удалил всех истёкших админов.");
    }
    else {
        $objResponse->addScript('ShowBox("Ошибка!", "Ошибка в удалении истёкших админок. <br /> Смотрите в системный лог для подробной информации.", "red", "index.php?p=admin&c=admins");');
        $log = new CSystemLog("w", "Удаление админов", "Ошибка удаления истёкших админок.");
    }
    
    return $objResponse;
}

function UpdateAdminPermissions($type, $value)
{
    $objResponse = new xajaxResponse();
    global $userbank;
    $type = (int)$type;
    if($type == 1)
    {
        $id = "web";
        if($value == "c")
        {
            $permissions = @file_get_contents(TEMPLATES_PATH . "/groups.web.perm.php");
            $permissions = str_replace("{title}", "Разрешения доступа к сайту", $permissions);
        }
        elseif($value == "n")
        {
            $permissions = @file_get_contents(TEMPLATES_PATH . "/group.name.php") . @file_get_contents(TEMPLATES_PATH . "/groups.web.perm.php");
            $permissions = str_replace("{name}", "webname", $permissions);
            $permissions = str_replace("{title}", "Добавить группу доступа", $permissions);
        }
        else
            $permissions = "";
    }
    if($type == 2)
    {
        $id = "server";
        if($value == "c")
        {
            $permissions = file_get_contents(TEMPLATES_PATH . "/groups.server.perm.php");
            $permissions = str_replace("{title}", "Разрешения доступа к серверу", $permissions);
        }
        elseif($value == "n")
        {
            $permissions = @file_get_contents(TEMPLATES_PATH . "/group.name.php") . @file_get_contents(TEMPLATES_PATH . "/groups.server.perm.php");
            $permissions = str_replace("{name}", "servername", $permissions);
            $permissions = str_replace("{title}", "Добавить группу доступа", $permissions);
        }
        else
            $permissions = "";
    }

    $objResponse->addAssign($id."perm", "innerHTML", $permissions);
    if(!$userbank->HasAccess(ADMIN_OWNER))
        $objResponse->addScript('if($("wrootcheckbox")) { 
                                    $("wrootcheckbox").setStyle("display", "none");
                                }
                                if($("srootcheckbox")) { 
                                    $("srootcheckbox").setStyle("display", "none");
                                }');
    $objResponse->addAssign($id.".msg", "innerHTML", "");
    return $objResponse;

}
