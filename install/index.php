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

if (version_compare(PHP_VERSION, '7.3','<')) {
    header('Content-Type: text/plain; charset=UTF8');
    echo("Работа SourceBans невозможна: для работы требуется PHP версии 7.3 и выше.\n");
    echo('На данный момент установлена версия ' . PHP_VERSION);
    exit();
}

session_start();
include_once 'init.php';
include_once(INCLUDES_PATH . "/user-functions.php");
include_once(INCLUDES_PATH . "/system-functions.php");
include_once(INCLUDES_PATH . "/page-builder.php");








//Yarr!

?>