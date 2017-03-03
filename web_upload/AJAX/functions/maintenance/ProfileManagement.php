<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function ChangeEmail($aid, $email, $password) {
    global $userbank, $username;
    $objResponse = new xajaxResponse();
    $aid = (int)$aid;
    
    if(!$userbank->is_logged_in() || $aid != $userbank->GetAid()) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался сменить e-mail ".$userbank->GetProperty('user', $aid).", не имея на это прав.");
        return $objResponse;
    }

    if($userbank->encrypt_password($password) != $userbank->getProperty('password')) {
        $objResponse->addScript("$('emailpw.msg').setStyle('display', 'block');");
        $objResponse->addScript("$('emailpw.msg').setHTML('Введённый пароль неверен.');");
        $objResponse->addScript("set_error(1);");
        return $objResponse;
    } else {
        $objResponse->addScript("$('emailpw.msg').setStyle('display', 'none');");
        $objResponse->addScript("set_error(0);");
    }
    
    if(!check_email($email)) {
        $objResponse->addScript("$('email1.msg').setStyle('display', 'block');");
        $objResponse->addScript("$('email1.msg').setHTML('Введите действительный адрес электронной почты.');");
        $objResponse->addScript("set_error(1);");
        return $objResponse;
    } else {
        $objResponse->addScript("$('email1.msg').setStyle('display', 'none');");
        $objResponse->addScript("set_error(0);");
    }

    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `email` = ? WHERE `aid` = ?", array($email, $aid));
    $objResponse->addScript("ShowBox('E-mail изменён', 'Ваш e-mail адрес успешно изменён.', 'green', 'index.php?p=account', true);");
    $log = new CSystemLog("m", "E-mail изменён", "E-mail изменил админ (".$aid.")");

    return $objResponse;
}

function CheckPassword($aid, $pass) {
    global $userbank;
    $objResponse = new xajaxResponse();
    $aid = (int)$aid;
    if(!$userbank->CheckLogin($userbank->encrypt_password($pass), $aid)) {
        $objResponse->addScript("$('current.msg').setStyle('display', 'block');");
        $objResponse->addScript("$('current.msg').setHTML('<div class=\"c-red\">Данные не совпадают</div>');");
        $objResponse->addScript("set_error(1);");
    } else {
        $objResponse->addScript("$('current.msg').setStyle('display', 'none');");
        $objResponse->addScript("set_error(0);");
    }

    return $objResponse;
}

function ChangePassword($aid, $pass) {
    global $userbank;
    $objResponse = new xajaxResponse();
    $aid = (int)$aid;

    if($aid != $userbank->aid && !$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ADMINS)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $_SERVER["REMOTE_ADDR"] . " пытался сменить пароль, не имея на это прав.");
        return $objResponse;
    }

    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `password` = '" . $userbank->encrypt_password($pass) . "' WHERE `aid` = $aid");
    $admname = $GLOBALS['db']->GetRow("SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = ?", array((int)$aid));
    $objResponse->addScript('ShowBox("Успех!", "Пароль успешно изменён!", "green", "index.php?p=login", true, 5000);');
    $log = new CSystemLog("m", "Пароль изменен", "Пароль сменен админом (".$admname['user'].")");
    return $objResponse;
}

function CheckSrvPassword($aid, $srv_pass) {
    global $userbank, $username;
    $objResponse = new xajaxResponse();
    $aid = (int)$aid;

    if(!$userbank->is_logged_in() || $aid != $userbank->GetAid()) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытается проверить пароль сервера ".$userbank->GetProperty('user', $aid).", не имея на это прав.");
        return $objResponse;
    }
    $res = $GLOBALS['db']->Execute("SELECT `srv_password` FROM `".DB_PREFIX."_admins` WHERE `aid` = '".$aid."'");
    if($res->fields['srv_password'] != NULL && $res->fields['srv_password'] != $srv_pass) {
        $objResponse->addScript("$('scurrent.msg').setStyle('display', 'block');");
        $objResponse->addScript("$('scurrent.msg').setHTML('Неверный пароль.');");
        $objResponse->addScript("set_error(1);");
    } else {
        $objResponse->addScript("$('scurrent.msg').setStyle('display', 'none');");
        $objResponse->addScript("set_error(0);");
    }

    return $objResponse;
}

function ChangeSrvPassword($aid, $srv_pass) {
    global $userbank, $username;
    $objResponse = new xajaxResponse();
    $aid = (int)$aid;

    if(!$userbank->is_logged_in() || $aid != $userbank->GetAid()) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытается изменить пароль сервера ".$userbank->GetProperty('user', $aid).", не имея на это прав.");
        return $objResponse;
    }

    if($srv_pass == "NULL")
        $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `srv_password` = NULL WHERE `aid` = '".$aid."'");
    else
        $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `srv_password` = ? WHERE `aid` = ?", array($srv_pass, $aid));

    $objResponse->addScript("ShowBox('Пароль сервера изменён', 'Пароль сервера был успешно изменён.', 'green', 'index.php?p=account', true);");
    $log = new CSystemLog("m", "Изменён пароль сервера", "Пароль сменил администратор (".$aid.")");

    return $objResponse;
}

function ChangeAdminsInfos($aid, $vk, $skype) {
    global $userbank;
    $objResponse = new xajaxResponse();
    $aid = (int)$aid;

    if($aid != $userbank->aid && !$userbank->is_logged_in()) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $_SERVER["REMOTE_ADDR"] . " пытался сменить vk или skype, не имея на это прав.");
        return $objResponse;
    }

    $vk = RemoveCode($vk);
    $vk = str_replace(array("http://","https://","/","vk.com"), "", $vk);
    $skype = RemoveCode($skype);

    $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `vk` = '".$vk."', `skype` = '".$skype."' WHERE `aid` = ?", array((int)$aid));
    $admname = $GLOBALS['db']->GetRow("SELECT user FROM `".DB_PREFIX."_admins` WHERE aid = ?", array((int)$aid));
    $objResponse->addScript("ShowBox('Информация', 'Ваши данные были успешно обновлены!', 'green', 'index.php?p=account');");
    $log = new CSystemLog("m", "Данные связи изменены", "У адмнистратора ".$admname['user']." успешно были изменены данные на (vk: ".$vk.", skype: ".$skype.")");
    return $objResponse;
}
