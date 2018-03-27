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
if (version_compare(PHP_VERSION, '5.5') != -1) {
  echo('[+] Установлена рекомендуемая версия PHP или выше');
  $success++;
} else if (version_compare(PHP_VERSION, '5.4') != -1) {
  echo('[o] Установлена минимально требуемая версия PHP');
  $warnings++;
} else {
  echo('[-] Версия PHP не поддерживается');
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
  $errors++;
}
echo("\n");

/**
 * Итог.
 */
echo("\n");
if ($errors > 0) {
  echo('SourceBans работать не будет: есть пункты, мешающие корректной работе. Исправьте их, и вернитесь к этому скрипту.');
  exit();
}

if ($warnings > 0) {
  echo('SourceBans работать будет, но часть функционала может либо не работать, либо работать крайне криво.');
  exit();
}

echo('Всё хорошо, можете загружать SourceBans Material Admin на свой веб-сервер.');