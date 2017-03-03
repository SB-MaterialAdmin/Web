<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

function AddGroup($name, $type, $bitmask, $srvflags) {
    global $userbank, $username;
    $objResponse = new xajaxResponse();

    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_GROUP)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " попытался добавить группу, не имея на это прав.");
        return $objResponse;
    }

    $error = 0;
    $query = $GLOBALS['db']->GetRow("SELECT `gid` FROM `" . DB_PREFIX . "_groups` WHERE `name` = ?", array($name));
    $query2 = $GLOBALS['db']->GetRow("SELECT `id` FROM `" . DB_PREFIX . "_srvgroups` WHERE `name` = ?", array($name));
    if(strlen($name) == 0 || count($query) > 0 || count($query2) > 0) {
        if(strlen($name) == 0) {
            $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
            $objResponse->addScript("$('name.msg').setHTML('Введите имя для группы.');");
            $error++;
        } else if(strstr($name, ','))	{
            $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
            $objResponse->addScript("$('name.msg').setHTML('В имени группы не может быть запятой.');");
            $error++;
        } else if(count($query) > 0 || count($query2) > 0){
            $objResponse->addScript("$('name.msg').setStyle('display', 'block');");
            $objResponse->addScript("$('name.msg').setHTML('Имя группы уже используется \'" . $name . "\'');");
            $error++;
        } else {
            $objResponse->addScript("$('name.msg').setStyle('display', 'none');");
            $objResponse->addScript("$('name.msg').setHTML('');");
        }
    }

    if($type == "0") {
        $objResponse->addScript("$('type.msg').setStyle('display', 'block');");
        $objResponse->addScript("$('type.msg').setHTML('Выберите тип группы.');");
        $error++;
    } else {
        $objResponse->addScript("$('type.msg').setStyle('display', 'none');");
        $objResponse->addScript("$('type.msg').setHTML('');");
    }

    if($error > 0)
        return $objResponse;

    $query = $GLOBALS['db']->GetRow("SELECT MAX(gid) AS next_gid FROM `" . DB_PREFIX . "_groups`");
    if($type == "1") {
        // add the web group
        $query1 = $GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_groups` (`gid`, `type`, `name`, `flags`) VALUES (". (int)($query['next_gid']+1) .", '" . (int)$type . "', ?, '" . (int)$bitmask . "')", array($name));
    } elseif($type == "2") {
        if(strstr($srvflags, "#")) {
            $immunity = "0";
            $immunity = substr($srvflags, strpos($srvflags, "#")+1);
            $srvflags = substr($srvflags, 0, strlen($srvflags) - strlen($immunity)-1);
        }
        $immunity = (isset($immunity) && $immunity>0) ? $immunity : 0;
        $add_group = $GLOBALS['db']->Prepare("INSERT INTO ".DB_PREFIX."_srvgroups(immunity,flags,name,groups_immune)
                    VALUES (?,?,?,?)");
        $GLOBALS['db']->Execute($add_group,array($immunity, $srvflags, $name, " "));
    } elseif($type == "3") {
        $query1 = $GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_groups` (`gid`, `type`, `name`, `flags`) VALUES (". ($query['next_gid']+1) .", '3', ?, '0')", array($name));
    }

    $log = new CSystemLog("m", "Группа создана", "Новая группа ($name) успешно создана");
    $objResponse->addScript("ShowBox('Группа создана', 'Группа была успешно создана.', 'green', 'index.php?p=admin&c=groups', true);");
    $objResponse->addScript("TabToReload();");
    return $objResponse;
}

function EditGroup($gid, $web_flags, $srv_flags, $type, $name, $overrides, $newOverride) {
    global $userbank, $username;
    $objResponse = new xajaxResponse();

    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_GROUPS)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался редактировать детали группы, не имея на это прав.");
        return $objResponse;
    }

    if(empty($name)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался удалить название группы. У группы должно быть название.");
        return $objResponse;
    }

    $gid = (int)$gid;
    $name = RemoveCode($name);
    $web_flags = (int)$web_flags;
    if($type == "web" || $type == "server" )
        $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_groups` SET `flags` = ?, `name` = ? WHERE `gid` = ?", array($web_flags, $name, $gid));
    else if($type == "srv") {
        $gname = $GLOBALS['db']->GetRow("SELECT name FROM ".DB_PREFIX."_srvgroups WHERE id = ?", array($gid));

        if(strstr($srv_flags, "#")) {
            $immunity = 0;
            $immunity = substr($srv_flags, strpos($srv_flags, "#")+1);
            $srv_flags = substr($srv_flags, 0, strlen($srv_flags) - strlen($immunity)-1);
        }
        $immunity = ($immunity>0) ? $immunity : 0;

        // Update server stuff
        $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_srvgroups` SET `flags` = ?, `name` = ?, `immunity` = ? WHERE `id` = ?", array($srv_flags, $name, $immunity, $gid));

        $oldname = $GLOBALS['db']->GetAll("SELECT aid FROM ".DB_PREFIX."_admins WHERE srv_group = ?", array($gname['name']));
        foreach($oldname as $o) {
            $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_admins` SET `srv_group` = ? WHERE `aid` = '" . (int)$o['aid'] . "'", array($name));
        }

        // Update group overrides
        if(!empty($overrides)) {
            foreach($overrides as $override) {
                // Skip invalid stuff?!
                if($override['type'] != "command" && $override['type'] != "group")
                    continue;

                $id = (int)$override['id'];
                // Wants to delete this override?
                if(empty($override['name']))
                {
                    $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_srvgroups_overrides` WHERE id = ?;", array($id));
                    continue;
                }

                // Check for duplicates
                $chk = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_srvgroups_overrides` WHERE name = ? AND type = ? AND group_id = ? AND id != ?", array($override['name'], $override['type'], $gid, $id));
                if(!empty($chk)) {
                    $objResponse->addScript("ShowBox('Ошибка', 'Переопределение с таким именем уже существует \\\"" . htmlspecialchars(addslashes($override['name'])) . "\\\" для выбранного типа..', 'red', '', true);");
                    return $objResponse;
                }

                // Edit the override
                $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_srvgroups_overrides` SET name = ?, type = ?, access = ? WHERE id = ?;", array($override['name'], $override['type'], $override['access'], $id));
            }
        }
        
        // Add a new override
        if(!empty($newOverride)) {
            if(($newOverride['type'] == "command" || $newOverride['type'] == "group") && !empty($newOverride['name'])) {
                // Check for duplicates
                $chk = $GLOBALS['db']->GetAll("SELECT * FROM `" . DB_PREFIX . "_srvgroups_overrides` WHERE name = ? AND type = ? AND group_id = ?", array($newOverride['name'], $newOverride['type'], $gid));

                if(!empty($chk)) {
                    $objResponse->addScript("ShowBox('Ошибка', 'Переопределение с таким именем уже существует \\\"" . htmlspecialchars(addslashes($newOverride['name'])) . "\\\" для выбранного типа..', 'red', '', true);");
                    return $objResponse;
                }

                // Insert the new override
                $GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_srvgroups_overrides` (group_id, type, name, access) VALUES (?, ?, ?, ?);", array($gid, $newOverride['type'], $newOverride['name'], $newOverride['access']));
            }
        }

        if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1) {
            // rehash the settings out of the database on all servers
            $serveraccessq = $GLOBALS['db']->GetAll("SELECT sid FROM ".DB_PREFIX."_servers WHERE enabled = 1;");
            $allservers = array();
            foreach($serveraccessq as $access) {
                if(!in_array($access['sid'], $allservers)) {
                    $allservers[] = $access['sid'];
                }
            }
            $objResponse->addScript("ShowRehashBox('".implode(",", $allservers)."', 'Группа обновлена', 'Группа успешно обновлена', 'green', 'index.php?p=admin&c=groups');TabToReload();");
        } else
            $objResponse->addScript("ShowBox('Группа обновлена', 'Группа успешно обновлена', 'green', 'index.php?p=admin&c=groups');TabToReload();");
        $log = new CSystemLog("m", "Группа обновлена", "Группа ($name) была обновлена");
        return $objResponse;
    }

    $objResponse->addScript("ShowBox('Группа обновлена', 'Группа успешно обновлена', 'green', 'index.php?p=admin&c=groups');TabToReload();");
    $log = new CSystemLog("m", "Группа обновлена", "Группа ($name) обновлена");

    return $objResponse;
}

function RemoveGroup($gid, $type) {
    global $userbank, $username;
    $objResponse = new xajaxResponse();

    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_DELETE_GROUPS)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " попытался удалить группу, не имея на это прав.");
        return $objResponse;
    }

    $gid = (int)$gid;

    if($type == "web") {
        $query2 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET gid = -1 WHERE gid = $gid");
        $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_groups` WHERE gid = $gid");
    } else if($type == "server") {
        $query2 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_servers_groups` WHERE group_id = $gid");
        $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_groups` WHERE gid = $gid");
    } else {
        $query2 = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET srv_group = NULL WHERE srv_group = (SELECT name FROM `" . DB_PREFIX . "_srvgroups` WHERE id = $gid)");
        $query1 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_srvgroups` WHERE id = $gid");
        $query0 = $GLOBALS['db']->Execute("DELETE FROM `" . DB_PREFIX . "_srvgroups_overrides` WHERE group_id = $gid");
    }

    if(isset($GLOBALS['config']['config.enableadminrehashing']) && $GLOBALS['config']['config.enableadminrehashing'] == 1) {
        // rehash the settings out of the database on all servers
        $serveraccessq = $GLOBALS['db']->GetAll("SELECT sid FROM ".DB_PREFIX."_servers WHERE enabled = 1;");
        $allservers = array();
        foreach($serveraccessq as $access) {
            if(!in_array($access['sid'], $allservers)) {
                $allservers[] = $access['sid'];
            }
        }
        $rehashing = true;
    }

    $objResponse->addScript("SlideUp('gid_$gid');");
    if($query1) {
        if(isset($rehashing))
            $objResponse->addScript("ShowRehashBox('".implode(",", $allservers)."', 'Группа удалена', 'Выбранная группа была успешно удалена из базы данных', 'green', 'index.php?p=admin&c=groups', true);");
        else
            $objResponse->addScript("ShowBox('Группа удалена', 'Выбранная группа была успешно удалена из базы данных', 'green', 'index.php?p=admin&c=groups', true);");
        $log = new CSystemLog("m", "Группа удалена", "Группа (" . $gid . ") удалена");
    } else
        $objResponse->addScript("ShowBox('Ошибка', 'Не получилось удалить группу из базы данных. Смотрите системный лог для дополнительной информации', 'red', 'index.php?p=admin&c=groups', true);");

    return $objResponse;
}

function AddServerGroupName() {
    global $userbank, $username;
    $objResponse = new xajaxResponse();

    if(!$userbank->HasAccess(ADMIN_OWNER|ADMIN_EDIT_GROUPS)) {
        $objResponse->redirect("index.php?p=login&m=no_access", 0);
        $log = new CSystemLog("w", "Ошибка доступа", $username . " пытался изменить имя группы, не имея на это прав.");
        return $objResponse;
    }

    $inject = '<td valign="top"><div class="rowdesc">' . HelpIcon("Имя группы серверов", "Введите имя новой группы.") . 'Имя группы </div></td>';
    $inject .= '<td><div align="left">
        <input type="text" style="border: 1px solid #000000; width: 105px; font-size: 14px; background-color: rgb(215, 215, 215);width: 200px;" id="sgroup" name="sgroup" />
      </div>
        <div id="group_name.msg" style="color:#CC0000;width:195px;display:none;"></div></td>';

    $objResponse->addAssign("nsgroup", "innerHTML", $inject);
    $objResponse->addAssign("group.msg", "innerHTML", "");
    return $objResponse;
}

function UpdateGroupPermissions($gid) {
    global $userbank;
    $objResponse = new xajaxResponse();

    $gid = (int)$gid;
    if($gid == 1) {
        $permissions = @file_get_contents(TEMPLATES_PATH . "/groups.web.perm.php");
        $permissions = str_replace("{title}", "Разрешения доступа к сайту", $permissions);
    } elseif($gid == 2) {
        $permissions = @file_get_contents(TEMPLATES_PATH . "/groups.server.perm.php");
        $permissions = str_replace("{title}", "Разрешения доступа к серверу", $permissions);
    } elseif($gid == 3)
        $permissions = "";

    $objResponse->addAssign("perms", "innerHTML", $permissions);
    if(!$userbank->HasAccess(ADMIN_OWNER))
        $objResponse->addScript('if($("wrootcheckbox")) { 
                                    $("wrootcheckbox").setStyle("display", "none");
                                }
                                if($("srootcheckbox")) { 
                                    $("srootcheckbox").setStyle("display", "none");
                                }');
    $objResponse->addScript("$('type.msg').setHTML('');");
    $objResponse->addScript("$('type.msg').setStyle('display', 'none');");
    return $objResponse;
}
