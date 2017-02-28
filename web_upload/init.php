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

// ---------------------------------------------------
//  Directories
// ---------------------------------------------------
define('ROOT',              dirname(__FILE__) . "/");
define('SCRIPT_PATH',       ROOT . 'scripts');
define('TEMPLATES_PATH',    ROOT . 'pages');
define('INCLUDES_PATH',     ROOT . 'includes');
define('DATA_PATH',         ROOT . 'data/');

define('SB_DEMO_LOCATION',  'demo');
define('SB_ICONS_LOCATION',  'games');
define('SB_MAP_LOCATION',   DATA_PATH . 'maps');
define('SB_DEMOS',          DATA_PATH . SB_DEMO_LOCATION);
define('SB_ICONS',          DATA_PATH . SB_ICONS_LOCATION);

define('SB_THEME',          ROOT . 'theme/');
define('SB_THEME_COMPILE',  DATA_PATH . 'theme/');

define('IN_SB',             true);
define('SB_AID',            isset($_COOKIE['aid'])?$_COOKIE['aid']:null);
define('XAJAX_REQUEST_URI', './index.php');

include_once(INCLUDES_PATH . "/CSystemLog.php");
include_once(INCLUDES_PATH . "/CUserManager.php");
include_once(INCLUDES_PATH . "/CUI.php");
include_once(SB_THEME . "theme.conf.php");
// ---------------------------------------------------
//  Fix some $_SERVER vars
// ---------------------------------------------------
// Fix for IIS, which doesn't set REQUEST_URI
if(!isset($_SERVER['REQUEST_URI']) || trim($_SERVER['REQUEST_URI']) == '') 
{ $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
    if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) 
    { $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; } 
} 
// Fix for Dreamhost and other PHP as CGI hosts
if(strstr($_SERVER['SCRIPT_NAME'], 'php.cgi')) unset($_SERVER['PATH_INFO']);
if(trim($_SERVER['PHP_SELF']) == '') $_SERVER['PHP_SELF'] = preg_replace("/(\?.*)?$/",'', $_SERVER["REQUEST_URI"]);

// ---------------------------------------------------
//  Are we installed?
// ---------------------------------------------------
if(!file_exists(DATA_PATH.'/config.php') || !@include_once(DATA_PATH . '/config.php')) {
	// No were not
	if($_SERVER['HTTP_HOST'] != "localhost") {
		echo "SourceBans не установлен.";
		die();
	}
}

if(!defined("DEVELOPER_MODE") && !defined("IS_UPDATE") && file_exists(ROOT."/install")) {
	if($_SERVER['HTTP_HOST'] != "localhost" || !file_exists(DATA_PATH . "installer_ban.php")) {
		echo "Для обеспечения безопасности работы SourceBans, удалите или переименуйте папку /install";
		die();
	}
}

if(!defined("DEVELOPER_MODE") && !defined("IS_UPDATE") && file_exists(ROOT."/updater")) {
	if($_SERVER['HTTP_HOST'] != "localhost") {
		echo "Выполняется перенаправление на страницу обновления SourceBans...";
		echo "<script>setTimeout(function() { window.location.replace('./updater'); }, 2000);</script>";
		die();
	}
}

// ---------------------------------------------------
//  Initial setup
// ---------------------------------------------------

if(!defined('SB_VERSION')){
	define('SB_VERSION', '1.5.4.7');
	define('MA_BRANCH', 'dev');
}
define('LOGIN_COOKIE_LIFETIME', (60*60*24*7)*2);
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIE_SECURE', false);
define('SB_SALT', 'SourceBans');

// ---------------------------------------------------
//  Setup PHP
// ---------------------------------------------------
ini_set('date.timezone', 'GMT');

if(defined("SB_MEM"))
	ini_set('memory_limit', SB_MEM);

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);


// ---------------------------------------------------
//  Setup DB connection with PDO.
// ---------------------------------------------------
try {
    $pdo_options =  [
        PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC
    ];

    require_once(INCLUDES_PATH . "/CDB.php");
    $GLOBALS['db'] = new Database(sprintf("mysql:host=%s;dbname=%s;charset=utf8;port=%d", DB_HOST, DB_NAME, intval(DB_PORT)), DB_USER, DB_PASS, $pdo_options);
} catch (PDOException $e) {
    echo("Не удалось подключиться к Базе Данных.<br /><br />");
    echo($e->getMessage());
    exit(1);
}
$GLOBALS['log'] = new CSystemLog();
$GLOBALS['db_version'] = $GLOBALS['db']->getAttribute(PDO::ATTR_SERVER_VERSION);

// ---------------------------------------------------
//  Read settings and push to global array
// ---------------------------------------------------
$GLOBALS['config'] = array();
$res = $GLOBALS['db']->query('SELECT * FROM `' . DB_PREFIX . '_settings`');
while ($row = $res->fetch(PDO::FETCH_LAZY)) {
    $GLOBALS['config'][$row->setting] = $row->value;
}

if ($GLOBALS['config']['config.debug'] == "1") {
    define("DEVELOPER_MODE", true);
}

define('SB_BANS_PER_PAGE', $GLOBALS['config']['banlist.bansperpage']);
define('MIN_PASS_LENGTH', $GLOBALS['config']['config.password.minlength']);
$dateformat = !empty($GLOBALS['config']['config.dateformat'])?$GLOBALS['config']['config.dateformat']:"m-d-y H:i";

$offset = (empty($GLOBALS['config']['config.timezone'])?0:$GLOBALS['config']['config.timezone'])*3600;
date_default_timezone_set("GMT");
$abbrarray = timezone_abbreviations_list();
foreach ($abbrarray as $abbr) {
    foreach ($abbr as $city) {
        if ($city['offset'] == $offset && $city['dst'] == $GLOBALS['config']['config.summertime']) {
            date_default_timezone_set($city['timezone_id']);
            break 2;
        }
    }
}

// ---------------------------------------------------
//  Setup our custom error handler
// ---------------------------------------------------
require_once(INCLUDES_PATH . '/CErrorHandler.php');
$GLOBALS['error_manager'] = new CErrorHandler();

// ---------------------------------------------------
//  Some defs
// ---------------------------------------------------
define('EMAIL_FORMAT', "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/");
define('URL_FORMAT', "/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}((:[0-9]{1,5})?\/.*)?$/i");
define('STEAM_FORMAT', "/^STEAM_[0-9]:[0-9]:[0-9]+$/");
define('STATUS_PARSE', '/# +([0-9 ]+) +"(.+)" +(STEAM_[0-9]:[0-9]:[0-9]+|\[U:[0-9]:[0-9]+\]) +([0-9:]+) +([0-9]+) +([0-9]+) +([a-zA-Z]+) +([0-9.:]+)/');
define('IP_FORMAT', '/\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/');
define('SERVER_QUERY', 'http://www.sourcebans.net/public/query/');

// Web admin-flags
define('ADMIN_LIST_ADMINS', 	(1<<0));
define('ADMIN_ADD_ADMINS', 		(1<<1));
define('ADMIN_EDIT_ADMINS', 	(1<<2));
define('ADMIN_DELETE_ADMINS', 	(1<<3));

define('ADMIN_LIST_SERVERS', 	(1<<4));
define('ADMIN_ADD_SERVER', 		(1<<5));
define('ADMIN_EDIT_SERVERS', 	(1<<6));
define('ADMIN_DELETE_SERVERS', 	(1<<7));

define('ADMIN_ADD_BAN', 		(1<<8));
define('ADMIN_EDIT_OWN_BANS', 	(1<<10));
define('ADMIN_EDIT_GROUP_BANS', (1<<11));
define('ADMIN_EDIT_ALL_BANS', 	(1<<12));
define('ADMIN_BAN_PROTESTS', 	(1<<13));
define('ADMIN_BAN_SUBMISSIONS', (1<<14));
define('ADMIN_DELETE_BAN',		(1<<25));
define('ADMIN_UNBAN', 			(1<<26));
define('ADMIN_BAN_IMPORT',		(1<<27));
define('ADMIN_UNBAN_OWN_BANS',	(1<<30));
define('ADMIN_UNBAN_GROUP_BANS',(1<<31));

define('ADMIN_LIST_GROUPS', 	(1<<15));
define('ADMIN_ADD_GROUP', 		(1<<16));
define('ADMIN_EDIT_GROUPS', 	(1<<17));
define('ADMIN_DELETE_GROUPS', 	(1<<18));

define('ADMIN_WEB_SETTINGS', 	(1<<19));

define('ADMIN_LIST_MODS', 		(1<<20));
define('ADMIN_ADD_MODS', 		(1<<21));
define('ADMIN_EDIT_MODS', 		(1<<22));
define('ADMIN_DELETE_MODS', 	(1<<23));

define('ADMIN_NOTIFY_SUB',	(1<<28));
define('ADMIN_NOTIFY_PROTEST',	(1<<29));

define('ADMIN_OWNER', 			(1<<24));

// Server admin-flags
define('SM_RESERVED_SLOT', 		"a");
define('SM_GENERIC', 			"b");
define('SM_KICK', 				"c");
define('SM_BAN', 				"d");
define('SM_UNBAN', 				"e");
define('SM_SLAY', 				"f");
define('SM_MAP', 				"g");
define('SM_CVAR', 				"h");
define('SM_CONFIG', 			"i");
define('SM_CHAT', 				"j");
define('SM_VOTE',				"k");
define('SM_PASSWORD', 			"l");
define('SM_RCON', 				"m");
define('SM_CHEATS', 			"n");
define('SM_ROOT', 				"z");

define('SM_CUSTOM1', 			"o");
define('SM_CUSTOM2', 			"p");
define('SM_CUSTOM3', 			"q");
define('SM_CUSTOM4', 			"r");
define('SM_CUSTOM5', 			"s");
define('SM_CUSTOM6', 			"t");


define('ALL_WEB', ADMIN_LIST_ADMINS|ADMIN_ADD_ADMINS|ADMIN_EDIT_ADMINS|ADMIN_DELETE_ADMINS|ADMIN_LIST_SERVERS|ADMIN_ADD_SERVER|
				  ADMIN_EDIT_SERVERS|ADMIN_DELETE_SERVERS|ADMIN_ADD_BAN|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_GROUP_BANS|
				  ADMIN_EDIT_ALL_BANS|ADMIN_BAN_PROTESTS|ADMIN_BAN_SUBMISSIONS|ADMIN_LIST_GROUPS|ADMIN_ADD_GROUP|ADMIN_EDIT_GROUPS|
				  ADMIN_DELETE_GROUPS|ADMIN_WEB_SETTINGS|ADMIN_LIST_MODS|ADMIN_ADD_MODS|ADMIN_EDIT_MODS|ADMIN_DELETE_MODS|ADMIN_OWNER|
				  ADMIN_DELETE_BAN|ADMIN_UNBAN|ADMIN_BAN_IMPORT|ADMIN_UNBAN_OWN_BANS|ADMIN_UNBAN_GROUP_BANS|ADMIN_NOTIFY_SUB|ADMIN_NOTIFY_PROTEST);

define('ALL_SERVER', SM_RESERVED_SLOT.SM_GENERIC.SM_KICK.SM_BAN.SM_UNBAN.SM_SLAY.SM_MAP.SM_CVAR.SM_CONFIG.SM_VOTE.SM_PASSWORD.SM_RCON.
					 SM_CHEATS.SM_CUSTOM1.SM_CUSTOM2.SM_CUSTOM3. SM_CUSTOM4.SM_CUSTOM5.SM_CUSTOM6.SM_ROOT);

// if(empty($GLOBALS['config']['config.timezone']))
// {
	// date_default_timezone_set("Europe/London");
// }else{
	// date_default_timezone_set($GLOBALS['config']['config.timezone']);
// }


// ---------------------------------------------------
// Setup our templater
// ---------------------------------------------------
require(INCLUDES_PATH . '/smarty/Smarty.class.php');

global $theme, $userbank;

if(!@file_exists(SB_THEME . "/theme.conf.php"))
	die("<b>Ошибка шаблона</b>: Шаблон повреждён. Отсутствует файл <b>theme.conf.php</b>.");

if(!@is_writable(SB_THEME))
	die("<b>Ошибка шаблона</b>: Папка <b>".SB_THEME_COMPILE."</b> не перезаписываемая! Установите права 777 на папку через FTP-клиент.");

$theme = new Smarty();
$theme->error_reporting     =   E_ALL ^ E_NOTICE;
$theme->use_sub_dirs        =   false;
$theme->compile_id          =   "MATERIAL_Admin";
$theme->caching             =   false;
$theme->template_dir        =   SB_THEME;
$theme->compile_dir         =   SB_THEME_COMPILE;

if ((isset($_GET['debug']) && $_GET['debug'] == 1) || defined("DEVELOPER_MODE"))
    $theme->force_compile = true;

// ---------------------------------------------------
// Setup our user manager
// ---------------------------------------------------
$l = '';
$p = '';
if (!defined('IS_UPDATE') && isset($_COOKIE['aid']))
    $l = $_COOKIE['aid'];
if (!defined('IS_UPDATE') && isset($_COOKIE['password']))
    $p = $_COOKIE['password'];

$userbank = new CUserManager($l, $p);

// ---------------------------------------------------
// Setup our avatar manager
// ---------------------------------------------------
if (!defined('IS_UPDATE')) {
    require_once(INCLUDES_PATH . '/CAvatarManager.php');
    // $GLOBALS['AvatarMgr'] = new CAvatarManager($GLOBALS['config']['avatarmgr.default']);
    $GLOBALS['AvatarMgr'] = new CAvatarManager("./theme/img/profile-pics/" . rand(1, 9) . ".jpg");
}
?>
