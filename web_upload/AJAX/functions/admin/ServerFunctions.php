<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function SendRcon($sid, $command, $output)
{
    global $userbank, $username;
    $objResponse = new xajaxResponse();
    if(!$userbank->HasAccess(SM_RCON . SM_ROOT))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался отправить РКОН команду, не имея на это прав.");
        return $objResponse;
    }
    if(empty($command))
    {
        $objResponse->addScript("$('cmd').value=''; $('cmd').disabled='';$('rcon_btn').disabled=''");
        return $objResponse;
    }
    if($command == "clr")
    {
        $objResponse->addAssign("rcon_con", "innerHTML",  "<div class='lv-item media'><div class='lv-avatar bgm-red pull-left'>R</div><div class='media-body'><div class='ms-item' style='display: block;max-width: 100%;'>************************************************************<br />*&nbsp;SourceBans РКОН консоль<br />*&nbsp;Введите команду в поле ниже и нажмите Enter<br />*&nbsp;Введите 'clr' для очистки консоли<br />************************************************************</div></div></div>");
        $objResponse->addScript("scroll.toBottom(); $('cmd').value=''; $('cmd').disabled='';$('rcon_btn').disabled=''");
        return $objResponse;
    }
    
    if(stripos($command, "rcon_password") !== false)
    {
        $objResponse->addAppend("rcon_con", "innerHTML",  "<div class='lv-item media p-b-5 p-t-5'><div class='lv-avatar bgm-red pull-left'>R</div><div class='media-body'><div class='ms-item' style='display: block;max-width: 100%;'> > Ошибка: Вы используете консоль. Не пытайтесь подобрать RCON пароль!</div></div></div>");
        $objResponse->addScript("scroll.toBottom(); $('cmd').value=''; $('cmd').disabled='';$('rcon_btn').disabled=''");
        return $objResponse;
    }
    
    $sid = (int)$sid;
    
    $rcon = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM `".DB_PREFIX."_servers` WHERE sid = ".$sid." LIMIT 1");
    if(empty($rcon['rcon']))
    {
        $objResponse->addAppend("rcon_con", "innerHTML", "<div class='lv-item media p-b-5 p-t-5'><div class='lv-avatar bgm-red pull-left'>R</div><div class='media-body'><div class='ms-item' style='display: block;max-width: 100%;'> > Ошибка: Нет RCON пароля!<br />Вы должны добавить RCON пароль для этого сервера на странице 'редактирования серверов' <br /> чтобы использовать консоль!</div></div></div>");
        $objResponse->addScript("scroll.toBottom(); $('cmd').value='Задать РКОН пароль.'; $('cmd').disabled=true; $('rcon_btn').disabled=true");
        return $objResponse;
    }
    if(!$test = @fsockopen($rcon['ip'], $rcon['port'], $errno, $errstr, 2))
    {
        @fclose($test);
        $objResponse->addAppend("rcon_con", "innerHTML", "<div class='lv-item media p-b-5 p-t-5'><div class='lv-avatar bgm-red pull-left'>R</div><div class='media-body'><div class='ms-item' style='display: block;max-width: 100%;'> > Ошибка: невозможно соединиться с сервером!</div></div></div>");
        $objResponse->addScript("scroll.toBottom(); $('cmd').value=''; $('cmd').disabled='';$('rcon_btn').disabled=''");
        return $objResponse;
    }
    @fclose($test);
    include(INCLUDES_PATH . "/CServerControl.php");
    
    $r = new CServerControl();
    $r->Connect($rcon['ip'], $rcon['port']);
    
    if(!$r->AuthRcon($rcon['rcon']))
    {
        $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$sid."';");
        $objResponse->addAppend("rcon_con", "innerHTML", "<div class='lv-item media p-b-5 p-t-5'><div class='lv-avatar bgm-red pull-left'>R</div><div class='media-body'><div class='ms-item' style='display: block;max-width: 100%;'> > Ошибка: неверный РКОН пароль!<br />Вы должны изменить РКОН пароль для этого сервера.<br /> Если Вы продолжите использовать консоль с неверным РКОН паролем, <br />сервер заблокирует соединение!</div></div></div>");
        $objResponse->addScript("scroll.toBottom(); $('cmd').value='Сменить РКОН пароль.'; $('cmd').disabled=true; $('rcon_btn').disabled=true");
        return $objResponse;
    }
    $ret = $r->SendCommand($command);


    $textAppend = "<div class='lv-item media right p-b-5 p-t-5'><div class='lv-avatar bgm-orange pull-right'><img src='".GetUserAvatar($userbank->getProperty("authid"))."' /></div><div class='media-body'><div class='ms-item'> $command </div><small class='ms-date'><i class='zmdi zmdi-time'></i> ".date("d/m/Y в H:i")."</small></div></div>";
    $ret = str_replace("\n", "<br />", $ret);
    if(empty($ret))
    {
        if($output)
        {
            //$objResponse->addAppend("rcon_con", "innerHTML",  "-> $command<br />");
            //$objResponse->addAppend("rcon_con", "innerHTML",  "Команда выполнена.<br />");
            $textAppend .= "<div class='lv-item media p-b-5 p-t-5'><div class='lv-avatar bgm-red pull-left'>R</div><div class='media-body'><div class='ms-item' style='display: block;max-width: 100%;'> Команда была отправлена, но ответа не последовало... :C </div></div></div>";
        }
    }
    else
    {
        if($output)
        {
            //$objResponse->addAppend("rcon_con", "innerHTML",  "-> $command<br />");
            //$objResponse->addAppend("rcon_con", "innerHTML",  "$ret<br />");
            $textAppend .= "<div class='lv-item media p-b-5 p-t-5'><div class='lv-avatar bgm-red pull-left'>R</div><div class='media-body'><div class='ms-item' style='display: block;max-width: 100%;'> $ret </div></div></div>";
        }
    }
    $objResponse->addAppend("rcon_con", "innerHTML", $textAppend);
    $objResponse->addScript("scroll.toBottom(); $('cmd').value=''; $('cmd').disabled=''; $('rcon_btn').disabled=''");
    $log = new CSystemLog("m", "РКОН отправлен", "РКОН был отправлен на сервер (".$rcon['ip'].":".$rcon['port']."). Команда: $command", true, true);
    return $objResponse;
}

function AddServer($ip, $port, $rcon, $rcon2, $mod, $enabled, $group, $group_name)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_SERVER))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался добавить сервер, не имея на это прав.");
        return $objResponse;
    }
    $ip = RemoveCode($ip);
    $group_name = RemoveCode($group_name);

    $error = 0;
    // ip
    if((empty($ip)))
    {
        $error++;
        $objResponse->addAssign("address.msg", "innerHTML", "Введите адрес сервера.");
        $objResponse->addScript("$('address.msg').setStyle('display', 'block');");
    }
    else
    {
        $objResponse->addAssign("address.msg", "innerHTML", "");
        if(!validate_ip($ip) && !is_string($ip))
        {
            $error++;
            $objResponse->addAssign("address.msg", "innerHTML", "Введите действительный IP сервера.");
            $objResponse->addScript("$('address.msg').setStyle('display', 'block');");
        }
        else
            $objResponse->addAssign("address.msg", "innerHTML", "");
    }
    // Port
    if((empty($port)))
    {
        $error++;
        $objResponse->addAssign("port.msg", "innerHTML", "Введите порт сервера.");
        $objResponse->addScript("$('port.msg').setStyle('display', 'block');");
    }
    else
    {
        $objResponse->addAssign("port.msg", "innerHTML", "");
        if(!is_numeric($port))
        {
            $error++;
            $objResponse->addAssign("port.msg", "innerHTML", "Введите действительный порт <b>цифрами</b>.");
            $objResponse->addScript("$('port.msg').setStyle('display', 'block');");
        }
        else
        {
            $objResponse->addScript("$('port.msg').setStyle('display', 'none');");
            $objResponse->addAssign("port.msg", "innerHTML", "");
        }
    }
    // rcon
    if(!empty($rcon) && $rcon != $rcon2)
    {
        $error++;
        $objResponse->addAssign("rcon2.msg", "innerHTML", "Пароли не совпадают.");
        $objResponse->addScript("$('rcon2.msg').setStyle('display', 'block');");
    }
    else
        $objResponse->addAssign("rcon2.msg", "innerHTML", "");

    // Please Select
    if($mod == -2)
    {
        $error++;
        $objResponse->addAssign("mod.msg", "innerHTML", "Выберите МОД сервера.");
        $objResponse->addScript("$('mod.msg').setStyle('display', 'block');");
    }
    else
        $objResponse->addAssign("mod.msg", "innerHTML", "");

    if($group == -2)
    {
        $error++;
        $objResponse->addAssign("group.msg", "innerHTML", "Вы должны выбрать опцию.");
        $objResponse->addScript("$('group.msg').setStyle('display', 'block');");
    }
    else
        $objResponse->addAssign("group.msg", "innerHTML", "");

    if($error)
        return $objResponse;
    
    // Check for dublicates afterwards
    $chk = $GLOBALS['db']->GetRow('SELECT sid FROM `'.DB_PREFIX.'_servers` WHERE ip = ? AND port = ?;', array($ip, (int)$port));
    if($chk)
    {
        $objResponse->addScript("ShowBox('Ошибка', 'Введённый сервер уже существует в базе.', 'red');");
        return $objResponse;
    }

    // ##############################################################
    // ##                     Start adding to DB                   ##
    // ##############################################################
    //they wanna make a new group
    $gid = -1;
    $sid = nextSid();
    
    $enable = ($enabled=="true"?1:0);

    // Add the server
    $addserver = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_servers (`sid`, `ip`, `port`, `rcon`, `modid`, `enabled`)
                                          VALUES (?,?,?,?,?,?)");
    $GLOBALS['db']->Execute($addserver,array($sid, $ip, (int)$port, $rcon, $mod, $enable));

    // Add server to each group specified
    $groups = explode(",", $group);
    $addtogrp = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_servers_groups (`server_id`, `group_id`) VALUES (?,?)");
    foreach($groups AS $g)
    {
        if($g)
            $GLOBALS['db']->Execute($addtogrp,array($sid, $g));
    }


    $objResponse->addScript("ShowBox('Сервер добавлен', 'Ваш сервер был успешно создан.', 'green', 'index.php?p=admin&c=servers');");
    $objResponse->addScript("TabToReload();");
    $log = new CSystemLog("m", "Сервер добавлен", "Сервер (" . $ip . ":" . $port . ") добавлен");
    return $objResponse;
}

function SetupEditServer($sid)
{
    $objResponse = new xajaxResponse();
    $sid = (int)$sid;
    $server = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_servers WHERE sid = $sid");

    // clear any old stuff
    $objResponse->addScript("$('address').value = ''");
    $objResponse->addScript("$('port').value = ''");
    $objResponse->addScript("$('rcon').value = ''");
    $objResponse->addScript("$('rcon2').value = ''");
    $objResponse->addScript("$('mod').value = '0'");
    $objResponse->addScript("$('serverg').value = '0'");


    // add new stuff
    $objResponse->addScript("$('address').value = '" . $server['ip']. "'");
    $objResponse->addScript("$('port').value =  '" . $server['port']. "'");
    $objResponse->addScript("$('rcon').value =  '" . $server['rcon']. "'");
    $objResponse->addScript("$('rcon2').value =  '" . $server['rcon']. "'");
    $objResponse->addScript("$('mod').value =  " . $server['modid']);
    $objResponse->addScript("$('serverg').value =  " . $server['gid']);

    $objResponse->addScript("$('insert_type').value =  " . $server['sid']);
    $objResponse->addScript("SwapPane(1);");
    return $objResponse;
}

function RemoveServer($sid)
{
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_SERVERS))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался удалить сервер, не имея на это прав.");
        return $objResponse;
    }
    $sid = (int)$sid;
    $objResponse->addScript("SlideUp('sid_$sid');");
    $servinfo = $GLOBALS['db']->GetRow("SELECT ip, port FROM `" . DB_PREFIX . "_servers` WHERE sid = $sid");
    $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_servers` WHERE sid = $sid");
    $query2 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_servers_groups` WHERE server_id = $sid");
    $query3 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins_servers_groups` SET server_id = -1 WHERE server_id = $sid");

    $query = $GLOBALS['db']->GetRow("SELECT count(sid) AS cnt FROM `" . DB_PREFIX . "_servers`");
    $objResponse->addScript("$('srvcount').setHTML('" . $query['cnt'] . "');");


    if($query1)
    {
        $objResponse->addScript("ShowBox('Сервер удалён', 'Выбранный сервер был успешно удалён из базы данных', 'green', 'index.php?p=admin&c=servers', true);");
        $log = new CSystemLog("m", "Сервер удалён", "Сервер ((" . $servinfo['ip'] . ":" . $servinfo['port'] . ") был удалён");
    }
    else
        $objResponse->addScript("ShowBox('Ошибка', 'Не получилось удалить сервер. Смотрите системный лог для дополнительной информации', 'red', 'index.php?p=admin&c=servers', true);");
    return $objResponse;
}
