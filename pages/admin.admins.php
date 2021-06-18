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
?>

<div class="tab-content p-0" id="admin-page-content">
<?php
if(!defined("IN_SB")){echo "Ошибка доступа!";die();}
global $userbank, $ui;

if (isset($_GET['page']) && $_GET['page'] > 0)
{
	$page = intval($_GET['page']);
}

$AdminsStart = intval(($page-1) * $AdminsPerPage);
$AdminsEnd = intval($AdminsStart+$AdminsPerPage);
if ($AdminsEnd > $admin_count) $AdminsEnd = $admin_count;

function SteamID2CommunityID($steamid) 
{ 
    $parts = explode(':', str_replace('STEAM_', '' ,$steamid)); 

    return bcadd(bcadd('76561197960265728', $parts['1']), bcmul($parts['2'], '2')); 
} 

// List Page
$admin_list = array();
foreach($admins AS $admin)
{
	$admin['immunity'] = $userbank->GetProperty("srv_immunity", $admin['aid']);
	$admin['web_group'] = $userbank->GetProperty("group_name", $admin['aid']);
	$admin['server_group'] = $userbank->GetProperty("srv_groups", $admin['aid']);
	
	// Add contakt
	$admin['vk_profile'] = $userbank->GetProperty("vk", $admin['aid']);
	if($admin['vk_profile'] == ""){
		$admin['vk_profile'] = "Нет данных";
	}else{
		$admin['vk_profile'] = htmlspecialchars($admin['vk_profile']);
		$admin['vk_profile'] = "<a href='https://vk.com/" .$admin['vk_profile'] . "'>" . $admin['vk_profile'] . "</a>";
	}
	
	$admin['sk_profile'] = $userbank->GetProperty("skype", $admin['aid']);
	if($admin['sk_profile'] == ""){
		$admin['sk_profile'] = "Нет данных";
	}else{
		$admin['sk_profile'] = $admin['sk_profile'] . " (<a href='skype:" . $admin['sk_profile'] . "?userinfo'>профиль</a>)";
	}
	
	$admin['comment_profile'] = $userbank->GetProperty("comment", $admin['aid']);
	if($admin['comment_profile'] == ""){
		$admin['comment_profile'] = "Нет доступных комментариев.";
	}
	
	$admin['email_profile'] = $userbank->GetProperty("email", $admin['aid']);
	$admin['communityid_profile'] = SteamID2CommunityID($userbank->GetProperty("authid", $admin['aid']));
	$admin['steam_id_amd'] = $userbank->GetProperty("authid", $admin['aid']);
	// Add contakt
	
	if(empty($admin['web_group']) || $admin['web_group']==" ")
	{
  		$admin['web_group'] = "Группа\индивид. права отсутствуют";
	}
	if(empty($admin['server_group']) || $admin['server_group']==" ")
	{
		$admin['server_group'] = "Группа\индивид. права отсутствуют";
	}
	$num = $GLOBALS['db']->GetRow("SELECT count(authid) AS num FROM `" . DB_PREFIX . "_bans` WHERE aid = '".$admin['aid']."'");
	$admin['bancount'] = $num['num'];

	$nodem = $GLOBALS['db']->GetRow("SELECT count(B.bid) AS num FROM `" . DB_PREFIX . "_bans` AS B WHERE aid = '".$admin['aid']."' AND NOT EXISTS (SELECT D.demid FROM `" . DB_PREFIX . "_demos` AS D WHERE D.demid = B.bid)");
	$admin['aid'] = $admin['aid'];
	$admin['nodemocount'] = $nodem['num'];

	$admin['name'] = stripslashes($admin['user']);
	$admin['server_flag_string'] = SmFlagsToSb($userbank->GetProperty("srv_flags",$admin['aid']));
	$admin['web_flag_string'] = BitToString($userbank->GetProperty("extraflags",$admin['aid']));
	

	if($admin['expired'] == 0) {
		$admin['expired_text'] = 'Никогда';
	}
	elseif($admin['expired'] < time()) {
		$admin['expired_text'] = 'Истёк';
	}
	else{
		$admin['expired_text'] = 'Через&nbsp;'.round((($admin['expired'] - time()) / 86400),0).'&nbsp;дн.';
	}
	if($admin['expired'] == 0) {
		$admin['expired_cv'] = 'Навсегда';
		$admin['del_link_d'] = 'if(confirm(\'У этого админа Вечная админка.\nВы действительно хотите удалить его?\')) { RemoveAdmin('.$admin['aid'].', \''.$admin['user'].'\'); }';
	}
	elseif($admin['expired'] < time()) {
		$admin['expired_cv'] = 'Уже <b>Истек</b>';
		$admin['del_link_d'] = 'RemoveAdmin('.$admin['aid'].', \''.$admin['user'].'\');';
	} else {
		$admin['expired_cv'] = date('До d.m.Y в <b>H:i</b>',$admin['expired']);
		$admin['del_link_d'] = 'if(confirm(\'У этого админа не истёк срок админки.\nВы действительно хотите удалить его?\')) { RemoveAdmin('.$admin['aid'].', \''.$admin['user'].'\'); }';
	}
	
	$lastvisit = $userbank->GetProperty("lastvisit", $admin['aid']);
	if(!$lastvisit)
		$admin['lastvisit'] = "Никогда";
	else
		$admin['lastvisit'] = SBDate($dateformat,$userbank->GetProperty("lastvisit", $admin['aid']));
	$admin['avatar'] = GetUserAvatar($userbank->GetProperty('authid', $admin['aid']));

	$admin['warnings'] = $GLOBALS['db']->GetOne("SELECT COUNT(*) FROM `" . DB_PREFIX . "_warns` WHERE `expires` > " . time() . " AND `arecipient` = " . (int) $admin['aid'] . ";");
	array_push($admin_list, $admin);
}

if ($page > 1)
{
	$prev = CreateLinkR('<i class="zmdi zmdi-chevron-left"></i>',"index.php?p=admin&c=admins&page=" .($page-1). $advSearchString);
}
else
{
	$prev = "";
}
if ($AdminsEnd < $admin_count)
{
	$next = CreateLinkR('<i class="zmdi zmdi-chevron-right"></i>',"index.php?p=admin&c=admins&page=" .($page+1).$advSearchString);
}
else
	$next = "";

//=================[ Start Layout ]==================================
//$admin_nav = 'displaying&nbsp;'.$AdminsStart.'&nbsp;-&nbsp;'.$AdminsEnd.'&nbsp;of&nbsp;'.$admin_count.'&nbsp;results';

$pages = ceil($admin_count/$AdminsPerPage);
if($pages > 1) {
	if (isset($_GET['showexpiredadmins']))
		$admin_nav_p = ' / Страницы: <select class="form-control" onchange="window.location=\'index.php?p=admin&c=admins&showexpiredadmins=true&page=\' + $(\'PageChanger\').value;" style="display: inline-block;width: 40px;" id="PageChanger">';
	else
		$admin_nav_p = ' / Страницы: <select class="form-control" onchange="changePage(this,\'A\',\''.$_GET['advSearch'].'\',\''.$_GET['advType'].'\');" style="display: inline-block;width: 40px;">';
	
	for($i=1;$i<=$pages;$i++) {
		if($i==$_GET["page"]) {
			$admin_nav_p .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
			continue;
		}
		$admin_nav_p .= '<option value="' . $i . '">' . $i . '</option>';
	}
	$admin_nav_p .= '</select>&nbsp;';
}

$admin_nav = '<ul class="pagination">';
	
if (strlen($prev) > 0)
{
	$admin_nav .= '<li>'.$prev.'</li>';
}
if (strlen($next) > 0)
{
	$admin_nav .= '<li>'.$next.'</li>';
}

$admin_nav .= '</ul>&nbsp;';

if(isset($_GET["showexpiredadmins"]) && $_GET["showexpiredadmins"] == "true") {
	$btn_icon = "zmdi-alarm";
	$btn_helpa = 'data-trigger="hover" data-toggle="tooltip" data-placement="top" data-original-title="Показать действующих администраторов" title=""';
	$btn_href = "index.php?p=admin&c=admins";
	$btn_rem = '<button type="button" onclick="removeExpiredAdmins()" class="btn bgm-bluegray btn-block waves-effect">Удалить всех истёкших админов</button>';
} else{
	$btn_icon = "zmdi-timer-off";
	$btn_helpa = 'data-trigger="hover" data-toggle="tooltip" data-placement="top" data-original-title="Показать истекших администраторов" title=""';
	$btn_href = "index.php?p=admin&c=admins&showexpiredadmins=true";
	$btn_rem = '';
}

$res = $GLOBALS['db']->Execute("SELECT aid FROM `".DB_PREFIX."_admins` WHERE `support` = '1'");
$checked = array();
while (!$res->EOF)
{
    $chek_in = array();
	$chek_in['kid'] = $res->fields['aid'];
	array_push($checked,$chek_in);
	$res->MoveNext();
}


echo '<div id="0" style="display:none;">';
	$theme->assign('checked_if', $checked);
	$theme->assign('permission_listadmin', $userbank->HasAccess(ADMIN_OWNER|ADMIN_LIST_ADMINS));
	$theme->assign('permission_editadmin', $userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_ADMINS));
	$theme->assign('permission_deleteadmin', $userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_ADMINS));
	$theme->assign('admin_count', $admin_count);
	$theme->assign('admin_nav', $admin_nav);
	$theme->assign('admin_nav_p', $admin_nav_p);
	$theme->assign('admins', $admin_list);
	$theme->assign('btn_helpa', $btn_helpa);
	$theme->assign('btn_rem', $btn_rem);
	$theme->assign('btn_href', $btn_href);
	$theme->assign('btn_icon', $btn_icon);
	$theme->assign('allow_warnings', ($GLOBALS['config']['admin.warns'] == "1"));
	$theme->assign('maxWarnings', $GLOBALS['config']['admin.warns.max']);
	$theme->display('page_admin_admins_list.tpl');
echo '</div>';




// Add Page
$group_list = 				$GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_groups` WHERE type = '3'");
$servers = 					$GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_servers`");
$server_admin_group_list = 	$GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_srvgroups`");
$server_group_list = 		$GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_groups` WHERE type != 3");
$server_list = array();
$serverscript = "<script type=\"text/javascript\">";
foreach($servers AS $server)
{
    $serverscript .= "xajax_ServerHostPlayers('".$server['sid']."', 'id', 'sa".$server['sid']."');";
	$info['sid'] = $server['sid'];
	$info['ip'] = $server['ip'];
	$info['port'] = $server['port'];
	array_push($server_list, $info);
}
$serverscript .= "</script>";

echo '<div id="1" style="display:none;">';
	$theme->assign('group_list', $group_list);
	$theme->assign('server_list', $server_list);
	$theme->assign('server_script', $serverscript);
	$theme->assign('server_admin_group_list', $server_admin_group_list);
	$theme->assign('server_group_list', $server_group_list);
	$theme->assign('permission_addadmin', $userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_ADMINS));
	$theme->display('page_admin_admins_add.tpl');
echo '</div>';




// Overrides

// Saving changed overrides
$overrides_error = "";
$overrides_save_success = false;
try
{
	if(isset($_POST['new_override_name']))
	{
		// Handle old overrides, if there are any.
		if(isset($_POST['override_id']))
		{
			// Apply changes first
			$edit_errors = "";
			foreach($_POST['override_id'] as $index => $id)
			{
				// Skip invalid stuff?!
				if($_POST['override_type'][$index] != "command" && $_POST['override_type'][$index] != "group")
					continue;
			
				$id = (int)$id;
				// Wants to delete this override?
				if(empty($_POST['override_name'][$index]))
				{
					$GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_overrides` WHERE id = ?;", array($id));
					continue;
				}
				
				// Check for duplicates
				$chk = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_overrides` WHERE name = ? AND type = ? AND id != ?", array($_POST['override_name'][$index], $_POST['override_type'][$index], $id));
				if(!empty($chk))
				{
					$edit_errors .= "&bull; Такое название уже существует \\\"" . htmlspecialchars(addslashes($_POST['override_name'][$index])) . "\\\".<br />";
					continue;
				}
				
				// Edit the override
				$GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_overrides` SET name = ?, type = ?, flags = ? WHERE id = ?;", array($_POST['override_name'][$index], $_POST['override_type'][$index], trim($_POST['override_flags'][$index]), $id));
			}
			
			if(!empty($edit_errors))
				throw new Exception("Ошибки ваших изменений:<br /><br />" . $edit_errors);
		}
	
		// Add a new override
		if(!empty($_POST['new_override_name']))
		{
			if($_POST['new_override_type'] != "command" && $_POST['new_override_type'] != "group")
				throw new Exception("Неверный оверрайд.");
			
			// Check for duplicates
			$chk = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_overrides` WHERE name = ? AND type = ?", array($_POST['new_override_name'], $_POST['new_override_type']));
			if(!empty($chk))
				throw new Exception("Оверрайд с таким именем уже существует.");
			
			// Insert the new override
			$GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_overrides` (type, name, flags) VALUES (?, ?, ?);", array($_POST['new_override_type'], $_POST['new_override_name'], trim($_POST['new_override_flags'])));
		}
		
		$overrides_save_success = true;
	}
} catch (Exception $e) {
	$overrides_error = $e->getMessage();
}

$overrides_list = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_overrides`;");

echo '<div id="2" style="display:none;">';
	$theme->assign('overrides_list', $overrides_list);
	$theme->assign('overrides_error', $overrides_error);
	$theme->assign('overrides_save_success', $overrides_save_success);
	$theme->assign('permission_addadmin', $userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_ADMINS));
	$theme->display('page_admin_overrides.tpl');
echo '</div>';
?>
</div>
