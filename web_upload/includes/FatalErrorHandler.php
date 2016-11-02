<?php
if (!defined('IN_SB')) {echo 'You should not be here. Only follow links!';die();}

global $theme;
if (headers_sent()) // Если SB уже отправил некоторые части шаблона, их надо очистить
    echo('<script>document.getElementsByTagName("html")[0].innerHTML = "";</script>');

$theme->assign('title', "Ошибка системы");
$theme->assign('message', $msg);
$theme->assign('pfunction', str_replace("<br />\n", "\n", $log->parent_function));
$theme->display('page_error.tpl');
