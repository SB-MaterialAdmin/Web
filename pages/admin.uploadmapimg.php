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
