<?php
/**************************************************************************
 * Эта программа является частью SourceBans MATERIAL Admin.
 *
 * Все права защищены © 2016-2017 Sergey Gut <webmaster@kruzefag.ru>
 *
 * SourceBans MATERIAL Admin распространяется под лицензией
 * Creative Commons Attribution-NonCommercial-ShareAlike 3.0.
 *
 * Вы должны были получить копию лицензии вместе с этой работой. Если нет,
 * см. <http://creativecommons.org/licenses/by-nc-sa/3.0/>.
 *
 * ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО
 * ГАРАНТИЙ, ЯВНЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ,
 * ГАРАНТИИ ПРИГОДНОСТИ ДЛЯ КОНКРЕТНЫХ ЦЕЛЕЙ И НЕНАРУШЕНИЯ. НИ ПРИ КАКИХ
 * ОБСТОЯТЕЛЬСТВАХ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ЗА
 * ЛЮБЫЕ ПРЕТЕНЗИИ, ИЛИ УБЫТКИ, НЕЗАВИСИМО ОТ ДЕЙСТВИЯ ДОГОВОРА,
 * ГРАЖДАНСКОГО ПРАВОНАРУШЕНИЯ ИЛИ ИНАЧЕ, ВОЗНИКАЮЩИЕ ИЗ, ИЛИ В СВЯЗИ С
 * ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ИЛИ ИНЫМИ ДЕЙСТВИЯМИ
 * ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ.
 *
 * Эта программа базируется на работе, охватываемой следующим авторским
 *                                                           правом (ами):
 *
 *  * SourceBans ++
 *    Copyright © 2014-2016 Sarabveer Singh
 *    Выпущено под лицензией CC BY-NC-SA 3.0
 *    Страница: <https://sbpp.github.io/>
 *
 ***************************************************************************/

include_once '../init.php';

if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
{
    echo "Нет доступа";
    die();
}
require_once(INCLUDES_PATH . '/xajax.inc.php');
$xajax = new xajax();
//$xajax->debugOn();
$xajax->setRequestURI("./admin.kickit.php");
$xajax->registerFunction("KickPlayer");
$xajax->registerFunction("LoadServers");
$xajax->processRequests();
$username = $userbank->GetProperty("user");

function LoadServers($check) {
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Попытка взлома", $username . " пытался использовать кик, не имея на это прав.");
        return $objResponse;
    }
    $id = 0;
    $servers = $GLOBALS['db']->Execute("SELECT sid, rcon FROM ".DB_PREFIX."_servers WHERE enabled = 1 ORDER BY modid, sid;");
    while(!$servers->EOF) {
        //search for player
        if(!empty($servers->fields["rcon"])) {
            $text = '<font size="1">Поиск...</font>';
            $objResponse->addScript("xajax_KickPlayer('".$check."', '".$servers->fields["sid"]."', '".$id."');");
        }
        else { //no rcon = servercount + 1 ;)
            $text = '<font size="1">Нет Rcon пароля.</font>';
            $objResponse->addScript('set_counter(1);');
        }        
        $objResponse->addAssign("srv_".$id, "innerHTML", $text);
        $id++;
        $servers->MoveNext();
    }
    return $objResponse;
}

function KickPlayer($check, $sid, $num) {
    $objResponse = new xajaxResponse();
    global $userbank, $username;
    $sid = (int)$sid;

    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
    {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Попытка взлома", $username . " пытался обработать кик игрока, не имея на это прав.");
        return $objResponse;
    }
    
    //get the server data
    $sdata = $GLOBALS['db']->GetRow("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE sid = '".$sid."';");
    
    //test if server is online
    if($test = @fsockopen($sdata['ip'], $sdata['port'], $errno, $errstr, 2)) {
        @fclose($test);
        require_once(INCLUDES_PATH . "/CServerControl.php");
        
        $r = new CServerControl();
        $r->Connect($sdata['ip'], $sdata['port']);

        if(!$r->AuthRcon($sdata['rcon'])) {
            $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET rcon = '' WHERE sid = '".$sid."' LIMIT 1;");        
            $objResponse->addAssign("srv_$num", "innerHTML", "<font color='red' size='1'>Ошибка Rcon пароля!</font>");
            $objResponse->addScript('set_counter(1);');
            return $objResponse;
        }
        $ret = $r->GetInfo();
        
        if(!$ret)
            $objResponse->addAssign("srvip_$num", "innerHTML", "<font size='1'><span title='".$sdata['ip'].":".$sdata['port']."'>".$ret['HostName']."</span></font>");
        
        require_once(INCLUDES_PATH . '/system-functions.php');
        if (kickClient($r, $check)) {
            $objResponse->addAssign("srv_$num", "innerHTML", "<font color='green' size='1'><b>Найден и кикнут с сервера.</b></font>");
            $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_bans` SET sid = '".(int) $sid."' WHERE authid = '".$check."' AND RemovedBy IS NULL;");
            $objResponse->addScript("set_counter('-1');");
        } else
            $objResponse->addAssign("srv_$num", "innerHTML", "<font size='1'>Не найден.</font>");
    } else
        $objResponse->addAssign("srv_$num", "innerHTML", "<font color='red' size='1'><i>Нет соединения.</i></font>");
    
    $objResponse->addScript('set_counter(1);');
    return $objResponse;
}
$servers = $GLOBALS['db']->Execute("SELECT ip, port, rcon FROM ".DB_PREFIX."_servers WHERE enabled = 1 ORDER BY modid, sid;");
$theme->assign('total', $servers->RecordCount());
$serverlinks = array();
$num = 0;
while(!$servers->EOF) {
    $info = array();
    $info['num'] = $num;
    $info['ip'] = $servers->fields["ip"];
    $info['port'] = $servers->fields["port"];
    array_push($serverlinks, $info);
    $num++;
    $servers->MoveNext();
}
$theme->assign('servers', $serverlinks);
$theme->assign('xajax_functions',  $xajax->printJavascript("../scripts", "xajax.js"));
$theme->assign('check', $_GET["check"]);// steamid or ip address

$theme->left_delimiter = "-{";
$theme->right_delimiter = "}-";
$theme->display('page_kickit.tpl');
$theme->left_delimiter = "{";
$theme->right_delimiter = "}";
?>
