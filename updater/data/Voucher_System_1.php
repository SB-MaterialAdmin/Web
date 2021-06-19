<?php
	$ret = $GLOBALS['db']->Execute("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."_vay4er` ( `aid` int(6) NOT NULL auto_increment, `activ` int(6) NOT NULL, `value` bigint(20) NOT NULL, `days` int(11) NOT NULL, `group_web` varchar(128) NOT NULL, `group_srv` varchar(128) NOT NULL, PRIMARY KEY  (`aid`)) ENGINE='MyISAM' DEFAULT CHARSET=utf8;");
	if(!$ret)
		return false;
	
	
	$temp = $GLOBALS['db']->GetAll("SELECT * FROM `".DB_PREFIX."_settings` WHERE setting = 'page.vay4er'");
	if(count($temp) == 0)
	{
		$ret = $GLOBALS['db']->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('page.vay4er', '0')");
		if(!$ret)
			return false;
	}
	return true;
?>
