<?php
	$ret = \MaterialAdmin\DataStorage::ADOdb()->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('theme.bg.size', '');");
	if(!$ret){
		$ret1 = \MaterialAdmin\DataStorage::ADOdb()->Execute("UPDATE `".DB_PREFIX."_settings` SET `setting` = 'theme.bg.size', `value` = '' WHERE `setting` = 'theme.bg.size' AND `setting` = 'theme.bg.size' COLLATE utf8mb4_bin;");
		if(!$ret1){
			return false;
		}
	}
	return true;
?>