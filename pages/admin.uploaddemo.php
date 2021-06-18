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


include_once("../init.php");
include_once("../includes/system-functions.php");
global $theme, $userbank;

if (!$userbank->HasAccess(ADMIN_OWNER|ADMIN_ADD_BAN|ADMIN_EDIT_OWN_BANS|ADMIN_EDIT_GROUP_BANS|ADMIN_EDIT_ALL_BANS))
{
    $log = new CSystemLog("w", "Попытка взлома", $userbank->GetProperty('user') . " пытался загрузить демо, не имея на это прав.");
	echo 'У вас нет доступа к этому!';
	die();
}

$message = "";

if(isset($_POST['upload']))
{
	if (CheckExt($_FILES['demo_file']['name'], ["dem", "zip", "rar", "7z", "bz2", "gz"])) {
		$filename = md5(time().rand(0, 1000));
		move_uploaded_file($_FILES['demo_file']['tmp_name'],SB_DEMOS."/".$filename);
		$message =  "<script>window.opener.demo('" . $filename . "','" . $_FILES['demo_file']['name'] . "');self.close()</script>";
        $log = new CSystemLog("m", "Демо загружено", "Новое демо было успешно загружено: ".htmlspecialchars($_FILES['demo_file']['name']));
	} else
		$message =  "<b> Файл должен быть формата dem, zip, rar, 7z, bz2 или gz.</b><br><br>";
}

$theme->assign("title", "Загрузить демо");
$theme->assign("message", $message);
$theme->assign("input_name", "demo_file");
$theme->assign("form_name", "demup");
$theme->assign("formats", "DEM, ZIP, RAR, 7Z, BZ2 или GZ");

$theme->display('page_uploadfile.tpl');
?>

