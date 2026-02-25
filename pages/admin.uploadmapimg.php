<?php
// *************************************************************************
//  This file is part of SourceBans++.
//  (C) reserved author rights as in original.
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

if (isset($_POST['upload'])) {
    $fls = normalize_files_array($_FILES);
    $message = '<script>alert("';

    foreach ($fls['mapimg_file'] as $curfile) {
        // 1. Check for upload errors
        if ($curfile['error'] != 0) {
            $message .= sprintf("Не удалось загрузить файл %s. Причина: %s.",
                $curfile['name'], getReasonByCode($curfile['error'], "JPG"));
            $message .= "\\n";
            continue;
        }

        // 2. Verify that the file is a genuine image (JPEG, PNG, or WebP) via content check
        $check = @getimagesize($curfile['tmp_name']);
        if ($check === false) {
            $message .= sprintf("Файл %s не является допустимым изображением.", $curfile['name']);
            $message .= "\\n";
            $log = new CSystemLog("w", "Подозрительная загрузка", "Не удалось определить тип файла: " . $curfile['name']);
            continue;
        }

        $allowed_types = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];
        if (!in_array($check[2], $allowed_types)) {
            $message .= sprintf("Файл %s не является изображением в формате JPEG, PNG или WebP.", $curfile['name']);
            $message .= "\\n";
            $log = new CSystemLog("w", "Подозрительная загрузка", "Попытка загрузить неподдерживаемый тип: " . $curfile['name']);
            continue;
        }

        // 3. Determine the correct extension based on the image type
        $image_type = $check[2];
        switch ($image_type) {
            case IMAGETYPE_JPEG: $ext = 'jpg'; break;
            case IMAGETYPE_PNG:  $ext = 'png'; break;
            case IMAGETYPE_WEBP: $ext = 'webp'; break;
            default: continue 2; // Should never happen
        }

        // 4. Securely process the file name
        // 4.1 Strip any path information (prevents directory traversal)
        $original_basename = basename($curfile['name']);
        
        // 4.2 Remove any character that is not a letter, digit, dot, dash or underscore
        //     This eliminates any possible HTML tags or unsafe symbols.
        $clean_name = preg_replace('/[^a-zA-Z0-9._-]/', '', $original_basename);
        
        // 4.3 Check if the original name contained unsafe characters
        if ($clean_name !== $original_basename) {
            $message .= sprintf("Имя файла %s содержит недопустимые символы. Загрузка отклонена.", htmlspecialchars($original_basename));
            $message .= "\\n";
            $log = new CSystemLog("w", "Подозрительное имя файла", "Попытка загрузить файл с недопустимыми символами: " . $original_basename);
            continue;
        }

        // 4.4 If the name becomes empty after cleaning (should not happen if check passed, but just in case)
        if (empty($clean_name)) {
            $message .= sprintf("Имя файла %s стало пустым после очистки. Загрузка отклонена.", htmlspecialchars($original_basename));
            $message .= "\\n";
            $log = new CSystemLog("w", "Пустое имя файла", "Имя файла после очистки оказалось пустым: " . $original_basename);
            continue;
        }

        // 4.5 Replace any extension with the correct one
        $filename = pathinfo($clean_name, PATHINFO_FILENAME) . '.' . $ext;

        // 5. Build the full destination path
        $destination = SB_MAP_LOCATION . '/' . $filename;

        // Prepare safe versions for output (to prevent XSS in messages)
        $fileNameSafe = htmlspecialchars($filename);
        $origNameSafe = htmlspecialchars($curfile['name']);

        // 6. Prevent overwriting existing files – reject upload if file already exists
        if (file_exists($destination)) {
            $message .= sprintf("Файл с именем %s уже существует. Загрузка отклонена.", $fileNameSafe);
            $message .= "\\n";
            continue;
        }

        // 7. Move the uploaded file to its destination
        if (move_uploaded_file($curfile['tmp_name'], $destination)) {
            // 8. Optional: re-encode the image using GD to strip any malicious code from metadata
            $reencoded = false;
            if (function_exists('imagecreatefromjpeg') && function_exists('imagejpeg') ||
                function_exists('imagecreatefrompng') && function_exists('imagepng') ||
                function_exists('imagecreatefromwebp') && function_exists('imagewebp')) {

                $img = null;
                switch ($image_type) {
                    case IMAGETYPE_JPEG:
                        if (function_exists('imagecreatefromjpeg')) $img = @imagecreatefromjpeg($destination);
                        break;
                    case IMAGETYPE_PNG:
                        if (function_exists('imagecreatefrompng')) $img = @imagecreatefrompng($destination);
                        break;
                    case IMAGETYPE_WEBP:
                        if (function_exists('imagecreatefromwebp')) $img = @imagecreatefromwebp($destination);
                        break;
                }

                if ($img) {
                    // Save back with appropriate quality/compression
                    switch ($image_type) {
                        case IMAGETYPE_JPEG:
                            imagejpeg($img, $destination, 90);
                            break;
                        case IMAGETYPE_PNG:
                            // Compression level 9 = maximum (safe, no quality loss)
                            imagepng($img, $destination, 9);
                            break;
                        case IMAGETYPE_WEBP:
                            imagewebp($img, $destination, 80); // quality 80
                            break;
                    }
                    imagedestroy($img);
                    $reencoded = true;
                } else {
                    // The file could not be opened as a valid image – delete it and report error
                    unlink($destination);
                    $message .= sprintf("Файл %s повреждён или содержит некорректные данные.", $origNameSafe);
                    $message .= "\\n";
                    continue;
                }
            }

            // 9. Log successful upload
            $log = new CSystemLog("m", "Изображение карты загружено", "Новое изображение карты загружено: " . $fileNameSafe);
            $message .= sprintf("Файл %s загружен как %s.", $origNameSafe, $fileNameSafe);
        } else {
            $message .= sprintf("Не удалось сохранить файл %s.", $origNameSafe);
        }

        $message .= "\\n";
    }

    $message .= '"); self.close();</script>';
}

// Assign template variables
$theme->assign("title", "Загрузить изображение карты");
$theme->assign("message", $message);
$theme->assign("input_name", "mapimg_file[]");
$theme->assign("form_name", "mapimgup");
$theme->assign("formats", "JPG, PNG, WEBP");

$theme->display('page_uploadfile.tpl');
