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
header("Content-Type: text/html; charset=utf-8");
include_once("../init.php");
include_once("../includes/system-functions.php");
global $theme, $userbank;

if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_SERVER))
{
    $log = new CSystemLog("w", "Попытка взлома", $userbank->GetProperty('user') . " пытался загрузить изображение карты, не имея на это прав.");
	echo 'У вас нет доступа к этому!';
	die();
}

$message = sprintf("<br /><strong>Обратите внимание!</strong><br />Максимальный размер файла: %s<br />Максимальное кол-во файлов для загрузки: %s<br /><br />", ini_get('upload_max_filesize'), ini_get('max_file_uploads'));
if(isset($_POST['upload']))
{
	$fls = normalize_files_array($_FILES);

	$message = '<script>alert("';
	$fcount = count($fls['mapimg_file']);
	foreach ($fls['mapimg_file'] as $curfile) {
		if ($curfile['error'] != 0 || $curfile['type'] != "image/jpeg")
			$message .= sprintf("Не удалось загрузить файл %s. Причина: %s.", $curfile['name'], getReasonByCode(($curfile['type'] != "image/jpeg")?100500:$curfile['error'], "JPG"));
		else {
			move_uploaded_file($curfile['tmp_name'], SB_MAP_LOCATION."/".$curfile['name']);
			$log = new CSystemLog("m", "Изображение карты загружено", "Новое изображение карты загружено: ".htmlspecialchars($curfile['name']));
			$message .= sprintf("Файл %s загружен.", $curfile['name']); // $curfile['name']
		}
		$message .= "\\n";
	}
	$message .= '"); self.close();</script>';
}

$theme->assign("title", "Загрузить изображение карты");
$theme->assign("message", $message);
$theme->assign("input_name", "mapimg_file[]");
$theme->assign("form_name", "mapimgup");
$theme->assign("formats", "JPG");

$theme->display('page_uploadfile.tpl');
?>
