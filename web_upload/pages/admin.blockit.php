<?php
// *************************************************************************
//  This file is part of SourceBans++.
//
//  Copyright (C) 2014-2016 Sarabveer Singh <me@sarabveer.me>
//
//  SourceBans++ is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, per version 3 of the License.
//
//  SourceBans++ is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with SourceBans++. If not, see <http://www.gnu.org/licenses/>.
//
//  This file is based off work covered by the following copyright(s):  
//
//   SourceBans 1.4.11
//   Copyright (C) 2007-2015 SourceBans Team - Part of GameConnect
//   Licensed under GNU GPL version 3, or later.
//   Page: <http://www.sourcebans.net/> - <https://github.com/GameConnect/sourcebansv1>
//
// *************************************************************************

include_once '../init.php';

if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
{
	echo "No Access";
	die();
}
require_once(INCLUDES_PATH . '/xajax.inc.php');
$xajax = new xajax();
//$xajax->debugOn();
$xajax->setRequestURI("./admin.blockit.php");
$xajax->registerFunction("BlockPlayer");
$xajax->registerFunction("LoadServers2");
$xajax->processRequests();
$username = $userbank->GetProperty("user");

function LoadServers2($check, $type, $length) {
	$objResponse = new xajaxResponse();
	global $userbank, $username;
	if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
	{
		$objResponse->redirect("index.php?p=login&m=no_access", 0);
		$log = new CSystemLog("w", "Попытка взлома", $username . " пытался использовать блокировку, не имея на это прав.");
		return $objResponse;
	}
	$id = 0;
	$servers = $GLOBALS['db']->Execute("SELECT sid, rcon FROM ".DB_PREFIX."_servers WHERE enabled = 1 ORDER BY modid, sid;");
	while(!$servers->EOF) {
		//search for player
		if(!empty($servers->fields["rcon"])) {
			$text = '<font size="1">Поиск...</font>';
			$objResponse->addScript("xajax_BlockPlayer('".$check."', '".$servers->fields["sid"]."', '".$id."', '".$type."', '".$length."');");
		}
		else { //no rcon = servercount + 1 ;)
			$text = '<font size="1">Нет RCON пароля.</font>';
			$objResponse->addScript('set_counter(1);');
		}		
		$objResponse->addAssign("srv_".$id, "innerHTML", $text);
		$id++;
		$servers->MoveNext();
	}
	return $objResponse;
}

function BlockPlayer($check, $sid, $num, $type, $length) {
	$objResponse = new xajaxResponse();
	global $userbank, $username;
	$sid = (int)$sid;
	$length = (int)$length;

	if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN))
	{
		$objResponse->redirect("index.php?p=login&m=no_access", 0);
		$log = new CSystemLog("w", "Попытка взлома", $username . " пытался обработать блокировку игрока, не имея на это прав.");
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
			$objResponse->addAssign("srv_$num", "innerHTML", "<font color='red' size='1'>Неправильный RCON пароль, измените!</font>");
			$objResponse->addScript('set_counter(1);');
			return $objResponse;
		}
		$ret = $r->GetInfo();
		if(!$ret)
			$objResponse->addAssign("srvip_$num", "innerHTML", "<font size='1'><span title='".$sdata['ip'].":".$sdata['port']."'>".$ret['HostName']."</span></font>");

		$response = null;
		$gothim = false;
		if ($GLOBALS['config']['feature.old_serverside'] == "1") {
			$ret = $r->SendCommand("status");
        	$search = preg_match_all(STATUS_PARSE, $ret, $matches, PREG_PATTERN_ORDER);
	        //search for the steamid on the server
    	    foreach ($matches[3] AS $match) {
    	        if (substr($match, 8) == substr($check, 8)) {
	                $gothim = true;
    	            $kick   = $r->SendCommand("sc_fw_block " . $type . " " . $length . " " . $match);
    	        }
    	    }
		} else
			$gothim = (strpos($r->SendCommand("ma_wb_block ".$type." ".$length." ".$check), "ok") !== FALSE);

		if ($gothim) {
            $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_comms` SET sid = '".$sid."' WHERE authid = '".$check."' AND RemovedBy IS NULL;");
			$requri = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], "pages/admin.blockit.php"));
			$objResponse->addAssign("srv_$num", "innerHTML", "<font color='green' size='1'><b>Игрок найден и заблокирован!</b></font>");
			$objResponse->addScript("set_counter('-1');");
			return $objResponse;
        }

		if(!$gothim) {
			$objResponse->addAssign("srv_$num", "innerHTML", "<font size='1'>Игрок не найден.</font>");
			$objResponse->addScript('set_counter(1);');
			return $objResponse;
		}
	} else {
		$objResponse->addAssign("srv_$num", "innerHTML", "<font color='red' size='1'><i>Не могу соединиться с сервером.</i></font>");
		$objResponse->addScript('set_counter(1);');
		return $objResponse;
	}
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
$theme->assign('type', $_GET['type']);
$theme->assign('length', $_GET['length']);

$theme->left_delimiter = "-{";
$theme->right_delimiter = "}-";
$theme->display('page_blockit.tpl');
$theme->left_delimiter = "{";
$theme->right_delimiter = "}";
?>
