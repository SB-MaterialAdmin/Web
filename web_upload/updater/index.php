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

 define('IS_UPDATE', true);
 include "../init.php";
 $theme->clear_compiled_tpl();

 define('IS_AJAX',   isset($_GET['updater_ajax_call']));

 include INCLUDES_PATH . "/CUpdate.php";
 $updater = new CUpdater();
 
 $setup = "Проверка текущей версии SourceBans...<b> " . $updater->getCurrentRevision() . "</b>";
 if(!$updater->needsUpdate())
 {
	if (IS_AJAX)
		die(json_encode(['result' => false, 'reason' => "Система в обновлениях не нуждается."]));
	$setup .= "<br /><br />Обновления не нужны. Удалите папку <b>updater</b>!";
	$theme->assign('setup', $setup);
	$theme->assign('progress', "");
	$theme->display('updater.tpl');
	die();
 }
 $setup .= "<br />Обновление до версии: <b>" . $updater->getLatestPackageVersion() . "</b>";
 
 $progress = $updater->doUpdates();
 
 if (IS_AJAX)
	die(json_encode(['result' => true]));
 $theme->assign('setup', $setup);
 $theme->assign('progress', $progress);
 $theme->display('updater.tpl');
?>
