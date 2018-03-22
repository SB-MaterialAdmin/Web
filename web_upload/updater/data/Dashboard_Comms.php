<?php
	$ret = $GLOBALS['db']->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('config.home.comms', '1');");
	if(!$ret){
		return false;
	}

	return true;
?>
