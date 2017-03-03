<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function LostPassword($email) {
    $objResponse = new xajaxResponse();
    $q = $GLOBALS['db']->GetRow("SELECT * FROM `" . DB_PREFIX . "_admins` WHERE `email` = ?", array($email));

    if(!$q[0]) {
        $objResponse->addScript("ShowBox('Ошибка', 'Введенный Вами адрес e-mail не найден в базе', 'red', '', true);");
            return $objResponse;
    } else {
        $objResponse->addScript("$('msg-red').setStyle('display', 'none');");
    }

    $validation = md5(generate_salt(20).generate_salt(20)).md5(generate_salt(20).generate_salt(20));
    $query = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET `validate` = ? WHERE `email` = ?", array($validation, $email));
    $message = "";
    $message .= "Привет " . $q['user'] . "\n";
    $message .= "Вы запросили смену пароля в системе SourceBans.\n";
    $message .= "Для завершения процедуры смены пароля перейдите по ссылке ниже.\n";
    $message .= "ПРИМЕЧАНИЕ: если Вы не запрашивали смену пароля, просто проигнорируйте это сообщение.\n\n";

    $message .= "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "?p=lostpassword&email=". RemoveCode($email) . "&validation=" . $validation;

    $headers = 'From: SourceBans@' . $_SERVER['HTTP_HOST'] . "\n" .
    'X-Mailer: PHP/' . phpversion();
    $m = @EMail($email, "Сброс пароля SourceBans", $message, $headers);

    if ($m) {
        $objResponse->addScript("ShowBox('Проверьте почту', 'На Ваш электронный ящик было отправлено письмо с ссылкой для сброса пароля.', 'blue', '', true);");
    } else {
        $objResponse->addScript("ShowBox('Ошибка', 'Не удалось отправить письмо на Ваш электронный ящик. Напишите главному администратору.', 'red', '', true);");
    }

    return $objResponse;
}
