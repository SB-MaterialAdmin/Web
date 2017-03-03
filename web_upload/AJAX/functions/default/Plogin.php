<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function Plogin($username, $password, $remember, $redirect, $nopass) {
    global $userbank;

    $objResponse = new xajaxResponse();
    if (empty($password)) {
        ShowBox_ajx("Информация", "Не введён пароль. Введите пароль, и повторите попытку ещё раз.", "blue", "", true, $objResponse);
        return $objResponse;
    }

    $qStatement = $GLOBALS['db']->prepare("SELECT `aid`, `password`, `expired` FROM `" . DB_PREFIX . "_admins` WHERE `user` = ?");
    $q = $qStatement->execute([$username]);
    if($q) {
        $q = $qStatement->fetch(PDO::FETCH_LAZY);
        $aid = $q[0];
    }

    if($q && strlen($q[1]) == 0 && count($q) != 0) {
        $objResponse->addScript('ShowBox("Информация", "Вы не можете залогиниться. Не установлен пароль.", "blue", "", true);');
        return $objResponse;
    } else if(!$q || !$userbank->CheckLogin($userbank->encrypt_password($password), $aid)) {
        if($nopass!=1)
            $objResponse->addScript('ShowBox("Вход не удался", "Неверно введены имя пользователя или пароль.<br \> Если Вы забыли свой пароль, Используйте ссылку <a href=\"index.php?p=lostpassword\" title=\"Забыл пароль\">Забыл пароль.</a>", "red", "", true);');
        new CSystemLog("w", "Неуспешная попытка авторизации", "Кто-то пытался авторизоваться под Аккаунтом " . $username . ", введя неправильный пароль");
        return $objResponse;
    } else if($q[2] > 0 && $q[2] < time()) {
        $objResponse->addScript('ShowBox("Просрочена", "У этого аккаунта закончился срок действия.", "red", "", true);');
        return $objResponse;
    } else {
        $objResponse->addScript("$('msg-red').setStyle('display', 'none');");
    }

    $userbank->login($aid, $password, $remember);

    if(strstr($redirect, "validation") || empty($redirect))
        $objResponse->addRedirect("?",  0);
    else
        $objResponse->addRedirect("?" . $redirect, 0);
    return $objResponse;
}
