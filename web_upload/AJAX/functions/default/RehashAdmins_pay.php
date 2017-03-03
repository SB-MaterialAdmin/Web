<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function RehashAdmins_pay($server, $do=0, $card) {
    global $userbank, $username;
    $objResponse = new xajaxResponse();
    
    $card = RemoveCode($card);
    $card = preg_replace("/[^0-9]/", "", $card);

    $wfr = $GLOBALS['db']->GetRow("SELECT * FROM `" . DB_PREFIX . "_vay4er` WHERE `value` = '".$card."'");
    if($wfr == "" || $wfr == "0" || $card == ""){
        return $objResponse;
    }

    $do = (int)$do;

    $servers = explode(",",$server);
    if(sizeof($servers)>0) {
        if(sizeof($servers)-1 > $do)
            $objResponse->addScriptCall("xajax_RehashAdmins_pay", $server, $do+1, $card);

        $serv = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = '".(int)$servers[$do]."';");
        if(empty($serv['rcon'])) {
            $objResponse->addAppend("rehashDiv", "innerHTML", "".$serv['ip'].":".$serv['port']." (".($do+1)."/".sizeof($servers).") <font color='red'>Ошибка: не задан РКОН пароль</font>.<br />");
            if($do >= sizeof($servers)-1) {
                $objResponse->addAppend("rehashDiv", "innerHTML", "<b>Выполнено, переадресация....</b>");
                $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
                $objResponse->addScript("setTimeout(\"window.location = 'index.php';\", 1800);");
            }
            return $objResponse;
        }

        $test = @fsockopen($serv['ip'], $serv['port'], $errno, $errstr, 2);
        if(!$test) {
            $objResponse->addAppend("rehashDiv", "innerHTML", "".$serv['ip'].":".$serv['port']." (".($do+1)."/".sizeof($servers).") <font color='red'>Ошибка: нет соединения</font>.<br />");
            if($do >= sizeof($servers)-1) {
                $objResponse->addAppend("rehashDiv", "innerHTML", "<b>Выполнено, переадресация....</b>");
                $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
                $objResponse->addScript("setTimeout(\"window.location = 'index.php';\", 1800);");
            }
            return $objResponse;
        }

        require INCLUDES_PATH.'/CServerControl.php';

        $r = new CServerControl();
        $r->Connect($serv['ip'], $serv['port']);

        if(!$r->AuthRcon($serv['rcon'])) {
            $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$serv['sid']."';");
            $objResponse->addAppend("rehashDiv", "innerHTML", "".$serv['ip'].":".$serv['port']." (".($do+1)."/".sizeof($servers).") <font color='red'>Ошибка: неверный РКОН пароль</font>.<br />");
            if($do >= sizeof($servers)-1) {
                $objResponse->addAppend("rehashDiv", "innerHTML", "<b>Выполнено, переадресация....</b>");
                $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
                $objResponse->addScript("setTimeout(\"window.location = 'index.php';\", 1800);");
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
            $objResponse->addScript("setTimeout(\"window.location = 'index.php';\", 1800);");
        }
    } else {
        $objResponse->addAppend("rehashDiv", "innerHTML", "Не выбран сервер.");
        $objResponse->addScript("$('dialog-control').setStyle('display', 'block');");
    }

    return $objResponse;
}
