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
/**
 * SourceBans "Error Connecting()" Debug
 * Checks for the ports being forwarded correctly
 */

/** 
 * Конфиг
 * Смените IP и порт, если хотите протестировать соединение.
 */
$serverip = "";
$serverport = 27015;
$serverrcon = ""; // Указывайте RCON-пароль, если хотите проверить так же возможность управления сервером из веб-панели SourceBans


/******* Ничего не изменяйте после этой линии *******/
header("Content-Type: text/plain");

if(empty($serverip) || empty($serverport))
	die('[-] Не указана информация о сервере. Откройте текстовым редактором этот файл, пропишите в нём IP и порт на строках 40 и 41, сохраните и загрузите обратно на сервер. Если требуется проверить так же наличие доступа к RCON-протоколу, пропишите RCON-пароль на строке 42.');

echo '[+] SourceBans "DebugConnection()" запущен для сервера ' . $serverip . ':' . $serverport . "\n\n";

// Попытаемся установить соединение
echo '[+] Открываю UDP-сокет...'.PHP_EOL;
$sock = @fsockopen("udp://" . $serverip, $serverport, $errno, $errstr, 2);

$isBanned = false;

if(!$sock)
    echo '[-] Ошибка соединения. #' . $errno . ': ' . $errstr . PHP_EOL;
else {
    echo '[+] UDP-соединение успешно установлено!'.PHP_EOL;

    stream_set_timeout($sock, 1);

    // Попытаемся получить информацию у сервера
    echo '[+] Записываю запрос в сокет..'.PHP_EOL;
    if(fwrite($sock, "\xFF\xFF\xFF\xFF\x54Source Engine Query\0") === false)
        echo '[-] Ошибка записи.'.PHP_EOL;
    else {
        echo '[+] Запрос успешно записан в сокет. (Это не означает, что с соединением всё в порядке.) Читаю ответ...'.PHP_EOL;
        $packet = fread($sock, 1480);

        if(empty($packet))
            echo '[-] Ошибка при получении информации о сервере. Не удаётся прочитать UDP-соединение. Порт заблокирован.'.PHP_EOL;
        else {
            if(substr($packet, 5, (strpos(substr($packet, 5), "\0")-1)) == "Banned by server") {
                printf('[-] Ответ получен, но веб-сервер заблокирован. Удалите блокировку с сервера (removeip %s), и повторите попытку.%s', $_SERVER['SERVER_ADDR'], PHP_EOL);
                $isBanned = true;
            } else {
                $packet = substr($packet, 6);
                $hostname = substr($packet, 0, strpos($packet, "\0"));
                echo '[+] Ответ получен! Сервер: ' . $hostname . PHP_EOL;
            }
        }
    }
    fclose($sock);
}

echo PHP_EOL;

// Проверим на доступность и записываемость TCP-соединения
echo '[+] Попытка установить TCP-соединение...'.PHP_EOL;
$sock = @fsockopen($serverip, $serverport, $errno, $errstr, 2);
if(!$sock)
    echo '[-] Ошибка соединения. #' . $errno . ': ' . $errstr . PHP_EOL;
else
{
    echo '[+] TCP-соединение успешно установлено!'.PHP_EOL;
    if(empty($serverrcon))
        echo '[o] Прерываю работу. RCON-пароль не установлен.';
    else if($isBanned)
        echo '[o] Прерываю работу. Сервер находится в блокировке.';
    else {
        stream_set_timeout($sock, 2);
        $data = pack("VV", 0, 03) . $serverrcon . chr(0) . '' . chr(0);
        $data = pack("V", strlen($data)) . $data;

        echo '[+] Пытаюсь записать в TCP-сокет и произвести авторизацию...'.PHP_EOL;

        if(fwrite($sock, $data, strlen($data)) === false)
            echo '[-] Ошибка записи.'.PHP_EOL;
        else {
            echo '[+] Запрос авторизации успешно записан. Читаю ответ...'.PHP_EOL;
            $size = fread($sock, 4);
            if(!$size)
                echo '[-] Ошибка чтения.'.PHP_EOL;
            else {
                echo '[+] Ответ получен!'.PHP_EOL;
                $size = unpack('V1Size', $size);
                $packet = fread($sock, $size["Size"]);
                $size = fread($sock, 4);
                $size = unpack('V1Size', $size);
                $packet = fread($sock, $size["Size"]);
                $ret = unpack("V1ID/V1Reponse/a*S1/a*S2", $packet);
                if(empty($ret) || (isset($ret['ID']) && $ret['ID'] == -1))
                    echo '[-] RCON-пароль задан неверный ;) Не пытайтесь и дальше производить попытки, иначе ваш веб-сервер "улетит" в бан.';
                else
                    echo '[+] Пароль задан правильно!';
            }
        }
    }
    fclose($sock);
}
?>
