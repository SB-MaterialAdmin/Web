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

require_once("init.php");

if(!isset($_GET['id']) || !isset($_GET['type']))
  die('No id or type parameter.');

if(strcasecmp($_GET['type'], "U") != 0 && strcasecmp($_GET['type'], "B") != 0 && strcasecmp($_GET['type'], "S") != 0)
  die('Bad type');

$id = (int)$_GET['id'];

$demo = $GLOBALS['db']->GetRow("SELECT filename, origname FROM `".DB_PREFIX."_demos` WHERE demtype=? AND demid=?;", array($_GET['type'], $id));
//Official Fix: https://code.google.com/p/sourcebans/source/detail?r=165
if(!$demo)
{
  die('Demo not found.');
}

if((!in_array($demo['filename'], scandir(SB_DEMOS)) || !file_exists(SB_DEMOS . "/" . $demo['filename'])) && $_GET['type'] != "U")
{
  die('File not found.');
}

if($_GET['type'] != "U"){
$demo['filename'] = basename($demo['filename']);
header('Content-type: application/force-download');
header('Content-Transfer-Encoding: Binary');
header('Content-disposition: attachment; filename="' . $demo['origname'] . '"');
header("Content-Length: " . filesize(SB_DEMOS . "/" . $demo['filename']));
readfile(SB_DEMOS . "/" . $demo['filename']);
}else{
header( 'Location: '.$demo['origname'].'', true, 301 );
}
?>
