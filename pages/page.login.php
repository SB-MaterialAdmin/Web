<?php 
// *************************************************************************
//  This file is part of SourceBans++.
//
//  Copyright (C) 2014-2016 Sarabveer Singh <me@sarabveer.me>
//
//  SourceBans++ is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, per version 3 of the License.
//
//  SourceBans++ is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with SourceBans++. If not, see <http://www.gnu.org/licenses/>.
//
//  This file is based off work covered by the following copyright(s):  
//
//   SourceBans 1.4.11
//   Copyright (C) 2007-2015 SourceBans Team - Part of GameConnect
//   Licensed under GNU GPL version 3, or later.
//   Page: <http://www.sourcebans.net/> - <https://github.com/GameConnect/sourcebansv1>
//
// *************************************************************************

if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
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
