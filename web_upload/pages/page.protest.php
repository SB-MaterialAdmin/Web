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

global $userbank, $theme;
if($GLOBALS['config']['config.enableprotest']!="1")
{
	CreateRedBox("Ошибка", "Страница отключена.");
	PageDie();
}
if(!defined("IN_SB")){echo "Ошибка доступа!"; die();}
if (!isset($_POST['subprotest']) || $_POST['subprotest'] != 1)
{
	$Type = 0;
	$SteamID = "";
	$IP = "";
	$PlayerName = "";
	$UnbanReason = "";
	$Email = "";
}
else
{
	$Type = (int)$_POST['Type'];
	$SteamID = htmlspecialchars($_POST['SteamID']);
	$IP = htmlspecialchars($_POST['IP']);
	$PlayerName = htmlspecialchars($_POST['PlayerName']);
	$UnbanReason = htmlspecialchars($_POST['BanReason']);
	$Email = htmlspecialchars($_POST['EmailAddr']);
	$validsubmit = true;
	$errors = "";
	$BanId = -1;

	if(get_magic_quotes_gpc())
		$UnbanReason = stripslashes($UnbanReason);

	if($Type == 0 && !validate_steam($SteamID))
	{
		$errors .= '* Введите действительный STEAM ID.<br>';
		$validsubmit = false;
	}
	elseif($Type==0)
	{
		$pre = $GLOBALS['db']->Prepare("SELECT bid FROM ".DB_PREFIX."_bans WHERE authid=? AND RemovedBy IS NULL AND type=0;");
		$res = $GLOBALS['db']->Execute($pre,array($SteamID));
		if ($res->RecordCount() == 0)
		{
			$errors .=  '* Этот STEAM ID не забанен!<br>';
			$validsubmit = false;
		}
		else
		{
			$BanId = (int)$res->fields[0];
			$res = $GLOBALS['db']->Execute("SELECT pid FROM ".DB_PREFIX."_protests WHERE bid=$BanId");
			if ($res->RecordCount() > 0)
			{
				$errors .=  '* Бан этого STEAM ID уже был опротестован.<br>';
				$validsubmit = false;
			}
		}
	}
	if($Type == 1 && !validate_ip($IP))
	{
		$errors .= '* Введите действительныйd IP.<br>';
		$validsubmit = false;
	}
	elseif($Type==1)
	{
		$pre = $GLOBALS['db']->Prepare("SELECT bid FROM ".DB_PREFIX."_bans WHERE ip=? AND RemovedBy IS NULL AND type=1;");
		$res = $GLOBALS['db']->Execute($pre,array($IP));
		if ($res->RecordCount() == 0)
		{
			$errors .=  '* Этот IP не забанен!<br>';
			$validsubmit = false;
		}
		else
		{
			$BanId = (int)$res->fields[0];
			$res = $GLOBALS['db']->Execute("SELECT pid FROM ".DB_PREFIX."_protests WHERE bid=$BanId");
			if ($res->RecordCount() > 0)
			{
				$errors .=  '* Бан этого IP уже был опротестован.<br>';
				$validsubmit = false;
			}
		}
	}
	if (strlen($PlayerName) == 0)
	{
		$errors .=  '* Введите ник игрока<br>';
		$validsubmit = false;
	}
	if (strlen($UnbanReason) == 0)
	{
		$errors .=  '* Напишите пару строк коментария<br>';
		$validsubmit = false;
	}
	if (!check_email($Email))
	{
		$errors .=  '* Введите действительный адрес электронной почты<br>';
		$validsubmit = false;
	}

	if(!$validsubmit)
		CreateRedBox("Ошибка", $errors);

	if ($validsubmit && $BanId != -1)
	{
		$UnbanReason = trim($UnbanReason);
		$pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_protests(bid,datesubmitted,reason,email,archiv,pip) VALUES (?,UNIX_TIMESTAMP(),?,?,0,?)");
		$res = $GLOBALS['db']->Execute($pre,array($BanId, $UnbanReason,$Email,$_SERVER['REMOTE_ADDR']));
        $protid = $GLOBALS['db']->Insert_ID();
        $protadmin = $GLOBALS['db']->GetRow("SELECT ad.user FROM ".DB_PREFIX."_protests p, ".DB_PREFIX."_admins ad, ".DB_PREFIX."_bans b WHERE p.pid = '".$protid."' AND b.bid = p.bid AND ad.aid = b.aid");

		$Type = 0;
		$SteamID = "";
		$IP = "";
		$PlayerName = "";
		$UnbanReason = "";
		$Email = "";

		// Send an email when protest was posted
		$headers = 'From: protest@' . $_SERVER['HTTP_HOST'] . "\n" .
		'X-Mailer: PHP/' . phpversion();

		$emailinfo = $GLOBALS['db']->Execute("SELECT aid, user, email FROM `".DB_PREFIX."_admins` WHERE aid = (SELECT aid FROM `".DB_PREFIX."_bans` WHERE bid = '".(int)$BanId."');");
        $requri = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], ".php")+4);
		if(isset($GLOBALS['config']['protest.emailonlyinvolved']) && $GLOBALS['config']['protest.emailonlyinvolved'] == 1 && !empty($emailinfo->fields['email']))
			$admins = array(array('aid' => $emailinfo->fields['aid'], 'user' => $emailinfo->fields['user'], 'email' => $emailinfo->fields['email']));
		else
			$admins = $userbank->GetAllAdmins();
		foreach($admins AS $admin)
		{
			$message = "";
			$message .= "Здравствуйте " . $admin['user'] . ",\n\n";
			$message .= "Новый протест бана был опубликован на вашей странице SourceBans.\n\n";
			$message .= "Игрок: ".$_POST['PlayerName']." (".$_POST['SteamID'].")\nЗабаненый: ".$protadmin['user']."\nСообщение: ".$_POST['BanReason']."\n\n";
			$message .= "Кликните по ссылке чтобы увидеть протест бана.\n\nhttp://" . $_SERVER['HTTP_HOST'] . $requri . "?p=admin&c=bans#^1";
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_PROTESTS, $admin['aid']) && $userbank->HasAccess(ADMIN_NOTIFY_PROTEST, $admin['aid']))
				EMail($admin['email'], "[SourceBans] Добавлен протест бана", $message, $headers);
		}

		CreateGreenBox("Успешно", "Ваш протест был отправлен.");
	}
}

$theme->assign('steam_id', $SteamID);
$theme->assign('ip', $IP);
$theme->assign('player_name', $PlayerName);
$theme->assign('reason', $UnbanReason);
$theme->assign('player_email', $Email);

$theme->display('page_protestban.tpl');
?>
<script type="text/javascript">
function changeType(szListValue)
{
	$('steam.row').style.display = (szListValue == "0" ? "" : "none");
	$('ip.row').style.display    = (szListValue == "1" ? "" : "none");
}
$('Type').options[<?php echo $Type; ?>].selected = true;
changeType(<?php echo $Type; ?>);
</script>
