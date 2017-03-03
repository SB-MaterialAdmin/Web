<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function Maintenance($type) {
    global $userbank, $username, $theme;

    $objResponse = new xajaxResponse();
    if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_WEB_SETTINGS)) {
        ShowBox_ajx("Ошибка", "Вы не имеете прав для выполнения данного действия!", "red", "", true, $objResponse);
        new CSystemLog("w", "Ошибка доступа", $usernake . " пытался произвести операцию по обслуживанию системы, не имея на это прав.");
        return $objResponse;
    }

    switch($type) {
        case "themecache": {
            $theme->clear_compiled_tpl();
            ShowBox_ajx("Успех", "Кеш шаблона очищен успешно.", "green", "", true, $objResponse);
            break;
        }

        case "avatarcache": {
            $GLOBALS['db']->Execute(sprintf("TRUNCATE `%s_avatars`", DB_PREFIX));
            ShowBox_ajx("Успех", "Кеш аватарок очищен успешно.", "green", "", true, $objResponse);
            break;
        }

        case "bansexpired": {
            $GLOBALS['db']->Execute(sprintf("DELETE FROM `%s_bans` WHERE `RemoveType` IS NOT NULL", DB_PREFIX));
            ShowBox_ajx("Успех", "Истёкшие баны удалены успешно.", "green", "", true, $objResponse);
            break;
        }

        case "commsexpired": {
            $GLOBALS['db']->Execute(sprintf("DELETE FROM `%s_comms` WHERE `RemoveType` IS NOT NULL", DB_PREFIX));
            ShowBox_ajx("Успех", "Истёкшие муты удалены успешно.", "green", "", true, $objResponse);
            break;
        }

        case "optimizebd": {
            $tables = $GLOBALS['db']->GetAll("SHOW TABLES;");
            foreach ($tables as &$table)
                $GLOBALS['db']->Execute(sprintf("OPTIMIZE TABLE `%s`;", $table[0]));

            ShowBox_ajx("Успех", "Оптимизация таблиц завершена.", "green", "", true, $objResponse);
            break;
        }

        case "cleancountrycache": {
            $GLOBALS['db']->Execute("UPDATE `sb_bans` SET `country` = NULL;");
            ShowBox_ajx("Успех", "Кеш стран банлиста очищен успешно.<br /><br /><span style=\"color: #f00;\">Внимание!</span> Это может отрицательно сказаться на первой загрузке каждой страницы Вашего банлиста. Рекомендуем произвести операцию \"Обновить кеш стран в банлисте\".", "green", "", true, $objResponse);
            break;
        }

        case "rehashcountries": {
            $bans = $GLOBALS['db']->GetAll("SELECT `bid`, `ip` FROM `" . DB_PREFIX . "_bans` WHERE `country` IS NULL or `country` = 'zz'");
            foreach ($bans as $ban) {
                $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_bans` SET `country` = " . $GLOBALS['db']->qstr(FetchIp($ban['ip'])) . " WHERE `bid` = " . (int)$ban['bid'] . ";");
            }

            ShowBox_ajx("Успех", "Операция обновлений стран в кеше завершена.", "green", "", true, $objResponse);
            break;
        }

        case "updatecountries": {
            if (!function_exists("zlib_decode")) {
                ShowBox_ajx("Ошибка", "Невозможно произвести обновление GeoIP базы: недоступна функция <em>gzuncompress</em>.", "red", "", true, $objResponse);
                return $objResponse;
            }

            $CountryFile = DATA_PATH . '/IpToCountry.csv';
            if (@is_writable($CountryFile)) {
                file_put_contents($CountryFile, zlib_decode(file_get_contents("http://software77.net/geo-ip/?DL=1&x=Download")));
                ShowBox_ajx("Успех", "Файл GeoIP базы обновлён.", "green", "", true, $objResponse);
            } else
                ShowBox_ajx("Ошибка", "Невозможно произвести обновление GeoIP базы: запись в файл <em>/data/IpToCountry.csv</em> запрещена. Установите права <b>777</b> на файл <em>/data/IpToCountry.csv</em>", "red", "", true, $objResponse);
            break;
        }

        case "warningsexpired": {
            $GLOBALS['db']->Execute(sprintf("DELETE FROM `%s_warns` WHERE `expires` < %d", DB_PREFIX, time()));
            ShowBox_ajx("Успех", "Все истёкшие и снятые предупреждения были успешно удалены.", "green", "", true, $objResponse);
            break;
        }

        case "avatarupdate": {
            Maintenance("avatarcache");
            $users = $GLOBALS['db']->GetAll(sprintf("SELECT `authid` FROM `%s_admins`", DB_PREFIX));
            foreach ($users as &$user)
                GetUserAvatar($user['authid']);
            ShowBox_ajx("Успех", "Кеш аватаров Администраторов обновлён.", "green", "", true, $objResponse);
            break;
        }

        case "commentsclean": {
            $GLOBALS['db']->Execute(sprintf("TRUNCATE `%s_comments`;", DB_PREFIX));
            ShowBox_ajx("Успех", "Все комментарии были успешно удалены.", "green", "", true, $objResponse);
            break;
        }

        case "banlogclean": {
            $GLOBALS['db']->Execute(sprintf("TRUNCATE `%s_banlog`;", DB_PREFIX));
            ShowBox_ajx("Успех", "История заблокированных соединений к серверам успешно очищена.", "green", "", true, $objResponse);
            break;
        }

        case "protests": {
            $GLOBALS['db']->Execute(sprintf("TRUNCATE `%s_protests`;", DB_PREFIX));
            ShowBox_ajx("Успех", "Протесты успешно удалены.", "green", "", true, $objResponse);
            break;
        }

        case "reports": {
            $GLOBALS['db']->Execute(sprintf("TRUNCATE `%s_submissions`;", DB_PREFIX));
            ShowBox_ajx("Успех", "Предложения бана (репорты) успешно удалены.", "green", "", true, $objResponse);
            break;
        }

        default: {
            ShowBox_ajx("Ошибка", "Неизвестная операция", "red", "", true, $objResponse);
            break;
        }
    }

    return $objResponse;
}

function CheckVersion() {
    $objResponse = new xajaxResponse();
    $relver = @file_get_contents("https://raw.githubusercontent.com/CrazyHackGUT/SB_Material_Design/" . MA_BRANCH . "/updates.json");
    $version = 0;

    if (strlen($relver)<8 || $relver == "") {
        $version = "<span style='color:#aa0000;'>Ошибка</span>";
        $msg = "<span style='color:#aa0000;'><strong>Ошибка получения обновлений</strong></span>";
    } else {
        $reldata = json_decode($relver);
        $version = $reldata->release;

        if(version_compare($reldata->release, theme_version, ">")) {
            $VersionInformation  = "<div style=\"text-align: left\">";
            foreach ($reldata->changes as $change)
                $VersionInformation .= "<strong>*</strong> ".$change."<br />";
            $VersionInformation .= "И многое другое...</div><br />";

            $msg = "<span style='color:#aa0000;'><strong>Доступна новая версия.</strong></span> <a href ='#' onClick='" . generateMsgBoxJS("Доступна новая версия!", $VersionInformation . "<a href=\"" . $reldata->download_url . "\">Скачать</a> / <a href=\"" . $reldata->changelist . "\">Список изменений</a>", "red", "", true) . "'>Подробнее...</a>";
        } else
            $msg = "<span style='color:#00aa00;'><strong>Вы используете последнюю версию</strong></span>";
    }

    $objResponse->addAssign("relver", "innerHTML",  sprintf("%s (%s)", $version, $msg));
    return $objResponse;
}
