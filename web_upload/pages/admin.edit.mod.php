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
global $theme, $userbank;
if(!isset($_GET['id']))
{
	echo '<script>ShowBox("Ошибка", "Нет модов для редактирования", "red", "", true);</script>';	
	PageDie();
}
if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_MODS))
{
	$log = new CSystemLog("w", "Попытка взлома", $userbank->GetProperty("user") . " пытался изменить МОД, не имея на это прав.");
	echo '<div id="msg-red" >
	<i><img src="./images/warning.png" alt="Внимание" /></i>
	<b>Ошибка</b>
	<br />
	У вас нет прав редактирования МОДов.
</div>';
	PageDie();
}

$_GET['id'] = (int)$_GET['id'];
$res = $GLOBALS['db']->GetRow("
    				SELECT name, modfolder, icon, enabled, steam_universe
    				FROM ".DB_PREFIX."_mods
    				WHERE mid = ?", array($_GET['id']));

$errorScript = "";

if(isset($_POST['name']))
{
	// Form validation
	$error = 0;
	
	if(empty($_POST['name']))
	{
		$error++;
		$errorScript .= "$('name.msg').innerHTML = 'Введите имя МОДа.';";
		$errorScript .= "$('name.msg').setStyle('display', 'block');";
	}
	else
	{
		// Already there?
		$check = $GLOBALS['db']->GetRow("SELECT * FROM `" . DB_PREFIX . "_mods` WHERE name = ? AND mid != ?;", array($_POST['name'], $_GET['id']));
		if(!empty($check))
		{
			$error++;
			$errorScript .= "$('name.msg').innerHTML = 'МОД с таким именем уже существует.';";
			$errorScript .= "$('name.msg').setStyle('display', 'block');";
		}
	}
	if(empty($_POST['folder']))
	{
		$error++;
		$errorScript .= "$('folder.msg').innerHTML = 'Введите имя папки МОДа.';";
		$errorScript .= "$('folder.msg').setStyle('display', 'block');";
	}
	else
	{
		// Already there?
		$check = $GLOBALS['db']->GetRow("SELECT * FROM `" . DB_PREFIX . "_mods` WHERE modfolder = ? AND mid != ?;", array($_POST['folder'], $_GET['id']));
		if(!empty($check))
		{
			$error++;
			$errorScript .= "$('folder.msg').innerHTML = 'Мод использующий эту папку уже существует.';";
			$errorScript .= "$('folder.msg').setStyle('display', 'block');";
		}
	}

	$name = htmlspecialchars(strip_tags($_POST['name']));//don't want to addslashes because execute will automatically do it
	$icon = htmlspecialchars(strip_tags($_POST['icon_hid']));
	$folder = htmlspecialchars(strip_tags($_POST['folder']));
	$enabled = ($_POST['enabled'] == 'on' ? 1 : 0);
	$steam_universe = (int)$_POST['steam_universe'];
	
	if($error == 0)
	{
		if($res['icon']!=$_POST['icon_hid'])
			@unlink(SB_ICONS."/".$res['icon']);
			
		$edit = $GLOBALS['db']->Execute("UPDATE ".DB_PREFIX."_mods SET
										`name` = ?, `modfolder` = ?, `icon` = ?, `enabled` = ?, `steam_universe` = ?
										WHERE `mid` = ?", array($name, $folder, $icon, $enabled, $steam_universe, $_GET['id']));
		echo '<script>ShowBox("МОД обновлен", "МОД был успешно обновлен", "green", "index.php?p=admin&c=mods");</script>';
	}
	
	// put into array to display new values after submit
	$res['name'] = $name;
	$res['modfolder'] = $folder;
	$res['icon'] = $icon;
	$res['enabled'] = $enabled;
	$res['steam_universe'] = $steam_universe;
}
if(!$res)
	echo '<script>ShowBox("Ошибка", "Возникла ошибка получения деталей. Возможно мод был удален?", "red", "index.php?p=admin&c=mod");</script>';

$theme->assign('mod_icon', $res['icon']);
$theme->assign('folder', $res['modfolder']);
$theme->assign('name', $res['name']);
$theme->assign('steam_universe', $res['steam_universe']);
?>


<div id="admin-page-content">
<div id="1">
<?php $theme->display('page_admin_edit_mod.tpl'); ?>
<script>
$('enabled').checked = <?php echo (int)$res['enabled'] ?>;
</script>
</div>
</div>
<script type="text/javascript">window.addEvent('domready', function(){
<?php echo $errorScript; ?>
});
</script>
