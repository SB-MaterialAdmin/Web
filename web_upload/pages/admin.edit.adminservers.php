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
if(!isset($_GET['id']))
{
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Ошибка</b>
	<br />
	ID администратора не указан
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
	Ошибка получения текущих данных.
</div>';
	PageDie();
}

$aid = (int)$_GET['id'];
if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ADMINS))
{
	$log = new CSystemLog("w", "Попытка взлома", $userbank->GetProperty("user") . " пытался изменить доступ к серверу админа ".$userbank->GetProperty('user', $_GET['id']).", не имея на это прав.");
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Внимание" /></i>
	<b>Ошибка</b>
	<br />
	Вы не имеете прав редактирования доступа админа к серверу.
</div>';
	PageDie();
}

$servers = $GLOBALS['db']->GetAll("SELECT `server_id`, `srv_group_id` FROM ".DB_PREFIX."_admins_servers_groups WHERE admin_id = ". (int)$aid);
$adminGroup = $GLOBALS['db']->GetAll('SELECT id FROM '.DB_PREFIX.'_srvgroups sg, '.DB_PREFIX.'_admins a WHERE sg.name = a.srv_group and a.aid = ? limit 1', array($aid));

$server_grp = isset($adminGroup[0]['id'])?$adminGroup[0]['id']:0;

	
if(isset($_POST['editadminserver']))
{
	
	// clear old stuffs
	$GLOBALS['db']->Execute("DELETE FROM ".DB_PREFIX."_admins_servers_groups WHERE admin_id = {$aid}");
	if(isset($_POST['servers']) && is_array($_POST['servers']) && count($_POST['servers']) > 0) {
		foreach($_POST['servers'] AS $s)
		{
			$pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_admins_servers_groups(admin_id,group_id,srv_group_id,server_id) VALUES (?,?,?,?)");
			$GLOBALS['db']->Execute($pre,array($aid,
											   $server_grp,
											   -1,
											   (int)substr($s,1)));
		}
	}
	if(isset($_POST['group']) && is_array($_POST['group']) && count($_POST['group']) > 0) {
		foreach($_POST['group'] AS $g)
		{
			$pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_admins_servers_groups(admin_id,group_id,srv_group_id,server_id) VALUES (?,?,?,?)");
			$GLOBALS['db']->Execute($pre,array($aid,
											   $server_grp,
											   (int)substr($g,1),
											   -1));
		}
	}
	if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1)
	{
		// rehash the admins on the servers
		$serveraccessq = $GLOBALS['db']->GetAll("SELECT s.sid FROM `".DB_PREFIX."_servers` s
												LEFT JOIN `".DB_PREFIX."_admins_servers_groups` asg ON asg.admin_id = '".(int)$aid."'
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
		
		// Add all servers, he's been admin on before
		foreach($servers as $server)
		{
			if($server['server_id'] != "-1" && !in_array((int)$server['server_id'], $allservers)) {
				$allservers[] = (int)$server['server_id'];
			}
			
			// old server groups
			$serv_in_grp = $GLOBALS['db']->GetAll('SELECT server_id FROM `'.DB_PREFIX.'_servers_groups` WHERE group_id = ?;', array((int)$server['srv_group_id']));
			foreach($serv_in_grp as $srg)
			{
				if($srg['server_id'] != "-1" && !in_array((int)$srg['server_id'], $allservers)) {
					$allservers[] = (int)$srg['server_id'];
				}
			}
		}
		
		echo '<script>setTimeout(\'ShowRehashBox("'.implode(",", $allservers).'", "Серверный доступ администратора обновлен", "Серверный доступ администратора был успешно обновлен", "green", "index.php?p=admin&c=admins");TabToReload();\', 1200);</script>';
	} else
		echo '<script>setTimeout(\'ShowBox("Серверный доступ администратора обновлен", "Серверный доступ администратора был успешно обновлен", "green", "index.php?p=admin&c=admins");TabToReload();\', 1200);</script>';
	
	$admname = $GLOBALS['db']->GetRow("SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = ?", array((int)$aid));
	$log = new CSystemLog("m", "Администратор сервера обновлен", "Серверный доступ администратора (" . $admname['user'] . ") был изменен");
}


$server_list = 	$GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_servers`");
$group_list = 	$GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_groups` WHERE type = '3'");
$rowcount = 	(count($server_list)+count($group_list));

$theme->assign('row_count', $rowcount);
$theme->assign('group_list', $group_list);
$theme->assign('server_list', $server_list);
$theme->assign('assigned_servers', $servers);

$theme->display('page_admin_edit_admins_servers.tpl');
?>

