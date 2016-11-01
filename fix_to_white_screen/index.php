<?php
	define('IN_SB', true);
	$ROOT = dirname(__FILE__);
	$ROOT = str_replace(array("/fix_to_white_screen/", "/fix_to_white_screen"), "", $ROOT);
	if(!file_exists($ROOT."/updater"))
	{
		echo "Папка /updater не найдена в корне SourceBans. Пожалуйста скачайте и залейте ТОЛЬКО ЭТУ папку из последнего обновления рефорка.";
		exit();
	}elseif(!file_exists($ROOT.'/config.php')){
		echo "Не могу найти файла /config.php с заполненными данными в корне SourceBans.";
		exit();
	}else{
		include_once($ROOT."/config.php");
	}

	$mysql = mysql_connect(DB_HOST,DB_USER,DB_PASS);
	mysql_select_db(DB_NAME);
	
	if($mysql){
		echo "<html>";
		
		$qs  = ["DROP TABLE IF EXISTS `".DB_PREFIX."_settings`;",
				"ALTER IGNORE TABLE `".DB_PREFIX."_admins` DROP COLUMN `expired`;",
				"ALTER IGNORE TABLE `".DB_PREFIX."_admins` DROP COLUMN `skype`;",
				"ALTER IGNORE TABLE `".DB_PREFIX."_admins` DROP COLUMN `comment`;",
				"ALTER IGNORE TABLE `".DB_PREFIX."_admins` DROP COLUMN `vk`;",
				"ALTER IGNORE TABLE `".DB_PREFIX."_admins` DROP COLUMN `support`;"];
		foreach ($qs as &$query) {
			if (!mysql_query($query)){
				echo "Была ошибка при выполнении запроса: ".$query.". Возможно, таких данных нету в БД - продолжаю. <b>->В этом нет ничего страшного!<-</b><br />";
			}
		}
		echo "Удаления старых таблиц - успешно<br />";
		
		$qs  = ["CREATE TABLE IF NOT EXISTS `".DB_PREFIX."_settings` (
				  `setting` varchar(128) NOT NULL,
				  `value` text NOT NULL,
				  UNIQUE KEY `setting` (`setting`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
				"INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES
					('dash.intro.text', '<center><p>SourceBans упешно установлена!</p><p>Добро пожаловать :)</p></center>'),
					('dash.lognopopup', '0'),
					('banlist.bansperpage', '20'),
					('banlist.hideadminname', '0'),
					('banlist.nocountryfetch', '0'),
					('banlist.hideplayerips', '0'),
					('bans.customreasons', ''),
					('config.password.minlength', '3'),
					('config.debug', '0 '),
					('template.logo', 'images/logos/sb-dark.png'),
					('template.title', 'SourceBans :: MATERIAL'),
					('config.enableprotest', '0'),
					('config.enablesubmit', '0'),
					('config.exportpublic', '0'),
					('config.enablekickit', '1'),
					('config.dateformat', 'd.m.Y в H:i'),
					('config.theme', 'new_box'),
					('config.defaultpage', '0'),
					('config.timezone', '0'),
					('config.summertime', '0'),
					('config.enablegroupbanning', '0'),
					('config.enablefriendsbanning', '0'),
					('config.enableadminrehashing', '1'),
					('protest.emailonlyinvolved', '0'),
					('config.version', '356');"];
		foreach ($qs as &$query) {
			if (!mysql_query($query)){
				echo "Ошибка в запросе ".$query;
				return false;
				}
		}
		echo "Запрос на добавление настроек и не только - успешно.<br />";
	
		echo "<script>setTimeout(\"window.location.replace('../updater');\", 5000);</script>";
		echo "Выполняется редерикт на обновление SourceBans<br />";
		echo "</html>";
	}else{
		echo "Нету связи с Mysql сервером. Возможно, данные в config.php указаны не верно.";
	}
?>
