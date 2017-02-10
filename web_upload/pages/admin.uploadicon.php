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

include_once("../init.php");
include_once("../includes/system-functions.php");
global $theme, $userbank;

if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_MODS|ADMIN_ADD_MODS))
{
    $log = new CSystemLog("w", "Попытка взлома", $userbank->GetProperty('user') . " пытался загрузить иконку МОДа, не имея на это прав.");
	echo 'У вас нет доступа к этому!';
	die();
}

$message = "";
if(isset($_POST['upload']))
{
	if(CheckExt($_FILES['icon_file']['name'], "gif") || CheckExt($_FILES['icon_file']['name'], "jpg") || CheckExt($_FILES['icon_file']['name'], "png"))
	{
		move_uploaded_file($_FILES['icon_file']['tmp_name'],SB_ICONS."/".$_FILES['icon_file']['name']);
		$message =  "<script>window.opener.icon('" . $_FILES['icon_file']['name'] . "');self.close()</script>";
        $log = new CSystemLog("m", "Иконка МОДа загружена", "Новая иконка МОДа загружена: ".htmlspecialchars($_FILES['icon_file']['name']));
	}
	else 
	{
		$message =  "<b> Файл должен быть формата gif, jpg или png.</b><br><br>";
	}
}

$theme->assign("title", "Загрузить иконку");
$theme->assign("message", $message);
$theme->assign("input_name", "icon_file");
$theme->assign("form_name", "iconup");
$theme->assign("formats", "GIF, PNG или JPG");

$theme->display('page_uploadfile.tpl');
?>
