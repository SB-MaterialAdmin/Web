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
	Идентефикатор сервера не указан
</div>';
	die();
}
$_GET['id'] = (int)$_GET['id'];

$server = $GLOBALS['db']->GetRow("SELECT * FROM ".DB_PREFIX."_servers WHERE sid = {$_GET['id']}");
if(!$server)
{
	$log = new CSystemLog("e", "Получение данных сервера не удалось", "Не удается найти данные для сервера с идентификатором '".$_GET['id']."'");
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Warning" /></i>
	<b>Ошибка</b>
	<br />
	Ошибка получения текущих данных .
</div></div>';
	PageDie();
}

$errorScript = "";

if(isset($_POST['address']))
{
	// Form validation
	$error = 0;
	
	// ip
	if((empty($_POST['address'])))
	{
		$error++;
		$errorScript .= "$('address.msg').innerHTML = 'Ведите IP сервера.';";
		$errorScript .= "$('address.msg').setStyle('display', 'block');";
	}
	else
	{
		if(!validate_ip($_POST['address']) && !is_string($_POST['address']))
		{
			$error++;
			$errorScript .= "$('address.msg').innerHTML = 'Введите реальный IP.';";
			$errorScript .= "$('address.msg').setStyle('display', 'block');";
		}
	}
	
	// Port
	if((empty($_POST['port'])))
	{
		$error++;
		$errorScript .= "$('port.msg').innerHTML = 'Введите порт сервера.';";
		$errorScript .= "$('port.msg').setStyle('display', 'block');";
	}
	else
	{
		if(!is_numeric($_POST['port']))
		{
			$error++;
			$errorScript .= "$('port.msg').innerHTML = 'Введите реальный порт <b>номер</b>.';";
			$errorScript .= "$('port.msg').setStyle('display', 'block');";
		}
	}
	
	// rcon
	if($_POST['rcon'] != '*Скрыт*' && $_POST['rcon'] != $_POST['rcon2'])
	{
		$error++;
		$errorScript .= "$('rcon2.msg').innerHTML = 'Пароли не совпадают.';";
		$errorScript .= "$('rcon2.msg').setStyle('display', 'block');";
	}
	
	$ip = RemoveCode($_POST['address']);
	
	// Check for dublicates afterwards
	if($error == 0)
	{
		$chk = $GLOBALS['db']->GetRow('SELECT sid FROM `'.DB_PREFIX.'_servers` WHERE ip = ? AND port = ? AND sid != ?;', array($ip, (int)$_POST['port'], $_GET['id']));
		if($chk)
		{
			$error++;
			$errorScript .= "ShowBox('Ошибка', 'Сервер с такой комбинацией IP:Port уже существует.', 'red');";
		}
	}
	
	$enabled = (isset($_POST['enabled']) && $_POST['enabled'] == "on" ? 1 : 0);
	
	$server['ip'] = $ip;
	$server['port'] = (int)$_POST['port'];
	$server['modid'] = (int)$_POST['mod'];
	$server['enabled'] = $enabled;
	
	if($error == 0)
	{
		$grps = "";
		$sg = $GLOBALS['db']->GetAll("SELECT * FROM ".DB_PREFIX."_servers_groups WHERE server_id = {$_GET['id']}");
		foreach($sg AS $s)
		{
			$GLOBALS['db']->Execute("DELETE FROM ".DB_PREFIX."_servers_groups WHERE server_id = " . (int)$s['server_id'] . " AND group_id = " . (int)$s['group_id']);
		}
		if(!empty($_POST['groups'])) {
			foreach($_POST['groups'] as $t)
			{
				$addtogrp = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_servers_groups (`server_id`, `group_id`) VALUES (?,?)");
				$GLOBALS['db']->Execute($addtogrp,array($_GET['id'], (int)$t));
			}
		}
		
		$enabled = (isset($_POST['enabled']) && $_POST['enabled'] == "on" ? 1 : 0);
		
		$edit = $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET
										`ip` = ?,
										`port` = ?,
										`modid` = ?,
										`enabled` = ?
										WHERE `sid` = ?", array($ip, (int)$_POST['port'], (int)$_POST['mod'], $enabled, (int)$_GET['id']));

	// don't change rcon password if not changed
	if($_POST['rcon'] != '*Скрыт*')
	{
		$edit = $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_servers SET
										`rcon` = ?
										WHERE `sid` = ?", array($_POST['rcon'], (int)$_GET['id']));
	}
										
		echo "<script>setTimeout(\"ShowBox('Сервер обновлен', 'Данные сервера были успешно обновлены', 'green', 'index.php?p=admin&c=servers');TabToReload();\", 1200);</script>";
	}
}

$modlist = $GLOBALS['db']->GetAll("SELECT mid, name FROM `" . DB_PREFIX . "_mods` WHERE `mid` > 0 AND `enabled` = 1 ORDER BY name ASC");
$grouplist = $GLOBALS['db']->GetAll("SELECT gid, name FROM `" . DB_PREFIX . "_groups` WHERE type = 3 ORDER BY name ASC");

$theme->assign('ip', 	$server['ip']);
$theme->assign('port', 	 $server['port']);
$theme->assign('rcon', 	'*Скрыт*'); // Mh, some random string
$theme->assign('modid', 	$server['modid']);


$theme->assign('permission_addserver', $userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_SERVER));
$theme->assign('modlist', 	$modlist);
$theme->assign('grouplist', $grouplist);

$theme->assign('edit_server', true);
$theme->assign('submit_text', "Обновить данные");

echo '<form action="" method="post" name="editserver">';
$theme->display('page_admin_servers_add.tpl');
echo '</form>';

echo "<script>";
if(!isset($_POST['address']))
{
	$groups = $GLOBALS['db']->GetAll("SELECT group_id FROM `" . DB_PREFIX . "_servers_groups` WHERE server_id = {$_GET['id']}");
}
else
{
	if(isset($_POST['groups']) && is_array($_POST['groups']))
	{
		$groups = $_POST['groups'];
		foreach($groups as $k => $g)
		{
			$groups[$k] = array($g);
		}
	}
	else
	{
		$groups = array();
	}
}
foreach($groups AS $g)
{
	if($g)
		echo "if($('g_" . $g[0] . "')) $('g_" . $g[0] . "').checked = true;";
}
echo $errorScript;
?>

$('enabled').checked = <?php echo $server['enabled']; ?>;
if($('mod')) $('mod').value = <?php echo $server['modid']?>;
</script>

</div>
