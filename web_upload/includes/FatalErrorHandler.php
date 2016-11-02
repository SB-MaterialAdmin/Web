<?php
if (!defined('IN_SB')) {echo 'You should not be here. Only follow links!';die();}

global $theme;
// SB ничего не мог отправить, так как весь вывод пихался в буфер. Выключим его, очистив всё его содержимое, и начнём выводить свой шаблон ошибки.
ob_end_clean();

$theme->assign('title', "Ошибка системы");
$theme->assign('message', $msg);
$theme->assign('pfunction', str_replace("<br />\n", "\n", $log->parent_function));
$theme->display('page_error.tpl');
