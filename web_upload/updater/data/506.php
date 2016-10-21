<?php

	$ret = $GLOBALS['db']->Execute("ALTER TABLE `".DB_PREFIX."_admins` ADD `support` int(6) NULL DEFAULT '0';");
	if(!$ret)
		return false;
		
	$ret = $GLOBALS['db']->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('config.home.comms', '1');");
	if(!$ret){
		$ret12 = $GLOBALS['db']->Execute("UPDATE `".DB_PREFIX."_settings` SET `setting` = 'config.home.comms', `value` = '1' WHERE `setting` = 'config.home.comms' AND `setting` = 'config.home.comms' COLLATE utf8mb4_bin;");
		if(!$ret12){
			return false;
		}
	}

	return true;
?>