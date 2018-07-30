<?php
/**
 * Скрипт "быстрой валидации" настроек
 * веб-сервера для установки SourceBans
 * Material Admin.
 */
Header("Content-Type: text/plain; charset=utf8");

$warnings   = 0;
$success    = 0;
$errors     = 0;
$howto      = [];

define('TEXT_DELIMITER', str_repeat('-', 100));

/**
 * Приветствие
 */
echo(TEXT_DELIMITER . "\n");
echo('Все пункты, помеченные плюсом, успешно проверены и соответствуют рекомендуемым требованиям.' . "\n");
echo('Все пункты, помеченные кружочком, удотворяют минимальным системным требованиям.' . "\n");
echo('Все пункты, помеченные тире, не прошли проверку минимальных системных требований.' . "\n");
echo(TEXT_DELIMITER);
echo("\n\n");

/**
 * PHP-версия
 */
if (version_compare(PHP_VERSION, '7.0') != -1) {
  echo('[+] Установлена рекомендуемая версия PHP или выше');
  $success++;
} else if (version_compare(PHP_VERSION, '5.4') != -1) {
  echo('[+] Установлена минимально требуемая версия PHP');
  $success++;
} else {
  echo('[-] Версия PHP не поддерживается');
  $howto[] = 'Обновите версию PHP. Если Вы используете shared-хостинг (чисто под сайт) - напишите в ТП, либо смените его.';
  $errors++;
}
echo(' (' . PHP_VERSION . ")\n");

/**
 * Поддержка BCMath
 */
if (function_exists('bcadd')) {
  echo('[+] BCMath установлен и работает.');
  $success++;
} else {
  echo('[-] BCMath не установлен.');
  $howto[] = 'Установите расширение BCMath (команду для VPS/VDS/DS можно найти в поисковой системе Google). Если у Вас shared-хостинг (чисто под сайт) - поищите в панели пункт для включения напишите в ТП, либо смените его.';
  $errors++;
}
echo("\n");

/**
 * Поддержка GMP / 64-битный PHP
 */
if (extension_loaded('gmp')) {
  echo('[+] Найдено расширение GMP для работы с 64-битными числами.');
  $success++;
} else if (2147483647 != PHP_INT_MAX) {
  echo('[+] Установлена 64-битная версия PHP.');
  $success++;
} else {
  echo('[-] Отсутствует поддержка 64-битных чисел.');
  $howto[] = 'Обновите PHP-интерпретатор или установите расширение GMP.';
  $errors++;
}
echo("\n");

/**
 * Загрузка файлов
 */
if (ini_get('file_uploads')) {
  echo('[+] Загрузка файлов разрешена.');
  $success++;
} else {
  echo('[-] Загрузка файлов запрещена.');
  $howto[] = 'В конфигурационном файле PHP установите значение переменной file_uploads значение 1.';
  $errors++;
}
echo("\n");

/**
 * Поддержка XML
 */
if (extension_loaded('xml')) {
  echo('[+] XML-расширение доступно.');
  $success++;
} else {
  echo('[-] XML-расширение недоступно.');
  $howto[] = 'Установите XML-расширение, если оно не установлено.';
  $errors++;
}
echo("\n");

/**
 * Глобальные переменные
 */
if (!ini_get('register_globals')) {
  echo('[+] Глобальные переменные выключены.');
  $success++;
} else {
  echo('[o] Глобальные переменные включены.');
  $howto[] = 'В конфигурационном файле PHP установите значение переменной register_globals значение 0.';
  $warnings++;
}
echo("\n");

/**
 * Safe Mode
 */
if (ini_get('safe_mode') == 0) {
  echo('[+] Безопасный режим выключен.');
  $success++;
} else {
  echo('[o] Безопасный режим включен.');
  $howto[] = 'В конфигурационном файле PHP установите значение переменной safe_mode значение 0.';
  $warnings++;
}
echo("\n");

/**
 * MySQLi
 */
if (extension_loaded('mysqli')) {
  echo('[+] MySQLi-расширение доступно.');
  $success++;
} else {
  echo('[-] Работа с БД невозможна.');
  $howto[] = 'Подключите расширение MySQLi';
  $errors++;
}
echo("\n");

/**
 * Итог.
 */
echo("\n");
if ($errors > 0) {
  echo('SourceBans работать не будет: есть пункты, мешающие корректной работе. Исправьте их, и вернитесь к этому скрипту.' . "\n\n");
  echo('Варианты исправления проблем:' . "\n");
  foreach ($howto as $item)
    echo($item . "\n");

  exit();
}

if ($warnings > 0) {
  echo('SourceBans работать будет, но часть функционала может либо не работать, либо работать крайне криво.' . "\n\n");
  echo('Варианты исправления проблем:' . "\n");
  foreach ($howto as $item)
    echo($item . "\n");

  exit();
}

echo('Всё хорошо, можете загружать SourceBans Material Admin на свой веб-сервер.');