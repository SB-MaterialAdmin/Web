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

// Check access rights: only owners and server adders may upload
if (empty($userbank) || !$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_SERVER)) {
	$userName = (empty($userbank)) ? 'Unknown' : $userbank->GetProperty('user');

	// Log suspicious attempt
	$log = new CSystemLog("w", "Попытка взлома", "{$userName} пытался загрузить изображение карты, не имея на это прав.");

	die('У вас нет доступа к этому!');
}

$message = sprintf("
	<br><strong>Обратите внимание!</strong>
	<br>Максимальный размер файла: %s
	<br>Максимальное кол-во файлов для загрузки: %s<br><br>",
	ini_get('upload_max_filesize'), ini_get('max_file_uploads')
);

$alerts = [];
$extensions = array_map('strtoupper', ALLOW_GAMEMAPS_EXT);
$extText = implode(", ", $extensions);

if (isset($_POST['upload'])) {
	$fls = normalize_files_array($_FILES);

	foreach ($fls['mapimg_file'] as $curfile) {
		// 1. Check for upload errors
		if ($curfile['error'] != 0) {
			$reason = getReasonByCode($curfile['error'], $extText);
			$alerts[] = sprintf("Не удалось загрузить файл %s. Причина: %s.", $curfile['name'], $reason);
			continue;
		}

		// 1.5 Check file size
		if ($curfile['size'] > MAX_GAMEMAPS_SIZE_BYTES) {
			$alerts[] = sprintf(
				"Файл %s превышает максимальный размер (%d MB).",
				$curfile['name'],
				MAX_GAMEMAPS_SIZE_BYTES / 1024 / 1024
			);

			$log = new CSystemLog(
				"w",
				"Слишком большой файл",
				"Попытка загрузить файл большого размера: " . $curfile['name']
			);

			continue;
		}

		// 2. Verify that the file is a genuine image (JPEG, PNG, or WebP) via content check
		$check = @getimagesize($curfile['tmp_name']);
		if ($check === false) {
			$alerts[] = sprintf("Файл %s не является допустимым изображением.", $curfile['name']);

			$log = new CSystemLog("w", "Подозрительная загрузка", "Не удалось определить тип файла: " . $curfile['name']);
			continue;
		}

		// 2.5 Check image dimensions
		[$width, $height, $type] = [$check[0], $check[1], $check[2]];

		if ($width > MAX_GAMEMAPS_WIDTH || $height > MAX_GAMEMAPS_HEIGHT) {
			$alerts[] = sprintf(
				"Изображение %s слишком большое (%dx%d). Максимум: %dx%d.",
				$curfile['name'],
				$width,
				$height,
				MAX_GAMEMAPS_WIDTH,
				MAX_GAMEMAPS_HEIGHT
			);

			$log = new CSystemLog(
				"w",
				"Слишком большое изображение",
				"Попытка загрузить изображение {$width}x{$height}: " . $curfile['name']
			);

			continue;
		}

		if (!array_key_exists($type, ALLOWED_GAMEMAPS_TYPES)) {
			$alerts[] = sprintf("Файл %s не является изображением в формате JPEG, PNG или WebP.", $curfile['name']);
			$log = new CSystemLog("w", "Подозрительная загрузка", "Попытка загрузить неподдерживаемый тип: " . $curfile['name']);
			continue;
		}

		// 3. Determine correct extension
		$ext = ALLOWED_GAMEMAPS_TYPES[$type];

		// 4. Securely process the file name
		// 4.1 Strip any path information (prevents directory traversal)
		$original_basename = basename($curfile['name']);

		// 4.2 Remove any character that is not a letter, digit, dot, dash or underscore
		//     This eliminates any possible HTML tags or unsafe symbols.
		$clean_name = preg_replace('/[^a-zA-Z0-9._-]/', '', $original_basename);

		// 4.3 Check if the original name contained unsafe characters
		if ($clean_name !== $original_basename) {
			$alerts[] = sprintf("Имя файла %s содержит недопустимые символы. Загрузка отклонена.", $original_basename);
			$log = new CSystemLog("w", "Подозрительное имя файла", "Попытка загрузить файл с недопустимыми символами: " . $original_basename);
			continue;
		}

		// 4.4 If the name becomes empty after cleaning (should not happen if check passed, but just in case)
		if (empty($clean_name)) {
			$alerts[] = sprintf("Имя файла %s стало пустым после очистки. Загрузка отклонена.", $original_basename);
			$log = new CSystemLog("w", "Пустое имя файла", "Имя файла после очистки оказалось пустым: " . $original_basename);
			continue;
		}

		// 4.5 Replace any extension with the correct one
		$filename = pathinfo($clean_name, PATHINFO_FILENAME) . '.' . $ext;

		// 5. Build the full destination path
		$destination = SB_MAP_LOCATION . '/' . $filename;

		// 6. Prevent overwriting existing files – reject upload if file already exists
		if (file_exists($destination)) {
			$alerts[] = sprintf("Файл с именем %s уже существует. Загрузка отклонена.", $filename);
			continue;
		}

		// 7. Move the uploaded file to its destination
		if (!move_uploaded_file($curfile['tmp_name'], $destination)) {
			$alerts[] = sprintf("Не удалось сохранить файл %s.", $curfile['name']);
			continue;
		}

		// 8. Optional: re-encode the image using GD to strip any malicious code from metadata
		if (!reencodeImage($destination, $type)) {
			unlink($destination);
			$alerts[] = sprintf("Файл %s повреждён или содержит некорректные данные.", $curfile['name']);
			continue;
		}

		// 9. Log successful upload
		$log = new CSystemLog("m", "Изображение карты загружено", "Новое изображение карты загружено: " . $filename);
		$alerts[] = sprintf("Файл %s загружен как %s.", $curfile['name'], $filename);
	}

	// XSS
    $alertsSafe = json_encode(implode("\n", $alerts), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    // Prepare JS alert
    $message .= '<script>alert(' . $alertsSafe . '); self.close();</script>';
}

// XSS
$extText = RemoveCode($extText);

// Assign template variables
$theme->assign("title", "Загрузить изображение карты");
$theme->assign("message", $message);
$theme->assign("input_name", "mapimg_file[]");
$theme->assign("form_name", "mapimgup");
$theme->assign("formats", $extText);

$theme->display('page_uploadfile.tpl');
