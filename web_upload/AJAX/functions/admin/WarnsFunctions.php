<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function AddWarning($id, $days, $reason) {
    global $userbank;

    $objResponse = new xajaxResponse();
    if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_ADMINS) || $userbank->GetProperty("srv_immunity", $admin['id']) > $userbank->GetProperty("srv_immunity")) {
        ShowBox_ajx("Ошибка", "Отказано в доступе.", "red", "", true, $objResponse);
        new CSystemLog("w", "Попытка несанцкионированного доступа", "Администратор пытался выдать предупреждение, не имея на это прав.");
        return $objResponse;
    }

    if ((int) $days <= 0) {
        ShowBox_ajx("Ошибка", "Пожалуйста, введите число дней более нуля.", "red", "", true, $objResponse);
        return $objResponse;
    }

    $removedAccess = false;

    $GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_warns` (`arecipient`, `afrom`, `expires`, `reason`) VALUES(" . (int) $id . ", " . (int) $userbank->GetAid() . ", " . (time() + (86400 * (int) $days)) . ", " . $GLOBALS['db']->qstr($reason) . ");");
    new CSystemLog("m", "Предупреждение выдано", "Администратор выдал предупреждение Администратору " . $userbank->getProperty('user', $id));

    if ($GLOBALS['db']->GetOne("SELECT COUNT(*) FROM `" . DB_PREFIX . "_warns` WHERE `arecipient` = " . (int) $id) >= (int) $GLOBALS['config']['admin.warns.max']) {
        $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET `expired` = 1 WHERE `aid` = " . (int) $id . ";");
        new CSystemLog("m", "Аккаунт администратора деактивирован", "По причине превышения лимита максимально активных предупреждений, Администратор " . $userbank->getProperty('user', $id) . " отстраняется от Должности.");
        $removedAccess = true;
    }
    $msg = "Предупреждение с причиной \"<em>".$reason."</em>\" выдано сроком на ".$days." дней.";
    if ($removedAccess)
        $msg .= "<br /><br />Поскольку Администратор превысил лимит максимально активных предупреждений, он <span style=\"color: #f00;\">отстранён от должности</span>.";

    ShowBox_ajx("Успех", $msg, "green", "", true, $objResponse);
    return $objResponse;
}

function RemoveWarning($warningId) {
    global $userbank;

    $objResponse = new xajaxResponse();
    if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_ADMINS)) {
        ShowBox_ajx("Ошибка", "Отказано в доступе.", "red", "", true, $objResponse);
        new CSystemLog("w", "Попытка несанцкионированного доступа", "Администратор пытался снять предупреждение, не имея на это прав.");
        return $objResponse;
    }

    if ((int) $GLOBALS['db']->GetOne("SELECT COUNT(*) FROM `" . DB_PREFIX . "_warns` WHERE `expires` > " . time() . " AND `id` = ". (int) $warningId) == 1) {
        ShowBox_ajx("Успех", "Предупреждение снято", "green", "", true, $objResponse);
        new CSystemLog("m", "Предупреждение снято", "Администратор снял предупреждение Администратору " . $userbank->getProperty('user', $GLOBALS['db']->GetOne("SELECT `arecipient` FROM `" . DB_PREFIX . "_warns` WHERE `id` = " . (int) $warningId)) . " с идентификатором " . $warningId);
        $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_warns` SET `expires` = -1 WHERE `id` = " . (int) $warningId);
    } else
        ShowBox_ajx("Ошибка", "Действущее предупреждение с идентификатором " . $warningId . " не найдено. Может быть, оно уже истекло?", "red", "", true, $objResponse);
    
    return $objResponse;
}
