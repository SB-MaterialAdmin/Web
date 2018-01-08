<?php
	$retr = \MaterialAdmin\DataStorage::ADOdb()->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('config.changeadmininfos', '1');");
	if(!$retr)
		return false;
 
	return true;
?>