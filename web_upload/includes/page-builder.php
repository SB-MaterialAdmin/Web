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

$_GET['p'] = isset($_GET['p']) ? $_GET['p'] : 'default';
$_GET['p'] = trim($_GET['p']);
switch ($_GET['p'])
{
	case "login":
		$page = TEMPLATES_PATH . "/page.login.php";
		break;
	case "logout":
		logout();
		Header("Location: index.php");
		exit(0);
		break;
	case "admin":
		$page = INCLUDES_PATH . "/admin.php";
		break;
	case "submit":
		RewritePageTitle("Репорт");
		$page = TEMPLATES_PATH . "/page.submit.php";
		break;
	case "banlist":
		RewritePageTitle("Банлист");
		$page = TEMPLATES_PATH ."/page.banlist.php";
		break;
	case "commslist":
		RewritePageTitle("Блокировки чата");
		$page = TEMPLATES_PATH ."/page.commslist.php";
		break;
	case "servers":
		RewritePageTitle("Сервера");
		$page = TEMPLATES_PATH . "/page.servers.php";
		break;
	case "protest":
		RewritePageTitle("Протест бана");
		$page = TEMPLATES_PATH . "/page.protest.php";
		break;
	case "account":
		RewritePageTitle("Ваш аккаунт");
		$page = TEMPLATES_PATH . "/page.youraccount.php";
		break;
	case "lostpassword":
		RewritePageTitle("Забыли пароль");
		$page = TEMPLATES_PATH . "/page.lostpassword.php";
		break;
	case "home":
		RewritePageTitle("Главная");
		$page = TEMPLATES_PATH . "/page.home.php";
		break;
	case "search_bans":
		RewritePageTitle("Подробный поиск банов");
		$page = TEMPLATES_PATH . "/page.search_bans.php";
		break;
	case "search_comm":
		RewritePageTitle("Подробный поиск мутов");
		$page = TEMPLATES_PATH . "/page.search_comms.php";
		break;
	case "pay":
		RewritePageTitle("Активация");
		$page = TEMPLATES_PATH . "/page.vay4er.php";
		break;
	case "adminlist":
		RewritePageTitle("АдминЛист");
		$page = TEMPLATES_PATH . "/page.adminlist.php";
		break;
	default:
		require_once(INCLUDES_PATH . "/CStaticPages.php");
		if (CStaticPages::IsPageExists($_GET['p'])) {
			
		} else
			switch($GLOBALS['config']['config.defaultpage'])
			{
				case 1:
					RewritePageTitle("Банлист");
					$page = TEMPLATES_PATH . "/page.banlist.php";
					$_GET['p'] = "banlist";
					break;
				case 2:
					RewritePageTitle("Сервера");
					$page = TEMPLATES_PATH . "/page.servers.php";
					$_GET['p'] = "servers";
					break;
				case 3:
					RewritePageTitle("Репорт");
					$page = TEMPLATES_PATH . "/page.submit.php";
					$_GET['p'] = "submit";
					break;
				case 4:
					RewritePageTitle("Протест бана");
					$page = TEMPLATES_PATH . "/page.protest.php";
					$_GET['p'] = "protest";
					break;
				case 5:
					RewritePageTitle("Блокировки чата");
					$page = TEMPLATES_PATH ."/page.commslist.php";
					$_GET['p'] = 'commslist';
					break;
				case 6:
					$page = TEMPLATES_PATH . "/page.static.php";
					$_GET['p'] = $GLOBALS['db']->GetOne("SELECT `url` FROM `" . DB_PREFIX . "_pages` WHERE `id` = " . (int) $GLOBALS['config']['config.defaultpage.static_id'] . ';');
					break;
				case 0:
					RewritePageTitle("Главная");
					$page = TEMPLATES_PATH . "/page.home.php";
					$_GET['p'] = "home";
					break;
			}
		break;
}

// Начинаем буферизовать вывод. Необходимо для более корректной работы хандлера ошибок.
ob_start();

// Подключаем графический фреймворк
require_once(INCLUDES_PATH . "/theme_framework.php");

global $ui;
$ui = new CUI();
BuildPageHeader();
BuildPageTabs();
BuildSubMenu();
BuildContHeader();
BuildBreadcrumbs();
if(!empty($page))
	include $page;
include_once(TEMPLATES_PATH . '/footer.php');

$page = ob_get_clean();
echo(str_replace("[{(REWRITE_page_title)}]", $GLOBALS['TitleRewrite'], $page));
?>
