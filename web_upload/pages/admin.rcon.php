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

global $theme, $userbank;

$sid = (int)$_GET['id'];

// Access on that server?
$servers = $GLOBALS['db']->GetAll("SELECT `server_id`, `srv_group_id` FROM ".DB_PREFIX."_admins_servers_groups WHERE admin_id = ". $userbank->GetAid());
$access = false;
foreach($servers as $server)
{
    if($server['server_id'] == $sid)
    {
        $access = true;
        break;
    }
    if($server['srv_group_id'] > 0)
    {
        $servers_in_group = $GLOBALS['db']->GetAll("SELECT `server_id` FROM ".DB_PREFIX."_servers_groups WHERE group_id = ". (int)$server['srv_group_id']);
        foreach($servers_in_group as $servig)
        {
            if($servig['server_id'] == $sid)
            {
                $access = true;
                break 2;
            }
        }
    }
}

$theme->assign('id', $sid);
$theme->assign('permission_rcon', ($access && $userbank->HasAccess(SM_RCON . SM_ROOT)));
$theme->left_delimiter = '-{';
$theme->right_delimiter = '}-';

$theme->display('page_admin_servers_rcon.tpl');

$theme->left_delimiter = '{';
$theme->right_delimiter = '}';
?>

