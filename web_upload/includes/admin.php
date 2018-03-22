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

global $userbank;

if(!isset($_GET['c']))
{
	include TEMPLATES_PATH . "/page.admin.php";
	RewritePageTitle("Администрирование");
}
else 
{
	// ###################[ Admin Groups ]##################################################################
	if($_GET['c'] == "groups")
	{
		CheckAdminAccess( ADMIN_OWNER|ADMIN_LIST_GROUPS|ADMIN_ADD_GROUP|ADMIN_EDIT_GROUPS|ADMIN_DELETE_GROUPS );
		if(!isset($_GET['o']))
		{
			// ====================[ ADMIN SIDE MENU START ] ===================
			$groupsTabMenu = new CTabsMenu();
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_LIST_GROUPS ) )
				$groupsTabMenu->addMenuItem("Список групп", 0);
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_GROUP ) )
				$groupsTabMenu->addMenuItem("Добавить группу", 1);
			$groupsTabMenu->outputMenu();
			// ====================[ ADMIN SIDE MENU END ] ===================	
			
			include TEMPLATES_PATH . "/admin.groups.php";	
			RewritePageTitle("Управление группами");
		}
		elseif($_GET['o'] == 'edit')
		{
			$groupsTabMenu = new CTabsMenu();
			$groupsTabMenu->addMenuItem("Назад",0, "", "javascript:history.go(-1);", true);
			$groupsTabMenu->outputMenu();
			
			include TEMPLATES_PATH . "/admin.edit.group.php";
			RewritePageTitle("Редактировать группы");
		}
	}elseif($_GET['c'] == "admins")
	 // ###################[ Admins ]##################################################################
	{
		// Make sure they are allowed here oO
		CheckAdminAccess( ADMIN_OWNER|ADMIN_LIST_ADMINS|ADMIN_ADD_ADMINS|ADMIN_EDIT_ADMINS|ADMIN_DELETE_ADMINS );
		if(!isset($_GET['o']))
		{	
			// ====================[ ADMIN SIDE MENU START ] ===================
			$adminTabMenu = new CTabsMenu();
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_LIST_ADMINS ) )
				$adminTabMenu->addMenuItem("Список администраторов", 0);		
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_ADMINS ) )
			{
				$adminTabMenu->addMenuItem("Добавить админа", 1);
				$adminTabMenu->addMenuItem("Переопределения", 2);
			}
			$adminTabMenu->outputMenu();
			// ====================[ ADMIN SIDE MENU END ] ===================
			$AdminsPerPage = SB_BANS_PER_PAGE;
			$page = 1;
			$join = "";
			$where = "";
			$advSearchString = "";
			if (isset($_GET['page']) && $_GET['page'] > 0)
			{
				$page = intval($_GET['page']);
			}
			if(isset($_GET['advSearch']))
			{
				// Escape the value, but strip the leading and trailing quote
				$value = substr($GLOBALS['db']->qstr($_GET['advSearch'], get_magic_quotes_gpc()), 1, -1);
				$type = $_GET['advType'];
				switch($type)
				{
					case "name":
						$where = " AND ADM.user LIKE '%" . $value . "%'";
					break;
					case "steamid":
						$where = " AND ADM.authid = '" . $value . "'";
					break;
					case "steam":
						$where = " AND ADM.authid LIKE '%" . $value . "%'";
					break;
					case "admemail":
						$where = " AND ADM.email LIKE '%" . $value . "%'";
					break;
					case "webgroup":
						$where = " AND ADM.gid = '" . $value . "'";
					break;
					case "srvadmgroup":
						$where = " AND ADM.srv_group = '" . $value . "'";
					break;
					case "srvgroup":
						$where = " AND SG.srv_group_id = '" . $value . "'";
						$join = " LEFT JOIN `" . DB_PREFIX . "_admins_servers_groups` AS SG ON SG.admin_id = ADM.aid";
					break;
					case "admwebflag":
						$findflags = explode(",",$value);
						foreach($findflags AS $flag)
							$flags[] = constant($flag);
						$flagstring = implode('|',$flags);
						$alladmins = $GLOBALS['db']->Execute("SELECT aid FROM `" . DB_PREFIX . "_admins` WHERE aid > 0");
						while(!$alladmins->EOF)
						{
							if($userbank->HasAccess($flagstring, $alladmins->fields["aid"])) {
								if(!isset($accessaid))
									$accessaid = $alladmins->fields["aid"];
								$accessaid .= ",".$alladmins->fields["aid"];
							}
							$alladmins->MoveNext();
						}
						$where = " AND ADM.aid IN(".$accessaid.")";
					break;
					case "admsrvflag":
						$findflags = explode(",",$value);
						foreach($findflags AS $flag)
							$flags[] = constant($flag);
						$alladmins = $GLOBALS['db']->Execute("SELECT aid, authid FROM `" . DB_PREFIX . "_admins` WHERE aid > 0");
						while(!$alladmins->EOF)
						{
							foreach($flags AS $fla) {
								if(strstr(get_user_admin($alladmins->fields["authid"]), $fla)) {
									if(!isset($accessaid))
										$accessaid = $alladmins->fields["aid"];
									$accessaid .= ",".$alladmins->fields["aid"];
								}
							}
							if(strstr(get_user_admin($alladmins->fields["authid"]), 'z')) {
								if(!isset($accessaid))
									$accessaid = $alladmins->fields["aid"];
								$accessaid .= ",".$alladmins->fields["aid"];
							}
							$alladmins->MoveNext();
						}
						$where = " AND ADM.aid IN(".$accessaid.")";
					break;
					case "server":
						$where = " AND (ASG.server_id = '" . $value . "' OR SG.server_id = '" . $value . "')";
						$join = " LEFT JOIN `" . DB_PREFIX . "_admins_servers_groups` AS ASG ON ASG.admin_id = ADM.aid LEFT JOIN `" . DB_PREFIX . "_servers_groups` AS SG ON SG.group_id = ASG.srv_group_id";
					break;
					default:
						$_GET['advSearch'] = "";
						$_GET['advType'] = "";
						$where = "";
					break;
				}
				$advSearchString = "&advSearch=".$_GET['advSearch']."&advType=".$_GET['advType'];
			}
			if($_GET['showexpiredadmins'] == 'true') {
				$where2 = " AND (ADM.expired < ".time()." AND ADM.expired <> 0)";
			}
			else {
				$where2 = " AND (ADM.expired > ".time()." OR ADM.expired = 0)";
			}
			$admins = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_admins` AS ADM".$join." WHERE ADM.aid > 0".$where2."".$where." ORDER BY user LIMIT " . intval(($page-1) * $AdminsPerPage) . "," . intval($AdminsPerPage));
			// quick fix for the server search showing admins mulitple times.
			if(isset($_GET['advSearch']) && isset($_GET['advType']) && $_GET['advType'] == 'server') {

				$aadm = array();
				$num = 0;
				foreach($admins as $aadmin) {
					if(!in_array($aadmin['aid'], $aadm))
						$aadm[] = $aadmin['aid'];
					else 
						unset($admins[$num]);
					$num++;
				}
			}
			
			$query = $GLOBALS['db']->GetRow("SELECT COUNT(ADM.aid) AS cnt FROM `" . DB_PREFIX . "_admins` AS ADM".$join." WHERE ADM.aid > 0".$where2."".$where);
			$admin_count = $query['cnt'];
			include TEMPLATES_PATH . "/admin.admins.php";
			RewritePageTitle("Управление админами");
		}
		elseif($_GET['o'] == 'editgroup' || $_GET['o'] == 'editdetails' || $_GET['o'] == 'editpermissions' || $_GET['o'] == 'editservers')
		{
			$adminTabMenu = new CTabsMenu();
			$adminTabMenu->addMenuItem("Назад", 0,"", "javascript:history.go(-1);", true);
			$adminTabMenu->outputMenu();
		
			if($_GET['o'] == 'editgroup')
			{
				include TEMPLATES_PATH . "/admin.edit.admingroup.php";
				RewritePageTitle("Редактировать группу админов");
			}
			elseif($_GET['o'] == 'editdetails')
			{
				include TEMPLATES_PATH . "/admin.edit.admindetails.php";
				RewritePageTitle("Редактировать детали админа");
			}
			elseif($_GET['o'] == 'editpermissions')
			{
				include TEMPLATES_PATH . "/admin.edit.adminperms.php";
				RewritePageTitle("Редактировать разрешения админа");
			}
			elseif($_GET['o'] == 'editservers')
			{
				include TEMPLATES_PATH . "/admin.edit.adminservers.php";
				RewritePageTitle("Редактировать доступ к серверам");
			}
		} elseif ($_GET['o'] == 'warnings' && $GLOBALS['config']['admin.warns'] == "1") {
			$tabs = new CTabsMenu();
			$tabs->addMenuItem("Назад", -1,"", "javascript:history.go(-1);", true);
			$tabs->outputMenu();
			include(TEMPLATES_PATH . '/admin.admins.warnings.php');
			RewritePageTitle("Список предупреждений");
		}
	}
	elseif($_GET['c'] == "servers")
	 // ###################[ Servers ]##################################################################
	{
		// Make sure they are allowed here oO
		CheckAdminAccess( ADMIN_OWNER|ADMIN_LIST_SERVERS|ADMIN_ADD_SERVER|ADMIN_EDIT_SERVERS|ADMIN_DELETE_SERVERS );
		if(!isset($_GET['o']))
		{
			// ====================[ ADMIN SIDE MENU START ] ===================
			$serverTabMenu = new CTabsMenu();
			if($userbank->HasAccess( ADMIN_OWNER|ADMIN_LIST_SERVERS ) )
				$serverTabMenu->addMenuItem("Список серверов",0);	
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_SERVER ) )
				$serverTabMenu->addMenuItem("Добавить новый сервер",1);
			$serverTabMenu->outputMenu();
			// ====================[ ADMIN SIDE MENU END ] ===================
			
			include TEMPLATES_PATH . "/admin.servers.php";
			RewritePageTitle("Управление серверами");
		}
		elseif($_GET['o'] == 'edit')
		{
			$serverTabMenu = new CTabsMenu();
			$serverTabMenu->addMenuItem("Назад", 0,"", "javascript:history.go(-1);", true);
			$serverTabMenu->outputMenu();		
			
			include TEMPLATES_PATH . "/admin.edit.server.php";
			RewritePageTitle("Редактировать сервер");
		}
		elseif($_GET['o'] == 'rcon')
		{
			$serverTabMenu = new CTabsMenu();
			$serverTabMenu->addMenuItem("Назад", 0,"", "javascript:history.go(-1);", true);
			$serverTabMenu->outputMenu();
						
			include TEMPLATES_PATH . "/admin.rcon.php";
			RewritePageTitle("RCON");
		}
		elseif($_GET['o'] == 'dbsetup')
		{
			$serverTabMenu = new CTabsMenu();
			$serverTabMenu->addMenuItem("Назад", 0,"", "javascript:history.go(-1);", true);
			$serverTabMenu->outputMenu();
						
			include TEMPLATES_PATH . "/admin.servers.db.php";
			RewritePageTitle("Настройки базы данных");
		}
		elseif($_GET['o'] == 'admincheck')
		{
			$serverTabMenu = new CTabsMenu();
			$serverTabMenu->addMenuItem("Назад", 0,"", "javascript:history.go(-1);", true);
			$serverTabMenu->outputMenu();
						
			include TEMPLATES_PATH . "/admin.srvadmins.php";
			RewritePageTitle("Админы сервера");
		}
	}
	elseif($_GET['c'] == "bans")
	 // ###################[ Bans ]##################################################################
	{
		CheckAdminAccess( ADMIN_OWNER|ADMIN_ADD_BAN|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_GROUP_BANS|ADMIN_EDIT_ALL_BANS|ADMIN_BAN_PROTESTS|ADMIN_BAN_SUBMISSIONS );
		
		if(!isset($_GET['o']))
		{
			// ====================[ ADMIN SIDE MENU START ] ===================
			$banTabMenu = new CTabsMenu();
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN ) ) {
				$banTabMenu->addMenuItem("Добавить бан",0);
				if($GLOBALS['config']['config.enablegroupbanning']==1)
					$banTabMenu->addMenuItem("Бан группы",4);
			}
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_PROTESTS ) )
				$banTabMenu->addMenuItem("Протесты банов",1);
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_SUBMISSIONS ) )
				$banTabMenu->addMenuItem("Заявки на бан", 2);
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_BAN_IMPORT ) )
				$banTabMenu->addMenuItem("Импорт банов", 3);
			$banTabMenu->addMenuItem("Список банов", 5, "", "index.php?p=banlist",true);
			$banTabMenu->outputMenu();
			// ====================[ ADMIN SIDE MENU END ] ===================

			include TEMPLATES_PATH . "/admin.bans.php";
			
			if(isset($_GET['mode']) && $_GET['mode'] == "delete")
				echo "<script>ShowBox('Бан удален', 'Бан был успешно удален из системы SourceBans', 'green', '', true);</script>";
			elseif(isset($_GET['mode']) && $_GET['mode']=="unban")
				echo "<script>ShowBox('Игрок разбанен', 'Игрок был успешно разбанен', 'green', '', true);</script>";
			
			RewritePageTitle("Bans");
		}
		elseif($_GET['o'] == 'edit')
		{
			$banTabMenu = new CTabsMenu();
			$banTabMenu->addMenuItem("Назад", 0,"", "javascript:history.go(-1);", true);
			$banTabMenu->outputMenu();			
			
			include TEMPLATES_PATH . "/admin.edit.ban.php";
			RewritePageTitle("Редактировать детали бана");
		}
		elseif($_GET['o'] == 'email')
		{
			$banTabMenu = new CTabsMenu();
			$banTabMenu->addMenuItem("Назад", 0, "", "javascript:history.go(-1);", true);
			$banTabMenu->outputMenu();					
			
			include TEMPLATES_PATH . "/admin.email.php";
			RewritePageTitle("Email");
		}
	}
	elseif($_GET['c'] == "comms")
	 // ###################[ Comms ]##################################################################
	{
		CheckAdminAccess( ADMIN_OWNER|ADMIN_ADD_BAN|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_ALL_BANS );
		
		if(!isset($_GET['o']))
		{
			// ====================[ ADMIN SIDE MENU START ] ===================
			$banTabMenu = new CTabsMenu();
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN ) ) {
				$banTabMenu->addMenuItem("Добавить блокировку", 0);
			}
			$banTabMenu->addMenuItem("Список мутов/гагов", 1, "", "index.php?p=commslist",true);
			$banTabMenu->outputMenu();
			// ====================[ ADMIN SIDE MENU END ] ===================

			include TEMPLATES_PATH . "/admin.comms.php";
			
			if(isset($_GET['mode']) && $_GET['mode'] == "delete")
				echo "<script>ShowBox('Бан удален', 'Бан был успешно удален из SourceBans', 'green', '', true);</script>";
			elseif(isset($_GET['mode']) && $_GET['mode']=="unban")
				echo "<script>ShowBox('Игрок разбанен', 'Игрок был успешно разбанен', 'green', '', true);</script>";
			
			RewritePageTitle("Comms");
		}
		elseif($_GET['o'] == 'edit')
		{
			$banTabMenu = new CTabsMenu();
			$banTabMenu->addMenuItem("Назад", 0,"", "javascript:history.go(-1);", true);
			$banTabMenu->outputMenu();			
			
			include TEMPLATES_PATH . "/admin.edit.comms.php";
			RewritePageTitle("Редактировать детали блокировки");
		}
	}
	elseif($_GET['c'] == "mods")
	 // ###################[ Mods ]##################################################################
	{
		CheckAdminAccess( ADMIN_OWNER|ADMIN_LIST_MODS|ADMIN_ADD_MODS|ADMIN_EDIT_MODS|ADMIN_DELETE_MODS );
		if(!isset($_GET['o']))
		{
			// ====================[ ADMIN SIDE MENU START ] ===================
			$modTabMenu = new CTabsMenu();
			if($userbank->HasAccess( ADMIN_OWNER|ADMIN_LIST_MODS ) )
				$modTabMenu->addMenuItem("Список МОДов",0);
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_MODS ) ) {
				$modTabMenu->addMenuItem("Добавить МОД",1);
				// $modTabMenu->addMenuItem("Установить МОД из репозитория",2,"","index.php?p=admin&c=mods&o=repo", true);
			}
			$modTabMenu->outputMenu();
			// ====================[ ADMIN SIDE MENU END ] ===================	
			
			$mod_list = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_mods` WHERE mid > 0 ORDER BY name ASC") ;
			$query = $GLOBALS['db']->GetRow("SELECT COUNT(mid) AS cnt FROM `" . DB_PREFIX . "_mods`") ;
			$mod_count = $query['cnt'];
			include TEMPLATES_PATH . "/admin.mods.php";
			RewritePageTitle("Управление МОДами");	
		}
		elseif($_GET['o'] == 'edit')
		{
			$modTabMenu = new CTabsMenu();
			$modTabMenu->addMenuItem("Назад",0, "", "javascript:history.go(-1);", true);
			$modTabMenu->outputMenu();					
			
			include TEMPLATES_PATH . "/admin.edit.mod.php";
			RewritePageTitle("Редактировать детали МОДа");
		}
		/*elseif($_GET['o'] == "repo")
		{
			include TEMPLATES_PATH . "/admin.mod.repo.php";
			RewritePageTitle("Установить МОД из репозитория");
		}*/
	}
	elseif($_GET['c'] == "settings")
	 // ###################[ Settings ]##################################################################
	{
		CheckAdminAccess( ADMIN_OWNER|ADMIN_WEB_SETTINGS );	
		// ====================[ ADMIN SIDE MENU START ] ===================
			$settingsTabMenu = new CTabsMenu();
			if($userbank->HasAccess(ADMIN_OWNER|ADMIN_WEB_SETTINGS ) )
			{
				$settingsTabMenu->addMenuItem("Главные настройки",0);
				$settingsTabMenu->addMenuItem("Опции",3);
			}
			$settingsTabMenu->addMenuItem("Шаблон", 1);
			$settingsTabMenu->addMenuItem("Системный лог", 2);
			$settingsTabMenu->outputMenu();
		// ====================[ ADMIN SIDE MENU END ] ===================
	
		include TEMPLATES_PATH . "/admin.settings.php";
		RewritePageTitle("Настройки SourceBans");
	}
	elseif($_GET['c'] == "pay_card")
	 // ###################[ Settings ]##################################################################
	{
		CheckAdminAccess( ADMIN_OWNER );	
		// ====================[ ADMIN SIDE MENU START ] ===================
			$settingsTabMenu = new CTabsMenu();
			if($userbank->HasAccess(ADMIN_OWNER))
			{
				$settingsTabMenu->addMenuItem("Список",0);
				$settingsTabMenu->addMenuItem("Добавить",1);
				$settingsTabMenu->outputMenu();
			}
		// ====================[ ADMIN SIDE MENU END ] ===================
	
		include TEMPLATES_PATH . "/admin.pay_card.php";
		RewritePageTitle("Настройки Ваучеров");
	}
	//////////////////////////////////////////////////////
	elseif($_GET['c'] == "menu")
	// ###################[ Settings ]##################################################################
	{
		CheckAdminAccess( ADMIN_OWNER );	
		if($_GET['o'] == 'edit')
		{
			$banTabMenu = new CTabsMenu();
			$banTabMenu->addMenuItem("Назад", 0,"", "javascript:history.go(-1);", true);
			$banTabMenu->outputMenu();			
			
			include TEMPLATES_PATH . "/admin.menu.edit.php";
			RewritePageTitle("Редактирование меню");
		}else{
		// ====================[ ADMIN SIDE MENU START ] ===================
			$settingsTabMenu = new CTabsMenu();
			if($userbank->HasAccess(ADMIN_OWNER))
			{
				$settingsTabMenu->addMenuItem("Список",0);
				$settingsTabMenu->addMenuItem("Добавить",1);
				$settingsTabMenu->outputMenu();
			}
		// ====================[ ADMIN SIDE MENU END ] ===================
	
		include TEMPLATES_PATH . "/admin.menu.php";
		RewritePageTitle("Управление меню");
		}
	}
	
}
echo '</div></div></div>';
?>
