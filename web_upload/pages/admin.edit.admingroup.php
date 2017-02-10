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
global $userbank, $theme;

if(!isset($_GET['id']))
{
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Ошибка</b>
	<br />
	ID администратора не указан.
</div>';
	PageDie();
}

$_GET['id'] = (int)$_GET['id'];
if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ADMINS))
{
	$log = new CSystemLog("w", "Попытка взлома", $userbank->GetProperty("user") . " пытался изменить группу админу ".$userbank->GetProperty('user', $_GET['id']).". не имея на это прав.");
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Внимание" /></i>
	<b>Ошибка</b>
	<br />
	Вы не имеете прав изменения групп админов.
</div>';
	PageDie();
}

if(!$userbank->GetProperty("user", $_GET['id']))
{
	$log = new CSystemLog("e", "Получение данных администратора не удалось", "Не могу найти данные для администратора с идентификатором '".$_GET['id']."'");
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Ошибка</b>
	<br />
	Ошибка получения текущих данных.</div>';
	PageDie();
}

// Form sent
if(isset($_POST['wg']) || isset($_GET['wg']) || isset($_GET['sg']))
{
	if(isset($_GET['wg'])) {
		$_POST['wg'] = $_GET['wg'];
	}
	if(isset($_GET['sg'])) {
		$_POST['sg'] = $_GET['sg'];
	}
	
	$_POST['wg'] = (int)$_POST['wg'];
	$_POST['sg'] = (int)$_POST['sg'];
	
	// Users require a password and email to have web permissions
	$password = $GLOBALS['userbank']->GetProperty('password', $_GET['id']);
	$email = $GLOBALS['userbank']->GetProperty('email', $_GET['id']);
	if($_POST['wg'] > 0 && (empty($password) || empty($email)))
	{
		echo '<script>setTimeout(function() { ShowBox("Ошибка", "Администраторы должны иметь пароль и адрес электронной почты для того, чтобы получить веб-разрешения.<br /><a href=\"index.php?p=admin&c=admins&o=editdetails&id=' . $_GET['id'] . '\" title=\"Редактировать детали Администратора\">Измените детали</a> сначала и попробуйте снова.", "red"); }, 1350);</script>';
	}
	else
	{
		if(isset($_POST['wg']) && $_POST['wg'] != "-2")	{
			if($_POST['wg'] == "-1")
				$_POST['wg'] = 0;
			
			// Edit the web group
			$edit = $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_admins SET
											`gid` = ?
											WHERE `aid` = ?;", array($_POST['wg'], $_GET['id']));
		}
		
		if(isset($_POST['sg']) && $_POST['sg'] != "-2") {
			// Edit the server admin group
			$group = "";
			if($_POST['sg'] != -1)
			{
				$grps = $GLOBALS['db']->GetRow("SELECT name FROM ".DB_PREFIX."_srvgroups WHERE id = ?;", array($_POST['sg']));
				if($grps)
					$group = $grps['name'];
			}
				
			$edit = $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_admins SET
											`srv_group` = ?
											WHERE aid = ?", array($group, $_GET['id']));
			
			$edit = $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_admins_servers_groups SET
										`group_id` = ?
										WHERE admin_id = ?;", array($_POST['sg'], $_GET['id']));
				
		}
		if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1)
		{
			// rehash the admins on the servers
			$serveraccessq = $GLOBALS['db']->GetAll("SELECT s.sid FROM `".DB_PREFIX."_servers` s
													LEFT JOIN `".DB_PREFIX."_admins_servers_groups` asg ON asg.admin_id = '".(int)$_GET['id']."'
													LEFT JOIN `".DB_PREFIX."_servers_groups` sg ON sg.group_id = asg.srv_group_id
													WHERE ((asg.server_id != '-1' AND asg.srv_group_id = '-1')
													OR (asg.srv_group_id != '-1' AND asg.server_id = '-1'))
													AND (s.sid IN(asg.server_id) OR s.sid IN(sg.server_id)) AND s.enabled = 1");
			$allservers = array();
			foreach($serveraccessq as $access) {
				if(!in_array($access['sid'], $allservers)) {
					$allservers[] = $access['sid'];
				}
			}
			echo '<script>setTimeout(\'ShowRehashBox("'.implode(",", $allservers).'", "Администратор обновлен", "Детали админа были успешно обновлены", "green", "index.php?p=admin&c=admins");TabToReload();\', 1350);</script>';
		}
		else
			echo '<script>setTimeout(\'ShowBox("Администратор обновлен", "Детали админа были успешно обновлены", "green", "index.php?p=admin&c=admins");TabToReload();\', 1350);</script>';
		
		$admname = $GLOBALS['db']->GetRow("SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = ?", array((int)$_GET['id']));
		$log = new CSystemLog("m", "Группа админа обновлена", "Группа админа (" . $admname['user'] . ") была обновлена");
	}
}

$wgroups = $GLOBALS['db']->GetAll("SELECT gid, name FROM ".DB_PREFIX."_groups WHERE type != 3");
$sgroups = $GLOBALS['db']->GetAll("SELECT id, name FROM ".DB_PREFIX."_srvgroups");

$server_admin_group = $userbank->GetProperty('srv_groups', $_GET['id']);
foreach($sgroups as $sg)
{
	if($sg['name'] == $server_admin_group)
	{
		$server_admin_group = (int)$sg['id'];
		break;
	}
}

$theme->assign('group_admin_name', $userbank->GetProperty("user", $_GET['id']));
$theme->assign('group_admin_id', $userbank->GetProperty("gid", $_GET['id']));
$theme->assign('group_lst',  $sgroups);
$theme->assign('web_lst',  $wgroups);
$theme->assign('server_admin_group_id',  $server_admin_group);

$theme->display('page_admin_edit_admins_group.tpl');
?>
