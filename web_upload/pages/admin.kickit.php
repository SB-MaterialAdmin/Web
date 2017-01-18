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
		
		// show hostname instead of the ip, but leave the ip in the title
		if(!$ret)
			$objResponse->addAssign("srvip_$num", "innerHTML", "<font size='1'><span title='".$sdata['ip'].":".$sdata['port']."'>".$ret['HostName']."</span></font>");
		
		$response = false;
		if ($GLOBALS['config']['feature.old_serverside'] == "1") {
			$search = preg_match_all(STATUS_PARSE, $ret, $matches, PREG_PATTERN_ORDER);
		    //search for the steamid on the server
		    if ((int) $type == 0) {
				foreach ($matches[3] AS $match) {
					if (getAccountId($match) == getAccountId($check)) {
						// gotcha!!! kick him!
						$response = true;
						$GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_bans` SET sid = '" . $sid . "' WHERE authid = '" . $check . "' AND RemovedBy IS NULL;");
						$requri = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], "pages/admin.kickit.php"));
		                
						if (strpos($match, "[U:") === 0)
							$kick = $r->sendCommand("kickid \"" . $match . "\" \"You have been banned by this server, check http://" . $_SERVER['HTTP_HOST'] . $requri . " for more info.\"");
						else
							$kick = $r->sendCommand("kickid " . $match . " \"You have been banned by this server, check http://" . $_SERVER['HTTP_HOST'] . $requri . " for more info.\"");
		                
						$objResponse->addAssign("srv_$num", "innerHTML", "<font color='green' size='1'><b><u>Player Found & Kicked!!!</u></b></font>");
						$objResponse->addScript("set_counter('-1');");
						return $objResponse;
					}
				}
        	} else if ((int) $type == 1) { // search for the ip on the server
            	$id = 0;
            	foreach ($matches[8] AS $match) {
                	$ip = explode(":", $match);
                	$ip = $ip[0];
                	if ($ip == $check) {
                    	$userid = $matches[1][$id];

                    	// gotcha!!! kick him!
                    	$response = true;
                    	$GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_bans` SET sid = '" . $sid . "' WHERE ip = '" . $check . "' AND RemovedBy IS NULL;");
	                    $requri = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], "pages/admin.kickit.php"));
                    	$kick   = $r->sendCommand("kickid " . $userid . " \"You have been banned by this server, check http://" . $_SERVER['HTTP_HOST'] . $requri . " for more info.\"");
	                    $objResponse->addAssign("srv_$num", "innerHTML", "<font color='green' size='1'><b><u>Player Found & Kicked!!!</u></b></font>");
    	                $objResponse->addScript("set_counter('-1');");
    	                return $objResponse;
    	            }
    	            $id++;
    	        }
    	    }
		} else
			$response = (strpos($r->SendCommand(sprintf("ma_wb_ban %s", $check)), "ok") !== FALSE);

		if ($response) {
			$objResponse->addAssign("srv_$num", "innerHTML", "<font color='green' size='1'><b>Найден и кикнут с сервера.</b></font>");
			$GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_bans` SET sid = '".(int) $sid."' WHERE authid = '".$check."' AND RemovedBy IS NULL;");
			$objResponse->addScript("set_counter('-1');");
			return $objResponse;
		}

		$objResponse->addAssign("srv_$num", "innerHTML", "<font size='1'>Не найден.</font>");
		$objResponse->addScript('set_counter(1);');
		return $objResponse;
	} else {
		$objResponse->addAssign("srv_$num", "innerHTML", "<font color='red' size='1'><i>Нет соединения.</i></font>");
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

$theme->left_delimiter = "-{";
$theme->right_delimiter = "}-";
$theme->display('page_kickit.tpl');
$theme->left_delimiter = "{";
$theme->right_delimiter = "}";
?>
