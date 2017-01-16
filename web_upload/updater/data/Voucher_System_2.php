<?php
	$ret = $GLOBALS['db']->Execute("DELETE FROM `".DB_PREFIX."_vay4er`");
	if(!$ret)
		return false;
	
	$ret = $GLOBALS['db']->Execute("ALTER TABLE `".DB_PREFIX."_vay4er` ADD `servers` varchar(128) NOT NULL;");
	if(!$ret)
		return false;

	return true;
?>