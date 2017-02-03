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
//   SourceComms 0.9.266
//   Copyright (C) 2013-2014 Alexandr Duplishchev
//   Licensed under GNU GPL version 3, or later.
//   Page: <https://forums.alliedmods.net/showthread.php?p=1883705> - <https://github.com/d-ai/SourceComms>
//
// *************************************************************************
global $theme;
if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
$BansPerPage = SB_BANS_PER_PAGE;
$servers = array();
global $userbank;
function setPostKey()
{
	if(isset($_SERVER['REMOTE_IP']))
		$_SESSION['banlist_postkey'] = md5($_SERVER['REMOTE_IP'].time().rand(0,100000));
	else
		$_SESSION['banlist_postkey'] = md5(time().rand(0,100000));
}
if (!isset($_SESSION['banlist_postkey']) || strlen($_SESSION['banlist_postkey']) < 4)
	setPostKey();

$page = 1;
$pagelink = "";

PruneComms();

if (isset($_GET['page']) && $_GET['page'] > 0)
{
	$page = intval($_GET['page']);
	$pagelink = "&page=".$page;
}

if (isset($_GET['a']) && $_GET['a'] == "ungag" && isset($_GET['id']))
{
	if ($_GET['key'] != $_SESSION['banlist_postkey'])
		die("Возможная попытка взлома (Несоответствие URL-ключа)");
	//we have a multiple unban asking
	$bid = intval($_GET['id']);
	$res = $GLOBALS['db']->Execute("SELECT a.aid, a.gid FROM `".DB_PREFIX."_comms` c INNER JOIN ".DB_PREFIX."_admins a ON a.aid = c.aid WHERE bid = '".$bid."' AND c.type = 2;");
	if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_UNBAN) &&
			!($userbank->HasAccess(ADMIN_UNBAN_OWN_BANS) && $res->fields['aid'] == $userbank->GetAid()) &&
			!($userbank->HasAccess(ADMIN_UNBAN_GROUP_BANS) && $res->fields['gid'] == $userbank->GetProperty('gid')))
		{
			die("У вас нет доступа к этому");
		}

	$row = $GLOBALS['db']->GetRow("SELECT b.authid, b.name, b.created, b.sid, UNIX_TIMESTAMP() as now
										FROM ".DB_PREFIX."_comms b
										LEFT JOIN ".DB_PREFIX."_servers s ON s.sid = b.sid
										WHERE b.bid = ? AND b.RemoveType IS NULL AND b.type = 2 AND (b.length = '0' OR b.ends > UNIX_TIMESTAMP())",array($bid));
	if(empty($row) || !$row)
	{
		echo "<script>setTimeout('ShowBox(\"Игроку не включен чат\", \"Игроку не был включен чат. Либо он был включен ранее, либо некорректный блок чата.<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"red\", \"index.php?p=commslist$pagelink\", false);', 1350);</script>";
		PageDie();
	}

	$unbanReason = htmlspecialchars(trim($_GET['ureason']));
	$ins = $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_comms` SET
										`RemovedBy` = ?,
										`RemoveType` = 'U',
										`RemovedOn` = UNIX_TIMESTAMP(),
										`ureason` = ?
										WHERE `bid` = ?;",
										array( $userbank->GetAid(), $unbanReason, $bid));

	$blocked = $GLOBALS['db']->GetAll("SELECT sid FROM `".DB_PREFIX."_servers` WHERE `enabled`=1");
	foreach($blocked as $tempban)
	{
		SendRconSilent(("sc_fw_ungag " . $row['authid']), $tempban['sid']);
	}

	if($res){
		echo "<script>setTimeout('ShowBox(\"Включение чата\", \"Игроку <b>".StripQuotes($row['name'])."</b> (<b>" . $row['authid'] . "</b>) был включен чат.<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"green\", \"index.php?p=commslist$pagelink\", false);', 1350);</script>";
		$log = new CSystemLog("m", "Игроку включен чат", "'".StripQuotes($row['name'])."' (" . $row['authid'] . ") has been ungagged");
	}else{
		echo "<script>setTimeout('ShowBox(\"Чат не включен\", \"Возникла ошибка включения чата игроку <b>".StripQuotes($row['name'])."</b><br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"red\", \"index.php?p=commsist$pagelink\", false);', 1350);</script>";
	}
}
else if(isset($_GET['a']) && $_GET['a'] == "unmute" && isset($_GET['id']))
{
	if ($_GET['key'] != $_SESSION['banlist_postkey'])
		die("Possible Попытка взлома (URL Key mismatch)");
	//we have a multiple unban asking
	$bid = intval($_GET['id']);
	$res = $GLOBALS['db']->Execute("SELECT a.aid, a.gid FROM `".DB_PREFIX."_comms` c INNER JOIN ".DB_PREFIX."_admins a ON a.aid = c.aid WHERE bid = '".$bid."' AND c.type = 1;");
	if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_UNBAN) &&
			!($userbank->HasAccess(ADMIN_UNBAN_OWN_BANS) && $res->fields['aid'] == $userbank->GetAid()) &&
			!($userbank->HasAccess(ADMIN_UNBAN_GROUP_BANS) && $res->fields['gid'] == $userbank->GetProperty('gid')))
		{
			die("У вас нет доступа для этой операции");
		}

	$row = $GLOBALS['db']->GetRow("SELECT b.authid, b.name, b.created, b.sid, UNIX_TIMESTAMP() as now
										FROM ".DB_PREFIX."_comms b
										LEFT JOIN ".DB_PREFIX."_servers s ON s.sid = b.sid
										WHERE b.bid = ? AND b.RemoveType IS NULL AND b.type = 1 AND (b.length = '0' OR b.ends > UNIX_TIMESTAMP())",array($bid));
	if(empty($row) || !$row)
	{
		echo "<script>setTimeout('ShowBox(\"Игроку не включен микро\", \"Игроку не был включен микрофон. Он либо был включен ранее, либо некорректный блок микрофона.<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"red\", \"index.php?p=commslist$pagelink\", false);', 1350);</script>";
		PageDie();
	}

	$unbanReason = htmlspecialchars(trim($_GET['ureason']));
	$ins = $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_comms` SET
										`RemovedBy` = ?,
										`RemoveType` = 'U',
										`RemovedOn` = UNIX_TIMESTAMP(),
										`ureason` = ?
										WHERE `bid` = ?;",
										array( $userbank->GetAid(), $unbanReason, $bid));

	$blocked = $GLOBALS['db']->GetAll("SELECT sid FROM `".DB_PREFIX."_servers` WHERE `enabled`=1");
	foreach($blocked as $tempban)
	{
		SendRconSilent(("sc_fw_unmute " . $row['authid']), $tempban['sid']);
	}

	if($res){
		echo "<script>setTimeout('ShowBox(\"Включение микрофона\", \"Игроку <b>".StripQuotes($row['name'])."</b> <b>(" . $row['authid'] . ")</b> был включен микрофон.<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"green\", \"index.php?p=commslist$pagelink\", false);', 1350);</script>";
		$log = new CSystemLog("m", "Игроку влючен микро", "'".StripQuotes($row['name'])."' (" . $row['authid'] . ") has been unmuted");
	}else{
		echo "<script>setTimeout('ShowBox(\"Микрофон не включен\", \"Возникла ошибка включения микрофона <b>".StripQuotes($row['name'])."</b><br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"red\", \"index.php?p=commsist$pagelink\", false);', 1350);</script>";
	}
}
else if(isset($_GET['a']) && $_GET['a'] == "delete")
{
	if ($_GET['key'] != $_SESSION['banlist_postkey'])
		die("Возможная попытка взлома (Несоответствие URL-ключа)");

	if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_BAN))
	{
		echo "<script>setTimeout(\"ShowBox('Ошибка', 'У вас нет доступа к этой операции.<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>', 'red', 'index.php?p=commslist$pagelink');\", 1350);</script>";
		PageDie();
	}

	$bid = intval($_GET['id']);

	$steam = $GLOBALS['db']->GetRow("SELECT name, authid, ends, length, RemoveType, type, UNIX_TIMESTAMP() AS now
									FROM ".DB_PREFIX."_comms WHERE bid=?",array($bid));
	$end = (int)$steam['ends'];
	$length = (int)$steam['length'];
	$now = (int)$steam['now'];

	$cmd = "";

	switch($steam['type'])
	{
		case 1:
			$cmd = "sc_fw_unmute";
			break;
		case 2:
			$cmd = "sc_fw_ungag";
			break;
		default:
			break;
	}

	$res = $GLOBALS['db']->Execute("DELETE FROM `".DB_PREFIX."_comms` WHERE `bid` = ?",	array( $bid ));

	if(empty($steam['RemoveType']) && ($length == 0 || $end > $now))
	{
		$blocked = $GLOBALS['db']->GetAll("SELECT sid FROM `".DB_PREFIX."_servers` WHERE `enabled`=1");
		foreach($blocked as $tempban)
			{
				SendRconSilent(($cmd . " " . $steam['authid']), $tempban['sid']);
			}
	}

	if($res)
	{
		echo "<script>setTimeout('ShowBox(\"Блокировка удалена\", \"Блокировка игрока <b>" .StripQuotes($steam['name']). "</b> (<b>".$steam['authid']."</b>) была удалена из SourceBans. <br><br> <font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"green\", \"index.php?p=commslist$pagelink\", false);', 1350);</script>";
		$log = new CSystemLog("m", "Блокировка удалена", "Блокировка игрока '".StripQuotes($steam['name'])."' (" . $steam['authid'] . ") была удалена.");
	}else{
		echo "<script>setTimeout('ShowBox(\"Блокировка НЕ удалена\", \"Блокировка игрока <b>".StripQuotes($steam['name'])."</b> не удалена из-за ошибки. <br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"red\", \"index.php?p=commslist$pagelink\", false);', 1350);</script>";
	}
}

// LIMIT для SQL запроса - по номеру страницы и числу банов на страницу
$BansStart = intval(($page-1) * $BansPerPage);
$BansEnd = intval($BansStart+$BansPerPage);

// hide inactive bans feature
if(isset($_GET["hideinactive"]) && $_GET["hideinactive"] == "true") {// hide
	$_SESSION["hideinactive"] = true;
	//ShowBox('Hide inactive bans', 'Inactive bans will be hidden from the banlist.', 'green', 'index.php?p=banlist', true);
} elseif(isset($_GET["hideinactive"]) && $_GET["hideinactive"] == "false") { // show
	unset($_SESSION["hideinactive"]);
	//ShowBox('Show inactive bans', 'Inactive bans will be shown in the banlist.', 'green', 'index.php?p=banlist', true);
}
if(isset($_SESSION["hideinactive"])) {
	$hidetext = "Показать";
	$hideinactive = " AND RemoveType IS NULL";
	$hideinactiven = " WHERE RemoveType IS NULL";
} else {
	$hidetext = "Скрыть";
	$hideinactive = "";
	$hideinactiven = "";
}


if (isset($_GET['searchText']))
{
	$search = '%'.trim($_GET['searchText']).'%';

	$res = $GLOBALS['db']->Execute(
		"SELECT bid ban_id, CO.type, CO.authid, CO.name player_name, created ban_created, ends ban_ends, length ban_length, reason ban_reason, CO.ureason unban_reason, CO.aid, AD.gid AS gid, adminIp, CO.sid ban_server, RemovedOn, RemovedBy, RemoveType row_type,
		SE.ip server_ip, AD.user admin_name, MO.icon as mod_icon,
		CAST(MID(CO.authid, 9, 1) AS UNSIGNED) + CAST('76561197960265728' AS UNSIGNED) + CAST(MID(CO.authid, 11, 10) * 2 AS UNSIGNED) AS community_id,
		(SELECT count(*) FROM ".DB_PREFIX."_comms as BH WHERE (BH.authid = CO.authid AND BH.authid != '' AND BH.authid IS NOT NULL AND BH.type = 1)) as mute_count,
		(SELECT count(*) FROM ".DB_PREFIX."_comms as BH WHERE (BH.authid = CO.authid AND BH.authid != '' AND BH.authid IS NOT NULL AND BH.type = 2)) as gag_count,
		UNIX_TIMESTAMP() as c_time
		FROM ".DB_PREFIX."_comms AS CO FORCE INDEX (created)
		LEFT JOIN ".DB_PREFIX."_servers AS SE ON SE.sid = CO.sid
		LEFT JOIN ".DB_PREFIX."_mods AS MO on SE.modid = MO.mid
		LEFT JOIN ".DB_PREFIX."_admins AS AD ON CO.aid = AD.aid
      	WHERE CO.authid LIKE ? or CO.name LIKE ? or CO.reason LIKE ?".$hideinactive."
   		ORDER BY CO.created DESC LIMIT ?,?",
   		array($search,$search,$search,intval($BansStart),intval($BansPerPage)));


	$res_count = $GLOBALS['db']->Execute("SELECT count(CO.bid) FROM ".DB_PREFIX."_comms AS CO WHERE CO.authid LIKE ? OR CO.name LIKE ? OR CO.reason LIKE ?" . $hideinactive
										,array($search,$search,$search));
$searchlink = "&searchText=".$_GET["searchText"];
}
elseif(!isset($_GET['advSearch']))
{
	$res = $GLOBALS['db']->Execute(
	"SELECT bid ban_id, CO.type, CO.authid, CO.name player_name, created ban_created, ends ban_ends, length ban_length, reason ban_reason, CO.ureason unban_reason, CO.aid, AD.gid AS gid, adminIp, CO.sid ban_server, RemovedOn, RemovedBy, RemoveType row_type,
		SE.ip server_ip, AD.user admin_name, MO.icon as mod_icon,
		CAST(MID(CO.authid, 9, 1) AS UNSIGNED) + CAST('76561197960265728' AS UNSIGNED) + CAST(MID(CO.authid, 11, 10) * 2 AS UNSIGNED) AS community_id,
		(SELECT count(*) FROM ".DB_PREFIX."_comms as BH WHERE (BH.authid = CO.authid AND BH.authid != '' AND BH.authid IS NOT NULL AND BH.type = 1)) as mute_count,
		(SELECT count(*) FROM ".DB_PREFIX."_comms as BH WHERE (BH.authid = CO.authid AND BH.authid != '' AND BH.authid IS NOT NULL AND BH.type = 2)) as gag_count,
		UNIX_TIMESTAMP() as c_time
		FROM ".DB_PREFIX."_comms AS CO FORCE INDEX (created)
		LEFT JOIN ".DB_PREFIX."_servers AS SE ON SE.sid = CO.sid
		LEFT JOIN ".DB_PREFIX."_mods AS MO on SE.modid = MO.mid
		LEFT JOIN ".DB_PREFIX."_admins AS AD ON CO.aid = AD.aid
		".$hideinactiven."
		ORDER BY created DESC
		LIMIT ?,?",
	array(intval($BansStart),intval($BansPerPage)));

	$res_count = $GLOBALS['db']->Execute("SELECT count(bid) FROM ".DB_PREFIX."_comms".$hideinactiven);
	$searchlink = "";
}

$advcrit = array();
if(isset($_GET['advSearch']))
{
	$value = trim($_GET['advSearch']);
	$type = $_GET['advType'];
	switch($type)
	{
		case "name":
			$where = "WHERE CO.name LIKE ?";
			$advcrit = array("%$value%");
		break;
		case "banid":
			$where = "WHERE CO.bid = ?";
			$advcrit = array($value);
		break;
		case "steamid":
			$where = "WHERE CO.authid = ?";
			$advcrit = array($value);
		break;
		case "steam":
			$where = "WHERE CO.authid LIKE ?";
			$advcrit = array("%$value%");
		break;
		case "reason":
			$where = "WHERE CO.reason LIKE ?";
			$advcrit = array("%$value%");
		break;
		case "date":
			$date = explode(",", $value);
			$time = mktime(0,0,0,$date[1],$date[0],$date[2]);
			$time2 = mktime(23,59,59,$date[1],$date[0],$date[2]);
			$where = "WHERE CO.created > ? AND CO.created < ?";
			$advcrit = array($time, $time2);
		break;
		case "length":
			$len = explode(",", $value);
			$length_type = $len[0];
			$length = $len[1]*60;
			$where = "WHERE CO.length ";
			switch($length_type) {
				case "e":
					$where .= "=";
				break;
				case "h":
					$where .= ">";
				break;
				case "l":
					$where .= "<";
				break;
				case "eh":
					$where .= ">=";
				break;
				case "el":
					$where .= "<=";
				break;
			}
			$where .= " ?";
			$advcrit = array($length);
		break;
		case "btype":
			$where = "WHERE CO.type = ?";
			$advcrit = array($value);
		break;
		case "admin":
            if($GLOBALS['config']['banlist.hideadminname']&&!$userbank->is_admin())
			{
                $where = "";
				$advcrit = array();
			}
            else {
                $where = "WHERE CO.aid=?";
                $advcrit = array($value);
            }
		break;
		case "where_banned":
			$where = "WHERE CO.sid=?";
			$advcrit = array($value);
		break;
		case "bid":
			$where = "WHERE CO.bid = ?";
			$advcrit = array($value);
		break;
		case "comment":
			if($userbank->is_admin())
			{
				$where = "WHERE CM.type ='C' AND CM.commenttxt LIKE ?";
				$advcrit = array("%$value%");
			}
			else
			{
                $where = "";
				$advcrit = array();
			}
		break;
		default:
			$where = "";
			$_GET['advType'] = "";
			$_GET['advSearch'] = "";
			$advcrit = array();
		break;
	}

		$res = $GLOBALS['db']->Execute(
			"SELECT CO.bid ban_id, CO.type, CO.authid, CO.name player_name, created ban_created, ends ban_ends, length ban_length, reason ban_reason, CO.ureason unban_reason, CO.aid, AD.gid AS gid, adminIp, CO.sid ban_server, RemovedOn, RemovedBy, RemoveType row_type,
			SE.ip server_ip, AD.user admin_name, MO.icon as mod_icon,
			CAST(MID(CO.authid, 9, 1) AS UNSIGNED) + CAST('76561197960265728' AS UNSIGNED) + CAST(MID(CO.authid, 11, 10) * 2 AS UNSIGNED) AS community_id,
			(SELECT count(*) FROM ".DB_PREFIX."_comms as BH WHERE (BH.authid = CO.authid AND BH.authid != '' AND BH.authid IS NOT NULL AND BH.type = 1)) as mute_count,
			(SELECT count(*) FROM ".DB_PREFIX."_comms as BH WHERE (BH.authid = CO.authid AND BH.authid != '' AND BH.authid IS NOT NULL AND BH.type = 2)) as gag_count,
			UNIX_TIMESTAMP() as c_time
			FROM ".DB_PREFIX."_comms AS CO FORCE INDEX (created)
			LEFT JOIN ".DB_PREFIX."_servers AS SE ON SE.sid = CO.sid
			LEFT JOIN ".DB_PREFIX."_mods AS MO on SE.modid = MO.mid
			LEFT JOIN ".DB_PREFIX."_admins AS AD ON CO.aid = AD.aid
  			".($type=="comment"&&$userbank->is_admin()?"LEFT JOIN ".DB_PREFIX."_comments AS CM ON CO.bid = CM.bid":"")."
      ".$where.$hideinactive."
   ORDER BY CO.created DESC
   LIMIT ?,?", array_merge($advcrit, array(intval($BansStart),intval($BansPerPage))));

	$res_count = $GLOBALS['db']->Execute("SELECT count(CO.bid) FROM ".DB_PREFIX."_comms AS CO
										  ".($type=="comment"&&$userbank->is_admin()?"LEFT JOIN ".DB_PREFIX."_comments AS CM ON CO.bid = CM.bid":"")." ".$where.$hideinactive, $advcrit);
	$searchlink = "&advSearch=".$_GET['advSearch']."&advType=".$_GET['advType'];
}

$BanCount = $res_count->fields[0];
if ($BansEnd > $BanCount) $BansEnd = $BanCount;
if (!$res)
{
	echo "No Blocks Found.";
	PageDie();
}

$view_comments = false;
$bans = array();
while (!$res->EOF)
{
	$data = array();

	$data['ban_id'] = $res->fields['ban_id'];
	$data['type'] = $res->fields['type'];
	$data['c_time'] = $res->fields['c_time'];

	$mute_count = (int)$res->fields['mute_count'];
	$gag_count = (int)$res->fields['gag_count'];
	$history_count = $mute_count + $gag_count;

	$delimiter = "";

	// заюзаем иконку страны под отображение TYPE_MUTE or TYPE_GAG
	switch((int)$data['type'])
	{
		case 1:
			$data['type_icon'] = '<img src="images/type_v.png" alt="Микрофон" border="0" align="absmiddle" />';
			$mute_count = $mute_count - 1;
			break;
		case 2:
			$data['type_icon'] = '<img src="images/type_c.png" alt="Чат" border="0" align="absmiddle" />';
			$gag_count = $gag_count - 1;
			break;
		case 3:
			$data['type_icon'] = '<img src="images/type_silence.png" alt="Микрофон и чат" border=0 align="absmiddle" />';
			$gag_count -= 1;
			$mute_count -= 1;
			break;
		default:
			$data['type_icon'] = '<img src="images/country/zz.gif" alt="Неизвестный тип блока" border="0" align="absmiddle" />';
			break;
	}

	//$data['ban_date'] = SBDate($dateformat,$res->fields['ban_created']);
	$data['ban_date'] = SBDate($GLOBALS['config']['config.dateformat'],$res->fields['ban_created']);
	$data['ban_date_info'] = SBDate($GLOBALS['config']['config.dateformat_ver2'],$res->fields['ban_created']);
	$data['player'] = addslashes($res->fields['player_name']);
	$data['steamid'] = $res->fields['authid'];
	$data['communityid'] = $res->fields['community_id'];
	$steam2id = $data['steamid'];
	$steam3parts = explode(':', $steam2id);
	$data['steamid3'] = '[U:1:' . ($steam3parts[2] * 2 + $steam3parts[1]) . ']';

	if(isset($GLOBALS['config']['banlist.hideadminname']) && $GLOBALS['config']['banlist.hideadminname'] == "1" && !$userbank->is_admin())
		$data['admin'] = false;
	else
		$data['admin'] = stripslashes($res->fields['admin_name']);
	$data['reason'] = stripslashes($res->fields['ban_reason']);

	if ($res->fields['ban_length'] > 0)
	{
		$data['ban_length'] = SecondsToString(intval($res->fields['ban_length']));
		$data['expires'] = SBDate($dateformat,$res->fields['ban_ends']);
	}
	else if ($res->fields['ban_length'] == 0)
	{
		$data['ban_length'] = 'Навсегда';
		$data['expires'] = 'Никогда';
	}
	else
	{
		$data['ban_length'] = 'Сессия';
		$data['expires'] = 'н/д';
	}

	// Что за тип разбана - D? Я такой не видел, но оставлю так и быть.. for feature use...
	if($res->fields['row_type'] == 'D' || $res->fields['row_type'] == 'U' || $res->fields['row_type'] == 'E' || ($res->fields['ban_length'] && $res->fields['ban_ends'] < $data['c_time']))
	{
		$data['unbanned'] = true;
		$data['class'] = "success c-white";

		if($res->fields['row_type'] == "D")
			$data['ub_reason'] = "Удален";
		elseif($res->fields['row_type'] == "U")
			$data['ub_reason'] = "Снят";
		else{
			$data['ub_reason'] = "Истек";
			$data['class'] = "active";
		}
		
		$data['ureason'] = stripslashes($res->fields['unban_reason']);

		$removedby = $GLOBALS['db']->GetRow("SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = '".$res->fields['RemovedBy']."'");
        $data['removedby'] = "";
        if(isset($removedby[0]))
            $data['removedby'] = $removedby[0];
	}
	else if($data['ban_length'] == 'Навсегда')
	{
		$data['class'] = "listtable_1_permanent";
	}
	else
	{
		$data['unbanned'] = false;
		$data['class'] = "listtable_1_banned";
		$data['ub_reason'] = "";
	}

	$data['layer_id'] = 'layer_'.$res->fields['ban_id'];
	// Запрос текущего статуса игрока для рисования ссылки на мьют или гаг
	$alrdybnd = $GLOBALS['db']->Execute("SELECT count(bid) as count FROM `".DB_PREFIX."_comms` WHERE authid = '".$data['steamid']."' AND RemovedBy IS NULL AND type = '".$data['type']."' AND (length = 0 OR ends > UNIX_TIMESTAMP());");
	if($alrdybnd->fields['count']==0)
	{
		switch($data['type'])
		{
		case 1:
			$data['reban_link'] = CreateLinkR('Выдать мут',"index.php?p=admin&c=comms".$pagelink."&rebanid=".$res->fields['ban_id']."&key=".$_SESSION['banlist_postkey']."#^0");
			break;
		case 2:
			$data['reban_link'] = CreateLinkR('Выдать гаг',"index.php?p=admin&c=comms".$pagelink."&rebanid=".$res->fields['ban_id']."&key=".$_SESSION['banlist_postkey']."#^0");
			break;
		default:
			break;
		}
	}
	else
		$data['reban_link'] = false;


	$data['edit_link'] = CreateLinkR('Редактировать',"index.php?p=admin&c=comms&o=edit".$pagelink."&id=".$res->fields['ban_id']."&key=".$_SESSION['banlist_postkey']);

	switch($data['type'])
	{
		case 2:
			$data['unban_link'] = CreateLinkR('РазГаг',"#","", "_self", false, "UnGag('".$res->fields['ban_id']."', '".$_SESSION['banlist_postkey']."', '".$pagelink."', '".StripQuotes($data['player'])."', 1);return false;");
			break;
		case 1:
			$data['unban_link'] = CreateLinkR('РазМут',"#","", "_self", false, "UnMute('".$res->fields['ban_id']."', '".$_SESSION['banlist_postkey']."', '".$pagelink."', '".StripQuotes($data['player'])."', 1);return false;");
			break;
		default:
			break;
	}

	$data['delete_link'] = CreateLinkR('Удалить',"#","", "_self", false, "RemoveBlock('".$res->fields['ban_id']."', '".$_SESSION['banlist_postkey']."', '".$pagelink."', '".StripQuotes($data['player'])."', 0);return false;");

	$data['server_id'] = $res->fields['ban_server'];

	if(empty($res->fields['mod_icon']))
	{
		$modicon = "web.png";
	}
	else
	{
		$modicon = $res->fields['mod_icon'];
	}

	//$data['mod_icon'] = '<img src="images/games/' .$modicon . '" alt="MOD" border="0" align="absmiddle" />&nbsp;' . $data['type_icon'];
	$data['mod_icon'] = '<img src="images/games/' .$modicon . '" alt="MOD" border="0" align="absmiddle" />&nbsp;';
	
	$data['type_icon_p'] = $data['type_icon'];
	
    if($history_count > 1)
        $data['prevoff_link'] = $history_count . " " . CreateLinkR("(Поиск)","index.php?p=commslist&searchText=" .$data['steamid']. "&Submit");
    else
        $data['prevoff_link'] = "Нет предыдущих блокировок";

    $mutes = "";
    $gags = "";
    if($mute_count > 0)
    {
    	$mutes = $mute_count . '&thinsp;<img src="images/type_v.png" alt="Another mutes" border="0" align="absmiddle" />';
    	if ($gag_count > 0)
    		$mutes = $mutes . "&ensp;";
    }
    if($gag_count > 0)
    	$gags = $gag_count . '&thinsp;<img src="images/type_c.png" alt="Another gags" border="0" align="absmiddle" />';

	$data['server_id'] = $res->fields['ban_server'];

	//COMMENT STUFF
	//-----------------------------------
	if($userbank->is_admin()) {
		$view_comments = true;
		$commentres = $GLOBALS['db']->Execute("SELECT cid, aid, commenttxt, added, edittime,
											(SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = C.aid) AS comname,
											(SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = C.editaid) AS editname
											FROM `".DB_PREFIX."_comments` AS C
											WHERE C.type = 'C' AND bid = '".$data['ban_id']."' ORDER BY added desc");

		if($commentres->RecordCount()>0) {
			if ($mute_count > 0 || $gag_count > 0)
				$delimiter = "&ensp;";
			$comment = array();
			$morecom = 0;
			while(!$commentres->EOF) {
				$cdata = array();
				$cdata['morecom'] = ($morecom==1?true:false);
				if($commentres->fields['aid'] == $userbank->GetAid() || $userbank->HasAccess(ADMIN_OWNER)) {
					//$cdata['editcomlink'] = CreateLinkR('<img src=\'images/edit.gif\' border=\'0\' alt=\'\' style=\'vertical-align:middle\' />','index.php?p=commslist&comment='.$data['ban_id'].'&ctype=C&cid='.$commentres->fields['cid'].$pagelink,'Edit Comment');
					$cdata['editcomlink'] = CreateLinkR('Редактировать','index.php?p=commslist&comment='.$data['ban_id'].'&ctype=C&cid='.$commentres->fields['cid'].$pagelink);
					if($userbank->HasAccess(ADMIN_OWNER)) {
						$cdata['delcomlink'] = "<a href=\"#\" class=\"tip\" target=\"_self\" onclick=\"RemoveComment(".$commentres->fields['cid'].",'C',".(isset($_GET["page"])?$_GET["page"]:-1).");\">Удалить</a>";
					}
				}
				else {
					$cdata['editcomlink'] = "";
					$cdata['delcomlink'] = "";
				}

				$cdata['comname'] = $commentres->fields['comname'];
				$cdata['added'] = SBDate($dateformat, $commentres->fields['added']);
				$cdata['commenttxt'] = RemoveCode($commentres->fields['commenttxt']);
				$cdata['commenttxt'] = str_replace("\n", "<br />", $cdata['commenttxt']);
				// Parse links and wrap them in a <a href=""></a> tag to be easily clickable
				$cdata['commenttxt'] = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $cdata['commenttxt']);

				if(!empty($commentres->fields['edittime'])) {
					$cdata['edittime'] = SBDate($dateformat, $commentres->fields['edittime']);
					$cdata['editname'] = $commentres->fields['editname'];
				}
				else {
					$cdata['edittime'] = "";
					$cdata['editname'] = "";
				}

				$morecom = 1;
				array_push($comment,$cdata);
				$commentres->MoveNext();
			}
		}
		else
			$comment = "Нет";

		$data['commentdata'] = $comment;
	}

	$data['addcomment'] = CreateLinkR('<img src="images/details.gif" border="0" alt="" style="vertical-align:middle" /> Добавить комментарий','index.php?p=commslist&comment='.$data['ban_id'].'&ctype=C'.$pagelink);
	$data['addcomment_link'] = "index.php?p=commslist&comment=".$data['ban_id']."&ctype=C".$pagelink;
	//-----------------------------------
	$data['counts'] = $delimiter.$mutes.$gags;

	$data['ub_reason'] = (isset($data['ub_reason'])?$data['ub_reason']:"");
 	//$data['banlength'] = $data['ban_length'] . " " .  $data['ub_reason'];
 	$data['banlength'] = $data['ban_length'];
	$data['view_edit'] = ($userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ALL_BANS) || ($userbank->HasAccess(ADMIN_EDIT_OWN_BANS) && $res->fields['aid']==$userbank->GetAid()) || ($userbank->HasAccess(ADMIN_EDIT_GROUP_BANS) && $res->fields['gid']==$userbank->GetProperty('gid')));
    $data['view_unban'] = ($userbank->HasAccess(ADMIN_OWNER|ADMIN_UNBAN) || ($userbank->HasAccess(ADMIN_UNBAN_OWN_BANS) && $res->fields['aid']==$userbank->GetAid()) || ($userbank->HasAccess(ADMIN_UNBAN_GROUP_BANS) && $res->fields['gid']==$userbank->GetProperty('gid')));
    $data['view_delete'] = ($userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_BAN));
	array_push($bans,$data);
	$res->MoveNext();
}

if(isset($_GET['advSearch']))
	$advSearchString = "&advSearch=" . (isset($_GET['advSearch'])?$_GET['advSearch']:'') . "&advType=" . (isset($_GET['advType'])?$_GET['advType']:'');
else
	$advSearchString = '';

if ($page > 1)
{
	if(isset($_GET['c']) && $_GET['c'] == "comms")
		$prev = CreateLinkR('<i class="zmdi zmdi-chevron-right"></i>',"javascript:void(0);", "", "_self", false, $prev);
	else
		$prev = CreateLinkR('<i class="zmdi zmdi-chevron-left"></i>',"index.php?p=commslist&page=".($page-1).(isset($_GET['searchText']) > 0?"&searchText=".$_GET['searchText']:'' . $advSearchString));
}
else
{
	$prev = "";
}
if ($BansEnd < $BanCount)
{
	if(isset($_GET['c']) && $_GET['c'] == "comms")
	{
		if(!isset($nxt))
			$nxt = "";
			$next = CreateLinkR('<i class="zmdi zmdi-chevron-left"></i>',"javascript:void(0);", "", "_self", false, $nxt);
	}
	else
		$next = CreateLinkR('<i class="zmdi zmdi-chevron-right"></i>',"index.php?p=commslist&page=".($page+1).(isset($_GET['searchText']) ?"&searchText=".$_GET['searchText']:'' . $advSearchString));
}
else
	$next = "";

//=================[ Start Layout ]==================================
$ban_nav = '<ul class="pagination">';

if (strlen($prev) > 0)
{
	$ban_nav .= '<li>'.$prev.'</li>';
}
if (strlen($next) > 0)
{
	$ban_nav .= '<li>'.$next.'</li>';
}

$ban_nav .= '</ul>&nbsp;'; 


$pages = ceil($BanCount/$BansPerPage);
if($pages > 1) {
	$ban_nav_p = ' / Страница: <div class="select" style="display: inline-block;"><select class="form-control" onchange="changePage(this,\'C\',\''.(isset($_GET['advSearch']) ? $_GET['advSearch'] : '').'\',\''.(isset($_GET['advType']) ? $_GET['advType'] : '').'\');" style="display: inline-block;width: 50px;">';
	for($i=1;$i<=$pages;$i++)
	{
		if(isset($_GET["page"]) && $i == $_GET["page"]) {
			$ban_nav_p .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
			continue;
		}
		$ban_nav_p .= '<option value="' . $i . '">' . $i . '</option>';
	}
	$ban_nav_p .= '</select></div>&nbsp;';
}

//COMMENT STUFF
//----------------------------------------
if(isset($_GET["comment"])) {
	$theme->assign('commenttype', (isset($_GET["cid"])?"Редактировать":"Добавить"));
	if(isset($_GET["cid"])) {
		$ceditdata = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_comments WHERE cid = '".(int)$_GET["cid"]."'");
        $ctext = $ceditdata['commenttxt'];
		$cotherdataedit = " AND cid != '".(int)$_GET["cid"]."'";
	}
	else
    {
        $cotherdataedit = "";
        $ctext = "";
    }
	$cotherdata = $GLOBALS['db']->Execute("SELECT cid, aid, commenttxt, added, edittime,
											(SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = C.aid) AS comname,
											(SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = C.editaid) AS editname
											FROM `".DB_PREFIX."_comments` AS C
											WHERE type = ? AND bid = ?".$cotherdataedit." ORDER BY added desc", array($_GET["ctype"], $_GET["comment"]));

	$ocomments = array();
	while(!$cotherdata->EOF)
	{
		$coment = array();
		$coment['comname'] = $cotherdata->fields['comname'];
		$coment['added'] = SBDate($dateformat, $cotherdata->fields['added']);
		$coment['commenttxt'] = str_replace("\n", "<br />", $cotherdata->fields['commenttxt']);
		// Parse links and wrap them in a <a href=""></a> tag to be easily clickable
		$coment['commenttxt'] = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $coment['commenttxt']);
		if($cotherdata->fields['editname']!="") {
			$coment['edittime'] = SBDate($dateformat, $cotherdata->fields['edittime']);
			$coment['editname'] = $cotherdata->fields['editname'];
		}
		else {
			$coment['editname'] = "";
			$coment['edittime'] = "";
		}
		array_push($ocomments,$coment);
		$cotherdata->MoveNext();
	}

	$theme->assign('page', (isset($_GET["page"])?$_GET["page"]:-1));
	$theme->assign('othercomments', $ocomments);
	$theme->assign('commenttext', (isset($ctext)?$ctext:""));
	$theme->assign('ctype', $_GET["ctype"]);
	$theme->assign('cid', (isset($_GET["cid"])?$_GET["cid"]:""));
}
$theme->assign('view_comments',$view_comments);
$theme->assign('comment', (isset($_GET["comment"])?$_GET["comment"]:false));
//----------------------------------------

unset($_SESSION['CountryFetchHndl']);

$theme->assign('searchlink', $searchlink);
$theme->assign('hidetext', $hidetext);
$theme->assign('total_bans', $BanCount);
$theme->assign('active_bans', $BanCount);

$theme->assign('ban_nav', $ban_nav);
$theme->assign('ban_nav_p', $ban_nav_p);
$theme->assign('ban_list', $bans);
$theme->assign('admin_nick', $userbank->GetProperty("user"));

$theme->assign('admin_postkey', $_SESSION['banlist_postkey']);
$theme->assign('hideadminname', (isset($GLOBALS['config']['banlist.hideadminname']) && $GLOBALS['config']['banlist.hideadminname'] == "1" && !$userbank->is_admin()));
$theme->assign('general_unban', $userbank->HasAccess(ADMIN_OWNER|ADMIN_UNBAN|ADMIN_UNBAN_OWN_BANS|ADMIN_UNBAN_GROUP_BANS));
$theme->assign('can_delete', $userbank->HasAccess(ADMIN_DELETE_BAN));
$theme->assign('view_bans', ($userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ALL_BANS|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_GROUP_BANS|ADMIN_UNBAN|ADMIN_UNBAN_OWN_BANS|ADMIN_UNBAN_GROUP_BANS|ADMIN_DELETE_BAN)));
$theme->display('page_comms.tpl');
?>
