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
global $userbank, $theme;

if(!$userbank->HasAccess(ADMIN_OWNER))
{
	echo "Доступ запрещен!";
}
else
{
	
$srv_cfg = '"Databases"
{
	"driver_default"		"mysql"
	
	// Если вы используете старую серверную часть:
	"sourcebans"
	{
		"driver"			"mysql"
		"host"				"{server}"
		"database"			"{db}"
		"user"				"{user}"
		"pass"				"{pass}"
		"port"				"{port}"
	}
	
	"sourcecomms"
	{
		"driver"			"mysql"
		"host"				"{server}"
		"database"			"{db}"
		"user"				"{user}"
		"pass"				"{pass}"
		"port"				"{port}"
	}
	
	// Если вы используете новую серверную часть:
	"materialadmin"
	{
		"driver"			"mysql"
		"host"				"{server}"
		"database"			"{db}"
		"user"				"{user}"
		"pass"				"{pass}"
		"port"				"{port}"
	}
}
';
$srv_cfg = str_replace("{server}", DB_HOST, $srv_cfg);
$srv_cfg = str_replace("{user}", DB_USER, $srv_cfg);
$srv_cfg = str_replace("{pass}", DB_PASS, $srv_cfg);
$srv_cfg = str_replace("{db}", DB_NAME, $srv_cfg);
$srv_cfg = str_replace("{prefix}", DB_PREFIX, $srv_cfg);
$srv_cfg = str_replace("{port}", DB_PORT, $srv_cfg);	
	
if(strtolower(DB_HOST) == "localhost")
{
	ShowBox("Предупреждение локального сервера", "Вы указали, что ваш сервер MySQL работает на той же машине, что и веб-сервер, это хорошо, но, возможно, потребуется изменить следующий конфигурационный файл, чтобы установить удаленный доступ к вашему серверу MySQL." , "blue", "", true);
}

$theme->assign('conf', $srv_cfg);
?>
<div id="admin-page-content">
	<div id="0">
	<?php $theme->display('page_admin_servers_db.tpl'); ?>
	</div>
</div>
<?php } ?>

