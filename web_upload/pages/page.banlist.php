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

PruneBans();

if (isset($_GET['page']) && $_GET['page'] > 0)
{
	$page = intval($_GET['page']);
	$pagelink = "&page=".$page;
}
if (version_compare($GLOBALS['db_version'], "5.6.0") >= 0 && version_compare($GLOBALS['db_version'], "10.0.0")<0)
{
  $GLOBALS['db']->Execute("set session optimizer_switch='block_nested_loop=off';");
}
if (isset($_GET['a']) && $_GET['a'] == "unban" && isset($_GET['id']))
{
	if ($_GET['key'] != $_SESSION['banlist_postkey'])
		die("Возможная попытка взлома (Несоответствие URL-ключа)");
	//we have a multiple unban asking
	if(isset($_GET['bulk']))
		$bids = explode(",",$_GET['id']);
	else
		$bids = array($_GET['id']);
	$ucount = 0;
	$fail = 0;
	foreach($bids AS $bid) {
		$bid = intval($bid);
		$res = $GLOBALS['db']->Execute("SELECT a.aid, a.gid FROM `".DB_PREFIX."_bans` b INNER JOIN ".DB_PREFIX."_admins a ON a.aid = b.aid WHERE bid = '".$bid."';");
		if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_UNBAN) &&
		    !($userbank->HasAccess(ADMIN_UNBAN_OWN_BANS) && $res->fields['aid'] == $userbank->GetAid()) &&
		    !($userbank->HasAccess(ADMIN_UNBAN_GROUP_BANS) && $res->fields['gid'] == $userbank->GetProperty('gid')))
		{
			$fail++;
			if(!isset($_GET['bulk']))
				die("У вас нет доступа к этому");
			continue;
		}

		$row = $GLOBALS['db']->GetRow("SELECT b.ip, b.authid, 
										b.name, b.created, b.sid, b.type, m.steam_universe, UNIX_TIMESTAMP() as now
										FROM ".DB_PREFIX."_bans b
										LEFT JOIN ".DB_PREFIX."_servers s ON s.sid = b.sid
										LEFT JOIN ".DB_PREFIX."_mods m ON m.mid = s.modid
										WHERE b.bid = ? AND (b.length = '0' OR b.ends > UNIX_TIMESTAMP()) AND b.RemoveType IS NULL",array($bid));
		if(empty($row) || !$row) {
			$fail++;
			if(!isset($_GET['bulk'])) {
				echo "<script>setTimeout('ShowBox(\"Игрок не разбанен\", \"Игрок не был разбанен. Либо был разбанен ранее, либо бан некорректен.<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"red\", \"index.php?p=banlist$pagelink'\", false);', 1350);</script>";
				PageDie();
			}
			continue;
		}
		$unbanReason = htmlspecialchars(trim($_GET['ureason']));
		$ins = $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_bans` SET
										`RemovedBy` = ?,
										`RemoveType` = 'U',
										`RemovedOn` = UNIX_TIMESTAMP(),
										`ureason` = ?
										WHERE `bid` = ?;",
										array( $userbank->GetAid(), $unbanReason, $bid));

		$protestsunban = $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_protests` SET archiv = '4' WHERE bid = '".$bid."';");

		$blocked = $GLOBALS['db']->GetAll("SELECT s.sid, m.steam_universe FROM `".DB_PREFIX."_banlog` bl INNER JOIN ".DB_PREFIX."_servers s ON s.sid = bl.sid INNER JOIN ".DB_PREFIX."_mods m ON m.mid = s.modid WHERE bl.bid=? AND (UNIX_TIMESTAMP() - bl.time <= 300)",array($bid));
		foreach($blocked as $tempban)
		{
			SendRconSilent(($row['type']==0?"removeid STEAM_" . $tempban['steam_universe'] . substr($row['authid'], 7):"removeip ".$row['ip']), $tempban['sid']);
		}
		if(((int)$row['now'] - (int)$row['created']) <= 300 && $row['sid'] != "0" && !in_array_dim($row['sid'], $blocked))
			SendRconSilent(($row['type']==0?"removeid STEAM_" . $row['steam_universe'] . substr($row['authid'], 7):"removeip ".$row['ip']), $row['sid']);

		if($res){
			if(!isset($_GET['bulk']))
				echo "<script>setTimeout('ShowBox(\"Игрок разбанен\", \"<b>".StripQuotes($row['name'])."</b> (<b>" . ($row['type']==0?$row['authid']:$row['ip']) . "</b>) был разбанен.<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"green\", \"index.php?p=banlist$pagelink\", false);', 1350);</script>";
			$log = new CSystemLog("m", "Игрок разбанен", "'".StripQuotes($row['name'])."' (" . ($row['type']==0?$row['authid']:$row['ip']) . ") был разбанен");
			$ucount++;
		}else{
			if(!isset($_GET['bulk']))
				echo "<script>setTimeout('ShowBox(\"Игрок не разбанен\", \"Произошла ошибка <b>".StripQuotes($row['name'])."</b><br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"red\", \"index.php?p=banlist$pagelink\", false);', 1350);</script>";
			$fail++;
		}
	}
	if(isset($_GET['bulk']))
		echo "<script>setTimeout('ShowBox(\"Игрок разбанен\", \"$ucount был разбанен.<br>$fail failed.<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"green\", \"index.php?p=banlist$pagelink\", false);', 1350);</script>";
}
else if(isset($_GET['a']) && $_GET['a'] == "delete")
{
	if ($_GET['key'] != $_SESSION['banlist_postkey'])
		die("Возможная попытка взлома (Несоответствие URL-ключа)");

	if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_BAN))
	{
		echo "<script>setTimeout('ShowBox(\"Ошибка\", \"У вас нет доступа к этому.<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"red\", \"index.php?p=banlist$pagelink\", false);', 1350);</script>";
		PageDie();
	}
	//we have a multiple ban delete asking
	if(isset($_GET['bulk']))
		$bids = explode(",",$_GET['id']);
	else
		$bids = array($_GET['id']);
	$dcount = 0;
	$fail = 0;
	foreach($bids AS $bid) {
		$bid = intval($bid);
		$demres = $GLOBALS['db']->Execute("SELECT filename FROM `".DB_PREFIX."_demos` WHERE `demid` = ?",
									array( $bid ));
		@unlink(SB_DEMOS."/".$demres->fields["filename"]);
		$blocked = $GLOBALS['db']->GetAll("SELECT s.sid, m.steam_universe FROM `".DB_PREFIX."_banlog` bl INNER JOIN ".DB_PREFIX."_servers s ON s.sid = bl.sid INNER JOIN ".DB_PREFIX."_mods m ON m.mid = s.modid WHERE bl.bid=? AND (UNIX_TIMESTAMP() - bl.time <= 300)",array($bid));
		$steam = $GLOBALS['db']->GetRow("SELECT b.name, b.authid, b.created, b.sid, b.RemoveType, b.ip, b.type, m.steam_universe, UNIX_TIMESTAMP() AS now
										FROM ".DB_PREFIX."_bans b 
										LEFT JOIN ".DB_PREFIX."_servers s ON s.sid = b.sid
										LEFT JOIN ".DB_PREFIX."_mods m ON m.mid = s.modid 
										WHERE b.bid=?",array($bid));
		$block = $GLOBALS['db']->Execute("DELETE FROM `".DB_PREFIX."_banlog` WHERE bid = ?",array($bid));
		$res = $GLOBALS['db']->Execute("DELETE FROM `".DB_PREFIX."_bans` WHERE `bid` = ?",
									array( $bid ));
		if(empty($steam['RemoveType']))
		{
			foreach($blocked as $tempban)
			{
				SendRconSilent(($steam['type']==0?"removeid STEAM_" . $tempban['steam_universe'] . substr($steam['authid'], 7):"removeip ".$steam['ip']), $tempban['sid']);
			}
			if(((int)$steam['now'] - (int)$steam['created']) <= 300 && $steam['sid'] != "0" && !in_array_dim($steam['sid'], $blocked))
				SendRconSilent(($steam['type']==0?"removeid STEAM_" . $steam['steam_universe'] . substr($steam['authid'], 7):"removeip ".$steam['ip']), $steam['sid']);
		}

		if($res){
			if(!isset($_GET['bulk']))
				echo "<script>setTimeout('ShowBox(\"Бан удален\", \"Бан игрока <b>".StripQuotes($steam['name'])."</b> (<b>".($steam['type']==0?$steam['authid']:$steam['ip'])."</b>) был удален из SourceBans<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"green\", \"index.php?p=banlist$pagelink\", false);', 1350);</script>";
			$log = new CSystemLog("m", "Бан удален", "Бан ".StripQuotes($steam['name'])."' (" . ($steam['type']==0?$steam['authid']:$steam['ip']) . ") был удален.");
			$dcount++;
		}else{
			if(!isset($_GET['bulk']))
				echo "<script>setTimeout('ShowBox(\"Бан не удален\", \"При удалении бана игрока <b>".StripQuotes($steam['name'])."</b> произошла ошибка.<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"red\", \"index.php?p=banlist$pagelink\", false);', 1350);</script>";
			$fail++;
		}
	}
	if(isset($_GET['bulk']))
		echo "<script>setTimeout('ShowBox(\"Игроки удалены\", \"$dcount игроки удалены из SourceBans.<br>$fail failed.<br><br><font color=\'green\' class=\'f-15\'><b>Переадресация...</b></font>\", \"green\", \"index.php?p=banlist$pagelink\", false);', 1350);</script>";
}

$BansStart = intval(($page-1) * $BansPerPage);
$BansEnd = intval($BansStart+$BansPerPage);

// hide inactive bans feature
if(isset($_GET["hideinactive"]) && $_GET["hideinactive"] == "true") {// hide
	$_SESSION["hideinactive"] = true;
	//ShowBox('Hide inactive bans', 'Inactive bans will be hidden from the banlist.', 'green', 'index.php?p=banlist', true);
	echo "<script>setTimeout(\"$('bans_hidden').style.display = 'block';\", 1350);</script>";
} elseif(isset($_GET["hideinactive"]) && $_GET["hideinactive"] == "false") { // show
	unset($_SESSION["hideinactive"]);
	//ShowBox('Show inactive bans', 'Inactive bans will be shown in the banlist.', 'green', 'index.php?p=banlist', true);
	//echo "<script>$('bans_hidden').style.display = 'block';</script>";
}
if(isset($_SESSION["hideinactive"])) {
	$hidetext = "Все";
	$hidetext_darf = "0";
	$hideinactive = " AND RemoveType IS NULL";
	$hideinactiven = " WHERE RemoveType IS NULL";
} else {
	$hidetext = "Только активные";
	$hidetext_darf = "1";
	$hideinactive = "";
	$hideinactiven = "";
}


if (isset($_GET['searchText']))
{
	$search = '%'.trim($_GET['searchText']).'%';
    
    // disable ip search if hiding player ips
    $search_ips = "";
	$search_array = array();
    if(!isset($GLOBALS['config']['banlist.hideplayerips']) || $GLOBALS['config']['banlist.hideplayerips'] != "1" || $userbank->is_admin())
	{
        $search_ips = "BA.ip LIKE ? OR ";
		$search_array[] = $search;
	}
	
	$res = $GLOBALS['db']->Execute(
	"SELECT BA.bid ban_id, BA.type, BA.ip ban_ip, BA.authid, BA.name player_name, created ban_created, ends ban_ends, length ban_length, reason ban_reason, BA.ureason unban_reason, BA.aid, AD.gid AS gid, adminIp, BA.sid ban_server, country ban_country, RemovedOn, RemovedBy, RemoveType row_type,
			SE.ip server_ip, AD.user admin_name, AD.comment admin_comm, AD.skype admin_skype, AD.vk admin_vk, AD.authid admin_authid, AD.gid, MO.icon as mod_icon,
			CAST(MID(BA.authid, 9, 1) AS UNSIGNED) + CAST('76561197960265728' AS UNSIGNED) + CAST(MID(BA.authid, 11, 10) * 2 AS UNSIGNED) AS community_id,
			(SELECT count(*) FROM ".DB_PREFIX."_demos as DM WHERE (DM.demtype='B' or DM.demtype='U') and DM.demid = BA.bid) as demo_count,
			(SELECT count(*) FROM ".DB_PREFIX."_bans as BH WHERE (BH.type = BA.type AND BH.type = 0 AND BH.authid = BA.authid AND BH.authid != '' AND BH.authid IS NOT NULL) OR (BH.type = BA.type AND BH.type = 1 AND BH.ip = BA.ip AND BH.ip != '' AND BH.ip IS NOT NULL)) as history_count
	   FROM ".DB_PREFIX."_bans AS BA
  LEFT JOIN ".DB_PREFIX."_servers AS SE ON SE.sid = BA.sid
  LEFT JOIN ".DB_PREFIX."_mods AS MO on SE.modid = MO.mid
  LEFT JOIN ".DB_PREFIX."_admins AS AD ON BA.aid = AD.aid
      WHERE ".$search_ips."BA.authid LIKE ? or BA.name LIKE ? or BA.reason LIKE ?" . $hideinactive."
   ORDER BY BA.created DESC
   LIMIT ?,?",array_merge($search_array, array($search,$search,$search,intval($BansStart),intval($BansPerPage))));


	$res_count = $GLOBALS['db']->Execute("SELECT count(BA.bid) FROM ".DB_PREFIX."_bans AS BA WHERE ".$search_ips."BA.authid LIKE ? OR BA.name LIKE ? OR BA.reason LIKE ?" . $hideinactive
										,array_merge($search_array, array($search,$search,$search)));
$searchlink = "&searchText=".$_GET["searchText"];
}
elseif(!isset($_GET['advSearch']))
{
	$res = $GLOBALS['db']->Execute(
	"SELECT bid ban_id, BA.type, BA.ip ban_ip, BA.authid, BA.name player_name, created ban_created, ends ban_ends, length ban_length, reason ban_reason, BA.ureason unban_reason, BA.aid, AD.gid AS gid, adminIp, BA.sid ban_server, country ban_country, RemovedOn, RemovedBy, RemoveType row_type,
			SE.ip server_ip, AD.user admin_name, AD.comment admin_comm, AD.skype admin_skype, AD.vk admin_vk, AD.authid admin_authid, AD.gid, MO.icon as mod_icon,
			CAST(MID(BA.authid, 9, 1) AS UNSIGNED) + CAST('76561197960265728' AS UNSIGNED) + CAST(MID(BA.authid, 11, 10) * 2 AS UNSIGNED) AS community_id,
			(SELECT count(*) FROM ".DB_PREFIX."_demos as DM WHERE (DM.demtype='B' or DM.demtype='U') and DM.demid = BA.bid) as demo_count,
			(SELECT count(*) FROM ".DB_PREFIX."_bans as BH WHERE (BH.type = BA.type AND BH.type = 0 AND BH.authid = BA.authid AND BH.authid != '' AND BH.authid IS NOT NULL) OR (BH.type = BA.type AND BH.type = 1 AND BH.ip = BA.ip AND BH.ip != '' AND BH.ip IS NOT NULL)) as history_count
	   FROM ".DB_PREFIX."_bans AS BA
  LEFT JOIN ".DB_PREFIX."_servers AS SE ON SE.sid = BA.sid
  LEFT JOIN ".DB_PREFIX."_mods AS MO on SE.modid = MO.mid
  LEFT JOIN ".DB_PREFIX."_admins AS AD ON BA.aid = AD.aid
  ".$hideinactiven."
   ORDER BY created DESC
   LIMIT ?,?",
	array(intval($BansStart),intval($BansPerPage)));

	$res_count = $GLOBALS['db']->Execute("SELECT count(bid) FROM ".DB_PREFIX."_bans".$hideinactiven);
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
			$where = "WHERE BA.name LIKE ?";
			$advcrit = array("%$value%");
		break;
		case "banid":
			$where = "WHERE BA.bid = ?";
			$advcrit = array($value);
		break;
		case "steamid":
			$where = "WHERE BA.authid = ?";
			$advcrit = array($value);
		break;
		case "steam":
			$where = "WHERE BA.authid LIKE ?";
			$advcrit = array("%$value%");
		break;
		case "ip":
            // disable ip search if hiding player ips
            if(isset($GLOBALS['config']['banlist.hideplayerips']) && $GLOBALS['config']['banlist.hideplayerips'] == "1" && !$userbank->is_admin())
            {
                $where = "";
				$advcrit = array();
			}
			else
			{
				$where = "WHERE BA.ip LIKE ?";
				$advcrit = array("%$value%");
			}
		break;
		case "reason":
			$where = "WHERE BA.reason LIKE ?";
			$advcrit = array("%$value%");
		break;
		case "date":
			$date = explode(",", $value);
			$time = mktime(0,0,0,$date[1],$date[0],$date[2]);
			$time2 = mktime(23,59,59,$date[1],$date[0],$date[2]);
			$where = "WHERE BA.created > ? AND BA.created < ?";
			$advcrit = array($time, $time2);
		break;
		case "length":
			$len = explode(",", $value);
			$length_type = $len[0];
			$length = $len[1]*60;
			$where = "WHERE BA.length ";
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
			$where = "WHERE BA.type = ?";
			$advcrit = array($value);
		break;
		case "admin":
            if($GLOBALS['config']['banlist.hideadminname']&&!$userbank->is_admin())
			{
                $where = "";
				$advcrit = array();
			}
            else {
                $where = "WHERE BA.aid=?";
                $advcrit = array($value);
            }
		break;
		case "where_banned":
			$where = "WHERE BA.sid=?";
			$advcrit = array($value);
		break;
		case "nodemo":
			$where = "WHERE BA.aid = ? AND NOT EXISTS (SELECT DM.demid FROM ".DB_PREFIX."_demos AS DM WHERE DM.demid = BA.bid)";
			$advcrit = array($value);
		break;
		case "bid":
			$where = "WHERE BA.bid = ?";
			$advcrit = array($value);
		break;
		case "comment":
			if($userbank->is_admin())
			{
				$where = "WHERE CO.type = 'B' AND CO.commenttxt LIKE ?";
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
	
	// Make sure we got a "WHERE" clause there, if we add the hide inactive condition
	if(empty($where) && isset($_SESSION["hideinactive"]))
	{
		$hideinactive = $hideinactiven;
	}
	
		$res = $GLOBALS['db']->Execute(
				    	"SELECT BA.bid ban_id, BA.type, BA.ip ban_ip, BA.authid, BA.name player_name, created ban_created, ends ban_ends, length ban_length, reason ban_reason, BA.ureason unban_reason, BA.aid, AD.gid AS gid, adminIp, BA.sid ban_server, country ban_country, RemovedOn, RemovedBy, RemoveType row_type,
			SE.ip server_ip, AD.user admin_name, AD.comment admin_comm, AD.skype admin_skype, AD.vk admin_vk, AD.authid admin_authid, AD.gid, MO.icon as mod_icon,
			CAST(MID(BA.authid, 9, 1) AS UNSIGNED) + CAST('76561197960265728' AS UNSIGNED) + CAST(MID(BA.authid, 11, 10) * 2 AS UNSIGNED) AS community_id,
			(SELECT count(*) FROM ".DB_PREFIX."_demos as DM WHERE (DM.demtype='B' or DM.demtype='U') and DM.demid = BA.bid) as demo_count,
			(SELECT count(*) FROM ".DB_PREFIX."_bans as BH WHERE (BH.type = BA.type AND BH.type = 0 AND BH.authid = BA.authid AND BH.authid != '' AND BH.authid IS NOT NULL) OR (BH.type = BA.type AND BH.type = 1 AND BH.ip = BA.ip AND BH.ip != '' AND BH.ip IS NOT NULL)) as history_count
	   FROM ".DB_PREFIX."_bans AS BA
  LEFT JOIN ".DB_PREFIX."_servers AS SE ON SE.sid = BA.sid
  LEFT JOIN ".DB_PREFIX."_mods AS MO on SE.modid = MO.mid
  LEFT JOIN ".DB_PREFIX."_admins AS AD ON BA.aid = AD.aid
  ".($type=="comment"&&$userbank->is_admin()?"LEFT JOIN ".DB_PREFIX."_comments AS CO ON BA.bid = CO.bid":"")."
      ".$where.$hideinactive."
   ORDER BY BA.created DESC
   LIMIT ?,?", array_merge($advcrit, array(intval($BansStart),intval($BansPerPage))));

	$res_count = $GLOBALS['db']->Execute("SELECT count(BA.bid) FROM ".DB_PREFIX."_bans AS BA
										  ".($type=="comment"&&$userbank->is_admin()?"LEFT JOIN ".DB_PREFIX."_comments AS CO ON BA.bid = CO.bid":"")." ".$where.$hideinactive, $advcrit);
	$searchlink = "&advSearch=".$_GET['advSearch']."&advType=".$_GET['advType'];
}

$BanCount = $res_count->fields[0];
if ($BansEnd > $BanCount) $BansEnd = $BanCount;
if (!$res)
{
	echo "Баны не найдены.";
	PageDie();
}

$view_comments = false;
$bans = array();
function CommunityID($steamid_id){
	$parts = explode(':', str_replace('STEAM_', '' ,$steamid_id)); 
	return bcadd(bcadd('76561197960265728', $parts['1']), bcmul($parts['2'], '2')); 
}
while (!$res->EOF)
{
	$data = array();

	$data['ban_id'] = $res->fields['ban_id'];

	if(!empty($res->fields['ban_ip']) )
	{
		if(!empty($res->fields['ban_country']) && $res->fields['ban_country'] != ' ')
		{
			$data['country'] = '<img src="images/country/' .strtolower($res->fields['ban_country']) . '.gif" alt="' . $res->fields['ban_country'] . '" border="0" align="absmiddle" />';
	    }
	    elseif(isset($GLOBALS['config']['banlist.nocountryfetch']) && $GLOBALS['config']['banlist.nocountryfetch'] == "0")
		{
			$country = FetchIp($res->fields['ban_ip']);
			$edit = $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_bans SET country = ?
				                            WHERE bid = ?",array($country,$res->fields['ban_id']));

			$data['country'] = '<img src="images/country/' . strtolower($country) . '.gif" alt="' . $country . '" border="0" align="absmiddle" />';
		}
		else
		{
			$data['country'] = '<img src="images/country/zz.gif" alt="Страна неизвестна" border="0" align="absmiddle" />';
		}
	}
	else
	{
		$data['country'] = '<img src="images/country/zz.gif" alt="Страна неизвестна" border="0" align="absmiddle" />';
	}

	//$data['ban_date'] = SBDate($dateformat,$res->fields['ban_created']);
	$data['ban_date'] = SBDate($GLOBALS['config']['config.dateformat'],$res->fields['ban_created']);
	$data['ban_date_info'] = SBDate($GLOBALS['config']['config.dateformat_ver2'],$res->fields['ban_created']);
	$data['player'] = addslashes($res->fields['player_name']);
	$data['type'] = $res->fields['type'];
	$data['steamid'] = $res->fields['authid'];
	$data['communityid'] = $res->fields['community_id'];
	$steam2id = $data['steamid'];
	$steam3parts = explode(':', $steam2id);
	$data['steamid3'] = '[U:1:' . ($steam3parts[2] * 2 + $steam3parts[1]) . ']';
	
	if(isset($GLOBALS['config']['banlist.hideadminname']) && $GLOBALS['config']['banlist.hideadminname'] == "1" && !$userbank->is_admin())
		$data['admin'] = false;
	else{
		$data['admin'] = stripslashes($res->fields['admin_name']);
		$data['admin_comm'] = stripslashes($res->fields['admin_comm']);
		$data['admin_gid'] = stripslashes($res->fields['gid']);
		$data['admin_vk'] = stripslashes($res->fields['admin_vk']);
		$data['admin_authid'] = stripslashes($res->fields['admin_authid']);
		$data['admin_authid_link'] = CommunityID($data['admin_authid']);
		$data['admin_skype'] = stripslashes($res->fields['admin_skype']);
	}
	$data['reason'] = stripslashes($res->fields['ban_reason']);
	$data['ban_length'] = $res->fields['ban_length'] == 0 ? 'Навсегда' : SecondsToString(intval($res->fields['ban_length']));

// Custom "listtable_1_banned" & "listtable_1_permanent" addition entries
// Comment the 14 lines below out if they cause issues
	if ($res->fields['ban_length'] == 0)
	{
		$data['expires'] = 'never';
		$data['class'] = "danger c-white";
		$data['ub_reason'] = "";
		$data['unbanned'] = false;
	}
	else
	{
		$data['expires'] = SBDate($dateformat,$res->fields['ban_ends']);
		$data['class'] = "";
		$data['ub_reason'] = "";
		$data['unbanned'] = false;
	}
// End custom entries

	if($res->fields['row_type'] == 'D' || $res->fields['row_type'] == 'U' || $res->fields['row_type'] == 'E' || ($res->fields['ban_length'] && $res->fields['ban_ends'] < time()))
	{
		$data['unbanned'] = true;
		$data['class'] = "success c-white";

		if($res->fields['row_type'] == "D")
			$data['ub_reason'] = "Удален";
		elseif($res->fields['row_type'] == "U")
			$data['ub_reason'] = "Разбанен";
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
// Don't need this stuff.
// Uncomment below if the modifications above cause issues
//	else
//	{
//		$data['unbanned'] = false;
//		$data['class'] = "listtable_1";
//		$data['ub_reason'] = "";
//	}

	$data['layer_id'] = 'layer_'.$res->fields['ban_id'];
	if($data['type'] == "0")
		$alrdybnd = $GLOBALS['db']->Execute("SELECT count(bid) as count FROM `".DB_PREFIX."_bans` WHERE authid = '".$data['steamid']."' AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND type = '0';");
	else
		$alrdybnd = $GLOBALS['db']->Execute("SELECT count(bid) as count FROM `".DB_PREFIX."_bans` WHERE ip = '".$res->fields['ban_ip']."' AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND type = '1';");
	if($alrdybnd->fields['count']==0)
		$data['reban_link'] = CreateLinkR('Перебанить',"index.php?p=admin&c=bans".$pagelink."&rebanid=".$res->fields['ban_id']."&key=".$_SESSION['banlist_postkey']."#^0");
	else
		$data['reban_link'] = false;
	$data['blockcomm_link'] = CreateLinkR('Заглушить',"index.php?p=admin&c=comms".$pagelink."&blockfromban=".$res->fields['ban_id']."&key=".$_SESSION['banlist_postkey']."#^0");
	$data['details_link'] = CreateLinkR('Кликни','getdemo.php?type=B&id='.$res->fields['ban_id']);
	$data['groups_link'] = CreateLinkR('Показать группы',"index.php?p=admin&c=bans&fid=".$data['communityid']."#^4");
	$data['friend_ban_link'] = CreateLinkR('Забанить друзей', '#', '', '_self', false, "BanFriendsProcess('".$data['communityid']."','".StripQuotes($data['player'])."');return false;");
	$data['edit_link'] = CreateLinkR('Редактировать',"index.php?p=admin&c=bans&o=edit".$pagelink."&id=".$res->fields['ban_id']."&key=".$_SESSION['banlist_postkey']);

	$data['unban_link'] = CreateLinkR('Разбанить',"#","", "_self", false, "UnbanBan('".$res->fields['ban_id']."', '".$_SESSION['banlist_postkey']."', '".$pagelink."', '".StripQuotes($data['player'])."', 1, false);return false;");
	$data['delete_link'] = CreateLinkR('Удалить',"#","", "_self", false, "RemoveBan('".$res->fields['ban_id']."', '".$_SESSION['banlist_postkey']."', '".$pagelink."', '".StripQuotes($data['player'])."', 0, false);return false;");

	
	$data['server_id'] = $res->fields['ban_server'];

	if(empty($res->fields['mod_icon']))
	{
		$modicon = "web.png";
	}
	else
	{
		$modicon = $res->fields['mod_icon'];
	}

	$data['mod_icon'] = '<img src="images/games/' .$modicon . '" alt="MOD" border="0" align="absmiddle" />';
	$data['country_icon'] = $data['country'] . ' &nbsp;';

    if($res->fields['history_count'] > 1)
        $data['prevoff_link'] = $res->fields['history_count'] . " " . CreateLinkR("(search)","index.php?p=banlist&searchText=" . ($data['type']==0?$data['steamid']:$res->fields['ban_ip']) . "&Submit");
    else
        $data['prevoff_link'] = "Не найдено";



	if (strlen($res->fields['ban_ip']) < 7)
		$data['ip'] = 'none';
	else
		$data['ip'] =  $data['country'] . '&nbsp;' . $res->fields['ban_ip'];

	if ($res->fields['ban_length'] == 0)
		$data['expires'] = 'never';
	else
		$data['expires'] = SBDate($dateformat,$res->fields['ban_ends']);


	if ($res->fields['demo_count'] == 0)
	{
		$data['demo_available'] = false;
		$data['demo_quick'] = 'Н/Д';
		$data['demo_link'] = CreateLinkR('Нет Демо',"#");
	}
	else
	{
		$demtype = $GLOBALS['db']->GetRow("SELECT demtype FROM `".DB_PREFIX."_demos` WHERE demid = '".$data['ban_id']."'");
		$data['demo_available'] = true;
		$data['demo_quick'] = CreateLinkR('Демо',"getdemo.php?type=".$demtype['demtype']."&id=".$data['ban_id']);
		$data['demo_link'] = CreateLinkR('Демка',"getdemo.php?type=".$demtype['demtype']."&id=".$data['ban_id']);
	}

	$data['server_id'] = $res->fields['ban_server'];

	$banlog = $GLOBALS['db']->GetAll("SELECT bl.time, bl.name, s.ip, s.port FROM `".DB_PREFIX."_banlog` AS bl LEFT JOIN `".DB_PREFIX."_servers` AS s ON s.sid = bl.sid WHERE bid = '".$data['ban_id']."'");
	$data['blockcount'] = sizeof($banlog);
	$logstring = "";
	foreach($banlog AS $logged) {
		if(!empty($logstring))
			$logstring .= "  <span class=\"c-red\">//</span>  ";
		$logstring .= '<strong><i><span data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$logged["ip"].':'.$logged["port"].', Дата: '.SBDate($dateformat,$logged["time"]).'">'.($logged["name"]!=""?htmlspecialchars($logged["name"]):"<i>без имени</i>").'</span></i></strong>';
	}
	$data['banlog'] = $logstring;

	//COMMENT STUFF
	//-----------------------------------
	if($userbank->is_admin()) {
		$view_comments = true;
		$commentres = $GLOBALS['db']->Execute("SELECT cid, aid, commenttxt, added, edittime,
											(SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = C.aid) AS comname,
											(SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = C.editaid) AS editname
											FROM `".DB_PREFIX."_comments` AS C
											WHERE type = 'B' AND bid = '".$data['ban_id']."' ORDER BY added desc");

		if($commentres->RecordCount()>0) {
			$comment = array();
			$morecom = 0;
			while(!$commentres->EOF) {
				$cdata = array();
				$cdata['morecom'] = ($morecom==1?true:false);
				if($commentres->fields['aid'] == $userbank->GetAid() || $userbank->HasAccess(ADMIN_OWNER)) {
					$cdata['editcomlink'] = "<a href=\"index.php?p=banlist&comment=".$data['ban_id']."&ctype=B&cid=".$commentres->fields['cid'].$pagelink."\"> Редактировать</a>";
					if($userbank->HasAccess(ADMIN_OWNER)) {
						$cdata['delcomlink'] = "<a href=\"#\" target=\"_self\" onclick=\"RemoveComment(".$commentres->fields['cid'].",'B',".(isset($_GET["page"])?$page:-1).");\">Удалить</a>";
					}
				}
				else {
					$cdata['editcomlink'] = "none";
					$cdata['delcomlink'] = "none";
				}

				$cdata['comname'] = $commentres->fields['comname'];
				$cdata['added'] = SBDate($dateformat, $commentres->fields['added']);
				$cdata['commenttxt'] = htmlspecialchars($commentres->fields['commenttxt']);
				$cdata['commenttxt'] = str_replace("\n", "<br />", $cdata['commenttxt']);
				// Parse links and wrap them in a <a href=""></a> tag to be easily clickable
				$cdata['commenttxt'] = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $cdata['commenttxt']);

				if(!empty($commentres->fields['edittime'])) {
					$cdata['edittime'] = SBDate($dateformat, $commentres->fields['edittime']);
					$cdata['editname'] = $commentres->fields['editname'];
				}
				else {
					$cdata['edittime'] = "none";
					$cdata['editname'] = "none";
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


	//$data['addcomment'] = CreateLinkR('<img src="images/details.gif" border="0" alt="" style="vertical-align:middle" /> Add Comment','index.php?p=banlist&comment='.$data['ban_id'].'&ctype=B'.$pagelink);
	$data['addcomment_link'] = 'index.php?p=banlist&comment='.$data['ban_id'].'&ctype=B'.$pagelink;
	//-----------------------------------

	$data['ub_reason'] = (isset($data['ub_reason'])?$data['ub_reason']:"");
 	$data['banlength'] = $data['ban_length'];
 	//$data['banlength'] = $data['ban_length'] . " " .  $data['ub_reason'];
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
	if(isset($_GET['c']) && $_GET['c'] == "bans")
		$prev = CreateLinkR('<i class="zmdi zmdi-chevron-right"></i>',"javascript:void(0);", "", "_self", false, $prev);
	else
		$prev = CreateLinkR('<i class="zmdi zmdi-chevron-left"></i>',"index.php?p=banlist&page=".($page-1).(isset($_GET['searchText']) > 0?"&searchText=".$_GET['searchText']:'' . $advSearchString));
}
else
{
	$prev = "";
}
if ($BansEnd < $BanCount)
{
	if(isset($_GET['c']) && $_GET['c'] == "bans")
	{
		if(!isset($nxt))
			$nxt = "";
			$next = CreateLinkR('<i class="zmdi zmdi-chevron-left"></i>',"javascript:void(0);", "", "_self", false, $nxt);
	}
	else
		$next = CreateLinkR('<i class="zmdi zmdi-chevron-right"></i>',"index.php?p=banlist&page=".($page+1).(isset($_GET['searchText']) ?"&searchText=".$_GET['searchText']:'' . $advSearchString));
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
	$ban_nav_p = ' / Страница: <div class="select" style="display: inline-block;"><select class="form-control" onchange="changePage(this,\'B\',\''.(isset($_GET['advSearch']) ? $_GET['advSearch'] : '').'\',\''.(isset($_GET['advType']) ? $_GET['advType'] : '').'\');" style="display: inline-block;width: 50px;">';
	for($i=1;$i<=$pages;$i++)
	{
		if(isset($_GET["page"]) && $i == $page) {
			$ban_nav_p .= '<option value="' . $i . '" selected="selected">&nbsp;' . $i . '</option>';
			continue;
		}
		$ban_nav_p .= '<option value="' . $i . '">&nbsp;' . $i . '</option>';
	}
	$ban_nav_p .= '</select></div>&nbsp;';
}

//COMMENT STUFF
//----------------------------------------
if(isset($_GET["comment"])) {
	$_GET["comment"] = (int)$_GET["comment"];
	$theme->assign('commenttype', (isset($_GET["cid"])?"Редактировать":"Добавить"));
	if(isset($_GET["cid"])) {
		$_GET["cid"] = (int)$_GET["cid"];
		$ceditdata = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_comments WHERE cid = '".$_GET["cid"]."'");
		$ctext = htmlspecialchars($ceditdata['commenttxt']);
		$cotherdataedit = " AND cid != '".$_GET["cid"]."'";
	}
	else 
	{
		$cotherdataedit = "";
		$ctext = "";
	}
	
	$_GET["ctype"] = substr($_GET["ctype"], 0, 1);
	
	$cotherdata = $GLOBALS['db']->Execute("SELECT cid, aid, commenttxt, added, edittime,
											(SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = C.aid) AS comname,
											(SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = C.editaid) AS editname
											FROM `".DB_PREFIX."_comments` AS C
											WHERE type = ? AND bid = ?".$cotherdataedit." ORDER BY added desc", array($_GET["ctype"], $_GET["comment"]));

	$ocomments = array();
	while(!$cotherdata->EOF)
	{
		$coment = array();
		$coment['comname']   = 	$cotherdata->fields['comname'];
		$coment['added'] = SBDate($dateformat, $cotherdata->fields['added']);
		$coment['commenttxt'] = htmlspecialchars($cotherdata->fields['commenttxt']);
		$coment['commenttxt'] = str_replace("\n", "<br />", $coment['commenttxt']);
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

	$theme->assign('page', (isset($_GET["page"])?$page:-1));
	$theme->assign('othercomments', $ocomments);
	$theme->assign('commenttext', (isset($ctext)?$ctext:""));
	$theme->assign('ctype', $_GET["ctype"]);
	$theme->assign('cid', (isset($_GET["cid"])?$_GET["cid"]:""));
}
$theme->assign('view_comments',$view_comments);
$theme->assign('comment', (isset($_GET["comment"])&&$view_comments?$_GET["comment"]:false));
//----------------------------------------

unset($_SESSION['CountryFetchHndl']);

$theme->assign('searchlink', $searchlink);
$theme->assign('hidetext', $hidetext);
$theme->assign('hidetext_darf', $hidetext_darf);
$theme->assign('total_bans', $BanCount);
$theme->assign('active_bans', $BanCount);

$theme->assign('ban_nav', $ban_nav);
$theme->assign('ban_nav_p', $ban_nav_p);
$theme->assign('ban_list', $bans);
$theme->assign('admin_nick', $userbank->GetProperty("user"));
$theme->assign('nocountryshow', ($GLOBALS['config']['banlist.nocountryfetch'] == "1" && !$userbank->is_logged_in()));

$theme->assign('admin_postkey', $_SESSION['banlist_postkey']);
$theme->assign('admininfos', $GLOBALS['config']['config.enableadmininfos']);
$theme->assign('hideplayerips', (isset($GLOBALS['config']['banlist.hideplayerips']) && $GLOBALS['config']['banlist.hideplayerips'] == "1" && !$userbank->is_admin()));
$theme->assign('hideadminname', (isset($GLOBALS['config']['banlist.hideadminname']) && $GLOBALS['config']['banlist.hideadminname'] == "1" && !$userbank->is_admin()));
$theme->assign('groupban', ($GLOBALS['config']['config.enablegroupbanning']==1 && $userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN)));
$theme->assign('friendsban', ($GLOBALS['config']['config.enablefriendsbanning']==1 && $userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN)));
$theme->assign('general_unban', $userbank->HasAccess(ADMIN_OWNER|ADMIN_UNBAN|ADMIN_UNBAN_OWN_BANS|ADMIN_UNBAN_GROUP_BANS));
$theme->assign('can_delete', $userbank->HasAccess(ADMIN_DELETE_BAN));
$theme->assign('view_bans', ($userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ALL_BANS|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_GROUP_BANS|ADMIN_UNBAN|ADMIN_UNBAN_OWN_BANS|ADMIN_UNBAN_GROUP_BANS|ADMIN_DELETE_BAN)));
$theme->assign('can_export',($userbank->HasAccess(ADMIN_OWNER) || (isset($GLOBALS['config']['config.exportpublic']) && $GLOBALS['config']['config.exportpublic'] == "1")));
$theme->display('page_bans.tpl');
?>
