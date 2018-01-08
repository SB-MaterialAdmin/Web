<?php
	$ret = \MaterialAdmin\DataStorage::ADOdb()->Execute("DELETE FROM `".DB_PREFIX."_vay4er`");
	if(!$ret)
		return false;
	
	$ret = \MaterialAdmin\DataStorage::ADOdb()->Execute("ALTER TABLE `".DB_PREFIX."_vay4er` ADD `servers` varchar(128) NOT NULL;");
	if(!$ret)
		return false;

	return true;
?>