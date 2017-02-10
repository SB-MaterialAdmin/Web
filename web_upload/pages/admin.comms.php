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
if(isset($GLOBALS['IN_ADMIN']))define('CUR_AID', $userbank->GetAid());


if(isset($_GET["rebanid"]))
{
	echo '<script type="text/javascript">xajax_PrepareReblock("'.$_GET["rebanid"].'");</script>';
}elseif(isset($_GET["blockfromban"]))
{
	echo '<script type="text/javascript">xajax_PrepareBlockFromBan("'.$_GET["blockfromban"].'");</script>';
}elseif((isset($_GET['action']) && $_GET['action'] == "pasteBan") && isset($_GET['pName']) && isset($_GET['sid'])) {
	echo "<script type=\"text/javascript\">setTimeout(\"ShowBox('Загрузка..','<i>Подождите!</i>', 'blue', '', false, 5000);\", 800);xajax_PastePlayerData('".(int)$_GET['sid']."', '".addslashes($_GET['pName'])."');</script>";
}

echo '<div id="admin-page-content">';
	// Add Block
	echo '<div id="0" style="display:none;">';
		$theme->assign('permission_addban', $userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN));
		$theme->display('page_admin_comms_add.tpl');
	echo '</div>';
?>

<script type="text/javascript">
function changeReason(szListValue)
{
	$('dreason').style.display = (szListValue == "other" ? "block" : "none");
	$('txtReason').focus();
}
function ProcessBan()
{
	var err = 0;
	var reason = $('listReason')[$('listReason').selectedIndex].value;

	if (reason == "other")
		reason = $('txtReason').value;

	if(!$('nickname').value)
	{
		$('nick.msg').setHTML('Введите ник игрока, которому хотите добавить блокировку');
		$('nick.msg').setStyle('display', 'block');
		err++;
	}else
	{
		$('nick.msg').setHTML('');
		$('nick.msg').setStyle('display', 'none');
	}

	if($('steam').value.length < 10)
	{
		$('steam.msg').setHTML('Введите реальный STEAM ID или Community ID');
		$('steam.msg').setStyle('display', 'block');
		err++;
	}else
	{
		$('steam.msg').setHTML('');
		$('steam.msg').setStyle('display', 'none');
	}

	if(!reason)
	{
		$('reason.msg').setHTML('Выберите причину блокировки.');
		$('reason.msg').setStyle('display', 'block');
		err++;
	}else
	{
		$('reason.msg').setHTML('');
		$('reason.msg').setStyle('display', 'none');
	}

	if(err)
		return 0;

	xajax_AddBlock($('nickname').value,
				 $('type').value,
				 $('steam').value,
				 $('banlength').value,
				 reason);
}
</script>
</div>
