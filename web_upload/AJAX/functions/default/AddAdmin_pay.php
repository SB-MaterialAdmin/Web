<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function AddAdmin_pay($mask, $srv_mask, $a_name, $a_steam, $a_email, $a_password, $a_password2,    $a_sg, $a_wg, $a_serverpass, $a_webname, $a_servername, $server, $singlesrv, $skype, $comment, $vk, $a_code) {
    $objResponse = new xajaxResponse();
    global $userbank, $username;

    $mask = "";
    $srv_mask = "";
    $a_sg = "";
    $a_wg = "";
    $a_serverpass = "-1";
    $a_webname = "0";
    $a_servername = "0";
    $server = "";
    $comment = "";

    $vk = RemoveCode($vk);
    $vk = str_replace(array("http://","https://","/","vk.com"), "", $vk);
    $skype = RemoveCode($skype);
    $a_code = RemoveCode($a_code);
    $a_code = preg_replace("/[^0-9]/", '', $a_code);

    $srv_sql_val = $GLOBALS['db']->GetOne("SELECT `servers` FROM `" . DB_PREFIX . "_vay4er` WHERE `value` = '".$a_code."'");
    if($srv_sql_val == "-1"){
        $singlesrv = "";
    }elseif((stristr($srv_sql_val, ',') && stristr($srv_sql_val, 's')) == TRUE){
        $singlesrv = $srv_sql_val;
    }
    
    $qwe = $GLOBALS['db']->GetOne("SELECT `activ` FROM `" . DB_PREFIX . "_vay4er` WHERE `value` = '".$a_code."'");
    if($qwe == "0" || $qwe != "1"){
        $objResponse->addScript("ShowBox('Активация', 'Ваш ваучер уже был успешно активирован! Повторная активация - невозможна. Переадресация...', 'red', 'index.php', false);");
        $log = new CSystemLog("w", "Ваучер", $a_name . " пытался активировать ваучер повторно.");
        return $objResponse;
        exit();
    }
    
    $pay_days_sql = $GLOBALS['db']->GetOne("SELECT `days` FROM `" . DB_PREFIX . "_vay4er` WHERE `value` = '".$a_code."'");
    if(!$pay_days_sql == "0"){
        $pay_days_sql = (time() + $pay_days_sql * 86400);
    }
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
        if(strstr($a_name, "'"))
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
        $objResponse->addAssign("steam.msg", "innerHTML", "Введите ваш Steam ID или Community ID. Его можно найти в консоле, написав <b>status</b>.");
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
                $objResponse->addAssign("steam.msg", "innerHTML", "Этот Steam ID уже используется одним из администраторов!");
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
        $error++;
        $objResponse->addAssign("email.msg", "innerHTML", "Введите адрес e-mail.");
        $objResponse->addScript("$('email.msg').setStyle('display', 'block');");
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
            $objResponse->addAssign("email.msg", "innerHTML", "Этот e-mail уже используется одним из администраторов!");
            $objResponse->addScript("$('email.msg').setStyle('display', 'block');");
        }
        else
        {
            $objResponse->addAssign("email.msg", "innerHTML", "");
            $objResponse->addScript("$('email.msg').setStyle('display', 'none');");
        }
    }
    
    // no pass
    if(empty($a_password))
    {
        // A password is only required for users with web permissions.
        $error++;
        $objResponse->addAssign("password.msg", "innerHTML", "Введите пароль.");
        $objResponse->addScript("$('password.msg').setStyle('display', 'block');");
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

    //$q_del = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_vay4er` WHERE `value` = '".$a_code."'");
    $q_del = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_vay4er` SET `value` = '".$a_code."', `activ` = '0' WHERE `value` = '".$a_code."'");
    if($q_del){
        // Add the admin
        $web_gruop_id = $GLOBALS['db']->GetOne("SELECT `group_web` FROM ".DB_PREFIX."_vay4er WHERE `value` = '".$a_code."'");
        $web_gruop_sql = $GLOBALS['db']->GetOne("SELECT `gid` FROM ".DB_PREFIX."_groups WHERE `name` = '".$web_gruop_id."'");
        if($web_gruop_id == "" || $web_gruop_sql == "" ){
            $web_gruop_sql = "0";
        }
        $server_admin_group = $GLOBALS['db']->GetOne("SELECT `group_srv` FROM ".DB_PREFIX."_vay4er WHERE `value` = '".$a_code."'");
        if($server_admin_group == ""){
            $web_gruop_sql = "";
        }
        $aid = $userbank->AddAdmin($a_name, $a_steam, $a_password, $a_email, $web_gruop_sql, $mask, $server_admin_group, $srv_mask, $immunity, $a_serverpass, $pay_days_sql, $skype, '', $vk);
        setcookie("aid", $aid, time()+LOGIN_COOKIE_LIFETIME);
        setcookie("password", $GLOBALS['db']->GetOne("SELECT `password` FROM `".DB_PREFIX."_admins` WHERE `aid` = '".$aid."'"), time()+LOGIN_COOKIE_LIFETIME);
    }else{
        exit();
    }
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
            $objResponse->addScript("ShowRehashBox_pay('".implode(",", $allservers)."','Активация', 'Ваш ваучер был успешно активирован! Администратор (" . $a_name . ") был успешно добавлен!', 'green', 'index.php?p=account', '".$a_code."');TabToReload();");
        } else
            $objResponse->addScript("ShowBox('Активация', 'Ваш ваучер был успешно активирован! Администратор (" . $a_name . ") был успешно добавлен! Его ключ был:".$a_code."', 'green', 'index.php');TabToReload();");
        
        $log = new CSystemLog("m", "Ваучер", "Ваучер ".$a_code." был успешно активирован! Администратор (" . $a_name . ") был успешно добавлен!");
        return $objResponse;
    }
    else
    {
        $objResponse->addScript("ShowBox('Ваучер', 'Ошибка при активации ваучера. Свяжитесь с главной администрацией, для проверки лога на наличие SQL ошибок.', 'red', 'index.php');");
    }
}
