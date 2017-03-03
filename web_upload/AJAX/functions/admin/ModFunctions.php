<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function AddMod($name, $folder, $icon, $steam_universe, $enabled) {
    global $userbank, $username;
    $objResponse = new xajaxResponse();

    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_MODS)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался добавить МОД, не имея на это прав.");
        return $objResponse;
    }

    $name = htmlspecialchars(strip_tags($name));//don't want to addslashes because execute will automatically do it
    $icon = htmlspecialchars(strip_tags($icon));
    $folder = htmlspecialchars(strip_tags($folder));
    $steam_universe = (int)$steam_universe;
    $enabled = ($enabled == "on") ? 1 : 0;
    
    // Already there?
    $check = $GLOBALS['db']->GetRow("SELECT * FROM `" . DB_PREFIX . "_mods` WHERE modfolder = ? OR name = ?;", array($folder, $name));
    if(!empty($check)) {
        $objResponse->addScript("ShowBox('МОД не добавлен', 'МОД использующий такие папку или имя уже существует.', 'red');");
        return $objResponse;
    }

    $pre = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_mods(name,icon,modfolder,steam_universe,enabled) VALUES (?,?,?,?,?)");
    $GLOBALS['db']->Execute($pre,array($name, $icon, $folder, $steam_universe, $enabled));

    $objResponse->addScript("ShowBox('Мод добавлен', 'Игровой МОД успешно добавлен', 'green', 'index.php?p=admin&c=mods');");
    $objResponse->addScript("TabToReload();");
    $log = new CSystemLog("m", "МОД добавлен", "МОД ($name) был добавлен");

    return $objResponse;
}

function RemoveMod($mid) {
    global $userbank, $username;
    $objResponse = new xajaxResponse();

    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_MODS)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался удалить мод, не имея на это прав.");
        return $objResponse;
    }
    
    $mid = (int)$mid;
    $objResponse->addScript("SlideUp('mid_$mid');");

    $modicon = $GLOBALS['db']->GetRow("SELECT icon, name FROM `" . DB_PREFIX . "_mods` WHERE mid = '" . $mid . "';");
    @unlink(SB_ICONS."/".$modicon['icon']);

    $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_mods` WHERE mid = '" . $mid . "'");

    if($query1) {
        $objResponse->addScript("ShowBox('МОД удалён', 'Выбранный МОД был удалён из базы данных', 'green', 'index.php?p=admin&c=mods', true);");
        $log = new CSystemLog("m", "МОД удалён", "МОД (" . $modicon['name'] . ") был удалён");
    } else {
        $objResponse->addScript("ShowBox('Ошибка', 'Не получилось удалить МОД.', 'red', 'index.php?p=admin&c=mods', true);");
    }

    return $objResponse;
}
