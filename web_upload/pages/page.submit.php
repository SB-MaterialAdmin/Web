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

global $userbank, $ui, $theme;
if($GLOBALS['config']['config.enablesubmit']!="1")
{
	CreateRedBox("Ошибка", "Страница отключена.");
	PageDie();
}

require_once(INCLUDES_PATH.'/CServerControl.php');
$sinfo = new CServerControl();

if (!isset($_POST['subban']) || $_POST['subban'] != 1)
{
	$SteamID = "";
	$BanIP = "";
	$PlayerName = "";
	$BanReason = "";
	$SubmitterName = "";
	$Email = "";
	$SID = -1;
}
else
{
	$SteamID = trim(htmlspecialchars($_POST['SteamID']));
	$BanIP = trim(htmlspecialchars($_POST['BanIP']));
	$PlayerName = htmlspecialchars($_POST['PlayerName']);
	$BanReason = htmlspecialchars($_POST['BanReason']);
	$SubmitterName = htmlspecialchars($_POST['SubmitName']);
	$Email = trim(htmlspecialchars($_POST['EmailAddr']));
	$SID = (int)$_POST['server'];
	$validsubmit = true;
	$errors = "";
	if((strlen($SteamID)!=0 && $SteamID != "STEAM_0:") && !validate_steam($SteamID))
	{
		$errors .= '* Введите реальный STEAM ID.<br>';
		$validsubmit = false;
	}
	if(strlen($BanIP)!=0 && !validate_ip($BanIP))
	{
		$errors .= '* Введите реальный IP-address.<br>';
		$validsubmit = false;
	}
	if (strlen($PlayerName) == 0)
	{
		$errors .= '* Вы должны ввести ник игрока<br>';
		$validsubmit = false;
	}
	if (strlen($BanReason) == 0)
	{
		$errors .= '* Вы должны ввести причину бана<br>';
		$validsubmit = false;
	}
	if (!check_email($Email))
	{
		$errors .= '* Вы должны ввести ваш E-mail<br>';
		$validsubmit = false;
	}
	if($SID == -1)
	{
		$errors .= '* Выберите сервер.<br>';
		$validsubmit = false;
	}
	if(!empty($_FILES['demo_file']['name']))
	{
		if(!CheckExt($_FILES['demo_file']['name'], "zip") && !CheckExt($_FILES['demo_file']['name'], "rar") && !CheckExt($_FILES['demo_file']['name'], "dem") &&
		   !CheckExt($_FILES['demo_file']['name'], "7z") && !CheckExt($_FILES['demo_file']['name'], "bz2") && !CheckExt($_FILES['demo_file']['name'], "gz"))
		{
			$errors .= '* Формат файла должен быть zip, rar, 7z, bz2 или gz.<br>';
			$validsubmit = false;
		}
	}
	$checkres = $GLOBALS['db']->Execute("SELECT length FROM ".DB_PREFIX."_bans WHERE authid = ? AND RemoveType IS NULL", array($SteamID));
	$numcheck = $checkres->RecordCount();
	if($numcheck == 1 && $checkres->fields['length'] == 0)
	{
		$errors .= '* Игрок уже забанен навсегда.<br>';
		$validsubmit = false;
	}


	if(!$validsubmit)
		CreateRedBox("Ошибка", $errors);

	if ($validsubmit)
	{
		$filename = md5($SteamID.time());
		//echo SB_DEMOS."/".$filename;
		$demo = move_uploaded_file($_FILES['demo_file']['tmp_name'],SB_DEMOS."/".$filename);
		if($demo || empty($_FILES['demo_file']['name']))
		{
			if($SID!=0) {
				$res = $GLOBALS['db']->GetRow("SELECT ip, port FROM ".DB_PREFIX."_servers WHERE sid = $SID");
				
				$sinfo->Connect($res[0],$res[1]);
				
				$info = $sinfo->GetInfo();
				if($info)
					$mailserver = "Сервер: " . $info['HostName'] . " (" . $res[0] . ":" . $res[1] . ")\n";
				else
					$mailserver = "Сервер: Ошибка соединения (" . $res[0] . ":" . $res[1] . ")\n";
				$modid = $GLOBALS['db']->GetRow("SELECT m.mid FROM `".DB_PREFIX."_servers` as s LEFT JOIN `".DB_PREFIX."_mods` as m ON m.mid = s.modid WHERE s.sid = '".$SID."';");
			} else {
				$mailserver = "Сервер: Другой сервер\n";
				$modid[0] = 0;
			}
			if($SteamID == "STEAM_0:") $SteamID = "";
			$pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_submissions(submitted,SteamId,name,email,ModID,reason,ip,subname,sip,archiv,server) VALUES (UNIX_TIMESTAMP(),?,?,?,?,?,?,?,?,0,?)");
			$GLOBALS['db']->Execute($pre,array($SteamID,$PlayerName,$Email,$modid[0],$BanReason, $_SERVER['REMOTE_ADDR'], $SubmitterName, $BanIP, $SID));
			$subid = (int)$GLOBALS['db']->Insert_ID();

			if(!empty($_FILES['demo_file']['name']))
				$GLOBALS['db']->Execute("INSERT INTO ".DB_PREFIX."_demos(demid,demtype,filename,origname) VALUES (?, 'S', ?, ?)", array($subid, $filename, $_FILES['demo_file']['name']));
			$SteamID = "";
			$BanIP = "";
			$PlayerName = "";
			$BanReason = "";
			$SubmitterName = "";
			$Email = "";
			$SID = -1;

			// Send an email when ban was posted
			$headers = 'From: submission@' . $_SERVER['HTTP_HOST'] . "\n" . 'X-Mailer: PHP/' . phpversion();

			$admins = $userbank->GetAllAdmins();
			$requri = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], ".php")-5);
			foreach($admins AS $admin)
			{
				$message = "";
				$message .= "Приветствую, " . $admin['user'] . ",\n\n";
				$message .= "Поступила новая жалоба на игрока в вашей системе SourceBans:\n\n";
				$message .= "Игрок: ".$_POST['PlayerName']." (".$_POST['SteamID'].")\nДемо: ".(empty($_FILES['demo_file']['name'])?'Отсутствует':'Присутствует (http://' . $_SERVER['HTTP_HOST'] . $requri . 'getdemo.php?type=S&id='.$subid.')')."\n".$mailserver."Причина: ".$_POST['BanReason']."\n\n";
				$message .= "Кликните по ссылке для просмотра жалобы.\n\nhttp://" . $_SERVER['HTTP_HOST'] . $requri . "index.php?p=admin&c=bans#^2";
				if($userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_SUBMISSIONS, $admin['aid']) && $userbank->HasAccess(ADMIN_NOTIFY_SUB, $admin['aid']))
					mail($admin['email'], "[SourceBans] Добавлена жалоба на игрока", $message, $headers);
			}
			CreateGreenBox("Успешно", "Ваша жалоба была добавлена в базу данных, и будет рассмотрена одним из админов");
		}
		else
		{
			CreateRedBox("Ошибка", "Ошибка загрузки демо. попробуйте позже.");
			$log = new CSystemLog("e", "Ошибка загрузки демо", "Ошибка загрузки демо для заявки на бан от (". $Email . ")");
		}
	}
}

//$mod_list = $GLOBALS['db']->GetAssoc("SELECT mid,name FROM ".DB_PREFIX."_mods WHERE `mid` > 0 AND `enabled`= 1 ORDER BY mid ");
//serverlist
$server_list = $GLOBALS['db']->Execute("SELECT sid, ip, port FROM `" . DB_PREFIX . "_servers` WHERE enabled = 1 ORDER BY modid, sid");
$servers = array();
while (!$server_list->EOF)
{
	$info = array();
	$sinfo->Connect($server_list->fields[1], $server_list->fields[2]);
	$info = $sinfo->GetInfo();
	if(!$info)
		$info['HostName'] = "Ошибка соединения (" . $server_list->fields[1] . ":" . $server_list->fields[2] . ")";
	$info['sid'] = $server_list->fields[0];
	array_push($servers,$info);
	$server_list->MoveNext();
}

$theme->assign('STEAMID',		$SteamID==""?"STEAM_0:":$SteamID);
$theme->assign('ban_ip',			$BanIP);
$theme->assign('ban_reason',	$BanReason);
$theme->assign('player_name',	$PlayerName);
$theme->assign('subplayer_name',$SubmitterName);
$theme->assign('player_email',	$Email);
$theme->assign('server_list',		$servers);
$theme->assign('server_selected',	$SID);

$theme->display('page_submitban.tpl');
?>
