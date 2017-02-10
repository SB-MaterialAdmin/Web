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

global $theme;
$srv_admins = $GLOBALS['db']->GetAll("SELECT authid, user
										FROM " . DB_PREFIX . "_admins_servers_groups AS asg						
										LEFT JOIN " . DB_PREFIX . "_admins AS a ON a.aid = asg.admin_id			
										WHERE (server_id = " . (int)$_GET['id'] . " OR srv_group_id = ANY					
										(															
			   								SELECT group_id											
			   								FROM " . DB_PREFIX . "_servers_groups									
			   								WHERE server_id = " . (int)$_GET['id'] . ")									
										)															
										GROUP BY aid, authid, srv_password, srv_group, srv_flags, user ");
$i = 0;
foreach($srv_admins as $admin) {
	$admsteam[] = $admin['authid'];
}
if(sizeof($admsteam)>0 && $serverdata = checkMultiplePlayers((int)$_GET['id'], $admsteam))
	$noproblem = true;
foreach($srv_admins as $admin) {
	$admins[$i]['user'] = $admin['user'];
	$admins[$i]['authid'] = $admin['authid'];
	if(isset($noproblem) && isset($serverdata[$admin['authid']])) {
	$admins[$i]['ingame'] = true;
	$admins[$i]['iname'] = $serverdata[$admin['authid']]['name'];
	$admins[$i]['iip'] = $serverdata[$admin['authid']]['ip'];
	$admins[$i]['iping'] = $serverdata[$admin['authid']]['ping'];
	$admins[$i]['itime'] = $serverdata[$admin['authid']]['time'];
	} else
		$admins[$i]['ingame'] = false;
	$i++;
}
										
$theme->assign('admin_count', count($srv_admins));
$theme->assign('admin_list', $admins);
?>


<div id="admin-page-content">
<div id="0" style="display:none;">

<?php $theme->display('page_admin_servers_adminlist.tpl'); ?>

</div>
</div>
