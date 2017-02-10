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

if (!defined('IN_SB')) {echo("Вы не должны быть здесь. Используйте только ссылки внутри системы!");die();}
RewritePageTitle("Вход администратора");

global $userbank, $theme;
//$submenu = array( array( "title" => 'Забыл пароль?', "url" => 'index.php?p=lostpassword' ) );
//SubMenu( $submenu );
if(isset($_GET['m']) && $_GET['m'] == "no_access")
	echo "<script>setTimeout(\"ShowBox('Ошибка - Нет доступа', 'У вас нет доступа к этой странице.<br />Войдите в аккаунт.', 'red', '', false);\", 1200);</script>";

	
//$theme->assign('redir', "DoLogin('".(isset($_SESSION['q'])?$_SESSION['q']:'')."');");
$theme->assign('redir', "DoLogin('p=account'); '".(isset($_SESSION['q'])?$_SESSION['q']:'')."';");

// === Authorization by type - START ===
/**
 * Available AuthType
 *
 * 0 - Default (login-password and Steam)
 * 1 - Only login-password
 * 2 - Only Steam
 **/
$at = isset($GLOBALS['config']['auth.type'])?$GLOBALS['config']['auth.type']:0;
$theme->assign('steam_allowed', ($at != 1));
$theme->assign('login_allowed', ($at != 2));
// === Authorization by type -  END  ===

$theme->left_delimiter = "-{";
$theme->right_delimiter = "}-";
$theme->display('page_login.tpl');
$theme->left_delimiter = "{";
$theme->right_delimiter = "}";
?>
</div>
</div>
</div>
