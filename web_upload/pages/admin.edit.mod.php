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
