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

if(!defined("IN_SB")){echo "Ошибка доступа!";die();}

global $userbank, $theme; 

if($GLOBALS['config']['config.modgroup'] != "0"){
	$gid_groups = $GLOBALS['db']->GetOne("SELECT `gid` FROM `" . DB_PREFIX . "_admins` WHERE `aid` = '".$userbank->GetAid()."'");

	if($gid_groups == $GLOBALS['config']['config.modgroup']){
		$_GET['id'] = preg_replace("/[^0-9]/", '', $_GET['id']);
		$srv_ban = $GLOBALS['db']->GetOne("SELECT `sid` FROM `" . DB_PREFIX . "_bans` WHERE `bid` = '".$_GET['id']."'");
		$amd_access = $GLOBALS['db']->GetOne("SELECT `server_id` FROM `" . DB_PREFIX . "_admins_servers_groups` WHERE `admin_id` = '".$userbank->GetAid()."' AND `server_id` = '".$srv_ban."'");
		if($srv_ban != $amd_access){
			echo '<script>setTimeout(\'<script>ShowBox("Ошибка", "Вы имеете доступ только к редактированию банов на тех серверах, где у вас есть права управляющего!", "red", "");setTimeout(\'history.go(-1);\', 4000);\', 1200);</script>';
			PageDie();
		}
	}
}


if ($_GET['key'] != $_SESSION['banlist_postkey'])
{
	echo '<script>ShowBox("Ошибка", "Возможная попытка взлома (Несоответствие URL-ключа)!", "red", "index.php?p=admin&c=bans");</script>';
	PageDie();
}
if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
	echo '<script>ShowBox("Ошибка", "Нет бана!", "red", "index.php?p=admin&c=bans");</script>';
	PageDie();
}

$res = $GLOBALS['db']->GetRow("
    				SELECT bid, ba.ip, ba.type, ba.authid, ba.name, created, ends, length, reason, ba.aid, ba.sid, ad.user, ad.gid, CONCAT(se.ip,':',se.port), se.sid, mo.icon, dm.origname 
    				FROM ".DB_PREFIX."_bans AS ba
    				LEFT JOIN ".DB_PREFIX."_admins AS ad ON ba.aid = ad.aid
    				LEFT JOIN ".DB_PREFIX."_servers AS se ON se.sid = ba.sid
    				LEFT JOIN ".DB_PREFIX."_demos AS dm ON dm.demid = {$_GET['id']}
    				LEFT JOIN ".DB_PREFIX."_mods AS mo ON mo.mid = se.modid
    				WHERE bid = {$_GET['id']}");

if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ALL_BANS)&&(!$userbank->HasAccess(ADMIN_EDIT_OWN_BANS) && $res[8]!=$userbank->GetAid())&&(!$userbank->HasAccess(ADMIN_EDIT_GROUP_BANS) && $res->fields['gid']!=$userbank->GetProperty('gid')))
{
	echo '<script>ShowBox("Ошибка", "Вы не имеете доступ к этому!", "red", "index.php?p=admin&c=bans");</script>';
	PageDie();
}

isset($_GET["page"])?$pagelink = "&page=".$_GET["page"]:$pagelink = "";

$errorScript = "";

if(isset($_POST['name']))
{
	$_POST['steam'] = trim($_POST['steam']);
	$_POST['type'] = (int)$_POST['type'];
	$demo_linker = $_POST['demo_link'];
	if($demo_linker != "")
		preg_match("@^(?:http://)?([^/]+)@i", $_SERVER['HTTP_HOST'], $demo_linker_dns);


	// Form Validation
	$error = 0;
	// If they didn't type a steamid
	if(empty($_POST['steam']) && $_POST['type'] == 0)
	{
		$error++;
		$errorScript .= "$('steam.msg').innerHTML = 'Вы должны ввести Steam ID или Community ID';";
		$errorScript .= "$('steam.msg').setStyle('display', 'block');";
	}
	else if(($_POST['type'] == 0 
	&& !is_numeric($_POST['steam']) 
	&& !validate_steam($_POST['steam']))
	|| (is_numeric($_POST['steam']) 
	&& (strlen($_POST['steam']) < 15
	|| !validate_steam($_POST['steam'] = FriendIDToSteamID($_POST['steam'])))))
	{
		$error++;
		$errorScript .= "$('steam.msg').innerHTML = 'Введите реальный Steam ID или Community ID';";
		$errorScript .= "$('steam.msg').setStyle('display', 'block');";
	}
	// Didn't type an IP
	else if (empty($_POST['ip']) && $_POST['type'] == 1)
	{
		$error++;
		$errorScript .= "$('ip.msg').innerHTML = 'Введите IP';";
		$errorScript .= "$('ip.msg').setStyle('display', 'block');";
	}
	else if ($_POST['type'] == 1 && !validate_ip($_POST['ip']))
	{
		$error++; 
		$errorScript .= "$('ip.msg').innerHTML = 'Введите реальный IP';";
		$errorScript .= "$('ip.msg').setStyle('display', 'block');";
	}

	if($demo_linker != ""){
		if(checkdnsrr($demo_linker_dns[0],'A') && @get_headers($demo_linker)){
			echo "";
		}else{
			$error++;
			$errorScript .= "$('demo_link.msg').innerHTML = 'Не могу получить заголовок данного веб-сервера! Совет: Прочтите <img src=\"images/help.png\" />';";
			$errorScript .= "$('demo_link.msg').setStyle('display', 'block');";
		}
	}
	// Didn't type a custom reason
	if($_POST['listReason'] == "other" && empty($_POST['txtReason']))
	{
		$error++;
		$errorScript .= "$('reason.msg').innerHTML = 'Введите причину';";
		$errorScript .= "$('reason.msg').setStyle('display', 'block');";
	}
	
	// prune any old bans
	PruneBans();
	
	if($error == 0)
	{
		// Check if the new steamid is already banned
		if($_POST['type'] == 0)
		{
			$chk = $GLOBALS['db']->GetRow("SELECT count(bid) AS count FROM ".DB_PREFIX."_bans WHERE authid = ? AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND type = '0' AND bid != ?", array($_POST['steam'], (int)$_GET['id']));

			if((int)$chk[0] > 0)
			{
				$error++;
				$errorScript .= "$('steam.msg').innerHTML = 'Этот SteamID уже забанен';";
				$errorScript .= "$('steam.msg').setStyle('display', 'block');";
			}
			else
			{
				// Check if player is immune
				$admchk = $userbank->GetAllAdmins();
				foreach($admchk as $admin)
				{
					if($admin['authid'] == $_POST['steam'] && $userbank->GetProperty('srv_immunity') < $admin['srv_immunity'])
					{
						$error++;
						$errorScript .= "$('steam.msg').innerHTML = 'У админа ".$admin['user']." иммунитет';";
						$errorScript .= "$('steam.msg').setStyle('display', 'block');";
						break;
					}
				}
			}
		}
		// Check if the ip is already banned
		else if($_POST['type'] == 1)
		{
			$chk = $GLOBALS['db']->GetRow("SELECT count(bid) AS count FROM ".DB_PREFIX."_bans WHERE ip = ? AND (length = 0 OR ends > UNIX_TIMESTAMP()) AND RemovedBy IS NULL AND type = '1' AND bid != ?", array($_POST['ip'], (int)$_GET['id']));

			if((int)$chk[0] > 0)
			{
				$error++;
				$errorScript .= "$('ip.msg').innerHTML = 'Этот IP уже забанен';";
				$errorScript .= "$('ip.msg').setStyle('display', 'block');";
			}
		}
	}
	
	$_POST['name'] = RemoveCode($_POST['name']);
	$_POST['ip'] = preg_replace('#[^\d\.]#', '', $_POST['ip']);//strip ip of all but numbers and dots
	$_POST['dname'] = RemoveCode($_POST['dname']);
	$reason = RemoveCode(trim($_POST['listReason'] == "other"?$_POST['txtReason']:$_POST['listReason']));
	
	if(!$_POST['banlength'])
		$_POST['banlength'] = 0;
	else
		$_POST['banlength'] = (int)$_POST['banlength']*60;
	
	// Show the new values in the form
	$res['name'] = $_POST['name'];
	$res['authid'] = $_POST['steam'];
	$res['ip'] = $_POST['ip'];
	$res['length'] = $_POST['banlength'];
	$res['type'] = $_POST['type'];
	$res['reason'] = $reason;
	
	// Only process if there are still no errors
	if($error == 0)
	{
		$lengthrev = $GLOBALS['db']->Execute("SELECT length, authid FROM ".DB_PREFIX."_bans WHERE bid = '".(int)$_GET['id']."'");
		
		
		$edit = $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_bans SET
										`name` = ?, `type` = ?, `reason` = ?, `authid` = ?,
										`length` = ?,
										`ip` = ?,
										`country` = '',
										`ends` 	 =  `created` + ?
										WHERE bid = ?", array($_POST['name'], $_POST['type'], $reason, $_POST['steam'], $_POST['banlength'], $_POST['ip'], $_POST['banlength'], (int)$_GET['id']));
		
		// Set all submissions to archived for that steamid
		$GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_submissions` SET archiv = '3', archivedby = '".$userbank->GetAid()."' WHERE SteamId = ?;", array($_POST['steam']));
				
		if(!empty($_POST['dname']) and !$demo_linker)
		{
			$demoid = $GLOBALS['db']->GetRow("SELECT filename FROM `" . DB_PREFIX . "_demos` WHERE demid = '" . $_GET['id'] . "';");
			@unlink(SB_DEMOS."/".$demoid['filename']);
			$edit = $GLOBALS['db']->Execute("REPLACE INTO ".DB_PREFIX."_demos
											(`demid`, `demtype`, `filename`, `origname`)
											VALUES
											(?,
											'b',
											?,
											?)", array((int)$_GET['id'], $_POST['did'], $_POST['dname']));
			$res['dname'] = RemoveCode($_POST['dname']);
		}
		
		if($demo_linker != "" && empty($_POST['dname'])){
				
			$edit = $GLOBALS['db']->Execute("REPLACE INTO ".DB_PREFIX."_demos
												(`demid`, `demtype`, `filename`, `origname`)
												VALUES
												(?,
												'U',
												?,
												?)", array((int)$_GET['id'], '', $demo_linker));
		}else{
			if($res['origname'])
				$edit = $GLOBALS['db']->Execute("DELETE FROM `".DB_PREFIX."_demos` WHERE `demid` = '?';", array((int)$_GET['id']));
		}
		
		if($_POST['banlength'] != $lengthrev->fields['length'])
			$log = new CSystemLog("m", "Срок бана изменен", "Срок бана (" . $lengthrev->fields['authid'] . ") был обновлен, раньше: ".$lengthrev->fields['length'].", сейчас: ".$_POST['banlength']);
		echo "<script>setTimeout(\"ShowBox('Бан обновлен', 'Бан был успешно обновлен', 'green', 'index.php?p=banlist".$pagelink."', false, 5000)\", 1000);</script>";
	}
}

if(!$res)
{
	echo "<script>setTimeout(\"ShowBox('Ошибка', 'Произошла ошибка получения деталей. Возможно, этот бан был удален?', 'red', 'index.php?p=banlist".$pagelink."', false, 5000)\", 1000);</script>";
}

$theme->assign('demo_link_val', $res['origname']);
$theme->assign('ban_name', $res['name']);
$theme->assign('ban_reason', $res['reason']);
$theme->assign('ban_authid', trim($res['authid']));
$theme->assign('ban_ip', $res['ip']);
$theme->assign('ban_demo', (!empty($res['dname'])?"<b>".$res['dname']."</b>":""));
$theme->assign('customreason', ((isset($GLOBALS['config']['bans.customreasons'])&&$GLOBALS['config']['bans.customreasons']!="")?unserialize($GLOBALS['config']['bans.customreasons']):false));

$theme->left_delimiter = "-{";
$theme->right_delimiter = "}-";
$theme->display('page_admin_edit_ban.tpl');
$theme->left_delimiter = "{";
$theme->right_delimiter = "}";
?>
<script type="text/javascript">window.addEvent('domready', function(){
<?php echo $errorScript; ?>
});
function changeReason(szListValue)
{
	$('dreason').style.display = (szListValue == "other" ? "block" : "none");
}
selectLengthTypeReason('<?php echo (int)$res['length']; ?>', '<?php echo $res['type']; ?>', '<?php echo addslashes($res['reason']); ?>');
</script>
