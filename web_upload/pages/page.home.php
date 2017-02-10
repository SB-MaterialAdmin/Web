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

if (!defined('IN_SB')) {echo("Вы не должны быть здесь. Используйте только ссылки внутри системы!");die();}

global $theme;
define('IN_HOME', true);

//$GLOBALS['TitleRewrite'] = "HOME";

$res = $GLOBALS['db']->Execute("SELECT count(name) FROM ".DB_PREFIX."_banlog");
$totalstopped = (int)$res->fields[0];

$res = $GLOBALS['db']->Execute("SELECT bl.name, time, bl.sid, bl.bid, b.type, b.authid, b.ip
								FROM ".DB_PREFIX."_banlog AS bl
								LEFT JOIN ".DB_PREFIX."_bans AS b ON b.bid = bl.bid
								ORDER BY time DESC LIMIT 10");

$GLOBALS['server_qry'] = "";
$stopped = array();
$blcount = 0;
while (!$res->EOF)
{
	$info = array();
	//$info['date'] = SBDate($dateformat,$res->fields[1]);
	$info['date'] = SBDate($GLOBALS['config']['config.dateformat_ver2'],$res->fields[1]);
	$info['name'] = stripslashes($res->fields[0]);
	$info['short_name'] = trunc($info['name'], 40, false);
	$info['auth'] = $res->fields['authid'];
	$info['ip'] = $res->fields['ip'];
	$info['server'] = "block_".$res->fields['sid']."_$blcount";
	if($res->fields['type'] == 1)
	{
		$info['search_link'] = "index.php?p=banlist&advSearch=" . $info['ip'] . "&advType=ip&Submit";
	}else{
		$info['search_link'] = "index.php?p=banlist&advSearch=" . $info['auth'] . "&advType=steamid&Submit";
	}
	$info['link_url'] = "window.location = '" . $info['search_link'] . "';";
	$info['name'] = htmlspecialchars(addslashes($info['name']), ENT_QUOTES, 'UTF-8');
	$info['popup'] = "ShowBox('Заблокированный игрок: " . $info['name'] . "', '" . $info['name'] . " пытался войти<br />' + document.getElementById('".$info['server']."').title + '<br />at " . $info['date'] . "<br /><div align=middle><a href=" . $info['search_link'] . ">Кликните здесь для просмотра деталей.</a></div>', 'red', '', true);";
		
    $GLOBALS['server_qry'] .= "xajax_ServerHostProperty(".$res->fields['sid'].", 'block_".$res->fields['sid']."_$blcount', 'title', 100);";
        
    array_push($stopped,$info);
	$res->MoveNext();
    ++$blcount;
}

$res = $GLOBALS['db']->Execute("SELECT count(bid) FROM ".DB_PREFIX."_bans");
$BanCount = (int)$res->fields[0];

$res = $GLOBALS['db']->Execute("SELECT bid, ba.ip, ba.authid, ba.name, created, ends, length, reason, ba.aid, ba.sid, ad.user, CONCAT(se.ip,':',se.port), se.sid, mo.icon, ba.RemoveType, ba.type
			    				FROM ".DB_PREFIX."_bans AS ba 
			    				LEFT JOIN ".DB_PREFIX."_admins AS ad ON ba.aid = ad.aid
			    				LEFT JOIN ".DB_PREFIX."_servers AS se ON se.sid = ba.sid
			    				LEFT JOIN ".DB_PREFIX."_mods AS mo ON mo.mid = se.modid
			    				ORDER BY created DESC LIMIT 10");
$bans = array();
while (!$res->EOF)
{
        $info = array();
	if ($res->fields['length'] == 0)
	{
		$info['perm'] = true;
		$info['unbanned'] = false;
	}
	else
	{
		$info['temp'] = true;
                $info['unbanned'] = false;
	}
	$info['name'] = stripslashes($res->fields[3]);
	//$info['created'] = SBDate($dateformat,$res->fields['created']);
	$info['created'] = SBDate($GLOBALS['config']['config.dateformat_ver2'],$res->fields['created']);
	$info['created_info'] = SBDate("Выдано ".$GLOBALS['config']['config.dateformat'],$res->fields['created']);
	$ltemp = explode(",",$res->fields[6] == 0 ? 'Навсегда' : SecondsToString(intval($res->fields[6])));
	$info['length'] = $ltemp[0];
	$info['icon'] = empty($res->fields[13]) ? 'web.png' : $res->fields[13];
	$info['authid'] = $res->fields[2];
	$info['ip'] = $res->fields[1];
	if($res->fields[15] == 1)
	{
		$info['search_link'] = "index.php?p=banlist&advSearch=" . $info['ip'] . "&advType=ip&Submit";
	}else{
		$info['search_link'] = "index.php?p=banlist&advSearch=" . $info['authid'] . "&advType=steamid&Submit";
	}
	$info['link_url'] = "window.location = '" . $info['search_link'] . "';";
	$info['short_name'] = trunc($info['name'], 25, false);
	
	if($res->fields[14] == 'D' || $res->fields[14] == 'U' || $res->fields[14] == 'E' || ($res->fields[6] && $res->fields[5] < time()))
	{
		$info['unbanned'] = true;
		
		if($res->fields[14] == 'D')
			$info['ub_reason'] = 'D';
		elseif($res->fields[14] == 'U')
			$info['ub_reason'] = 'U';
		else
			$info['ub_reason'] = 'E';
	}
	else
	{
		$info['unbanned'] = false;
	}
	
	array_push($bans,$info);
	$res->MoveNext();
}

$res = $GLOBALS['db']->Execute("SELECT count(bid) FROM ".DB_PREFIX."_comms");
$CommCount = (int)$res->fields[0];
	
$res = $GLOBALS['db']->Execute("SELECT bid, ba.authid, ba.type, ba.name, created, ends, length, reason, ba.aid, ba.sid, ad.user, CONCAT(se.ip,':',se.port), se.sid, mo.icon, ba.RemoveType, ba.type
				    				FROM ".DB_PREFIX."_comms AS ba 
				    				LEFT JOIN ".DB_PREFIX."_admins AS ad ON ba.aid = ad.aid
				    				LEFT JOIN ".DB_PREFIX."_servers AS se ON se.sid = ba.sid
				    				LEFT JOIN ".DB_PREFIX."_mods AS mo ON mo.mid = se.modid
				    				ORDER BY created DESC LIMIT 10");
$comms = array();
while (!$res->EOF)
{
        $info = array();
	if ($res->fields['length'] == 0)
	{
		$info['perm'] = true;
		$info['unbanned'] = false;
	}
	else
	{
		$info['temp'] = true;
                $info['unbanned'] = false;
	}
	$info['name'] = stripslashes($res->fields[3]);
	//$info['created'] = SBDate($dateformat,$res->fields['created']);
	$info['created'] = SBDate($GLOBALS['config']['config.dateformat_ver2'],$res->fields['created']);
	$info['created_info'] = SBDate("Выдано ".$GLOBALS['config']['config.dateformat'],$res->fields['created']);
	$ltemp = explode(",",$res->fields[6] == 0 ? 'Навсегда' : ($res->fields[6] < 0 ? "Сессия" : SecondsToString(intval($res->fields[6]))));
	$info['length'] = $ltemp[0];
	$info['icon'] = empty($res->fields[13]) ? 'web.png' : $res->fields[13];
	$info['authid'] = $res->fields['authid'];
	$info['search_link'] = "index.php?p=commslist&advSearch=" . $info['authid'] . "&advType=steamid&Submit";
	$info['link_url'] = "window.location = '" . $info['search_link'] . "';";
	$info['short_name'] = trunc($info['name'], 25, false);
	$info['type'] = $res->fields['type'] == 2 ? "images/type_c.png" : "images/type_v.png";
		
	if($res->fields[14] == 'D' || $res->fields[14] == 'U' || $res->fields[14] == 'E' || ($res->fields[6] && $res->fields[5] < time()))
	{
		$info['unbanned'] = true;
			
		if($res->fields[14] == 'D')
			$info['ub_reason'] = 'D';
		elseif($res->fields[14] == 'U')
			$info['ub_reason'] = 'U';
		else
			$info['ub_reason'] = 'E';
	}
	else
	{
		$info['unbanned'] = false;
	}
		
	array_push($comms,$info);
	$res->MoveNext();
}

$counts = $GLOBALS['db']->GetRow("SELECT 
         (SELECT COUNT(aid) FROM `" . DB_PREFIX . "_admins` WHERE aid > 0) AS admins,
         (SELECT COUNT(sid) FROM `" . DB_PREFIX . "_servers`) AS servers"); // +

		 
$theme->assign('total_admins', $counts['admins']); // +
$theme->assign('total_servers', $counts['servers']); // +
$theme->assign('listing_block',  $GLOBALS['config']['config.home.comms']);

require(TEMPLATES_PATH . "/page.servers.php"); //Set theme vars from servers page

$theme->assign('dashboard_lognopopup', (isset($GLOBALS['config']['dash.lognopopup']) && $GLOBALS['config']['dash.lognopopup'] == "1"));
$theme->assign('dashboard_title',  stripslashes($GLOBALS['config']['dash.intro.title']));
$theme->assign('dashboard_text',  stripslashes($GLOBALS['config']['dash.intro.text']));
$theme->assign('dashboard_info_block',  $GLOBALS['config']['dash.info_block']);
$theme->assign('dashboard_info_block_text',  $GLOBALS['config']['dash.info_block_text']);
$theme->assign('dashboard_info_block_text_p',  $GLOBALS['config']['dash.info_block_text_t']);
$theme->assign('dashboard_info_vk',  $GLOBALS['config']['dash.info_vk']);
$theme->assign('dashboard_info_steam',  $GLOBALS['config']['dash.info_steam']);
$theme->assign('dashboard_info_yout',  $GLOBALS['config']['dash.info_yout']);
$theme->assign('dashboard_info_face',  $GLOBALS['config']['dash.info_face']);
$theme->assign('players_blocked', $stopped);
$theme->assign('total_blocked', $totalstopped);

$theme->assign('players_banned', $bans);
$theme->assign('total_bans', $BanCount);

$theme->assign('total_comms', $CommCount);
$theme->assign('players_commed', $comms);

$theme->assign('stats', ($GLOBALS['config']['theme.home.stats'] == "1"));

$theme->display('page_dashboard.tpl');
?>
