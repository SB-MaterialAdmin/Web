<?php
	$ret = $GLOBALS['db']->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('theme.bg', '');");
	if(!$ret)
		return false;
	
	$ret = $GLOBALS['db']->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('theme.bg.rep', '');");
	if(!$ret)
		return false;
	
	$ret = $GLOBALS['db']->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('theme.bg.att', '');");
	if(!$ret)
		return false;
	
	$ret = $GLOBALS['db']->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('theme.bg.pos', '');");
	if(!$ret)
		return false;
	

	return true;
?>