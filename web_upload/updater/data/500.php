<?php
	$temp = $GLOBALS['db']->GetAll("SELECT * FROM `".DB_PREFIX."_settings` WHERE setting = 'theme.style'");
	if(count($temp) == 0)
	{
		$ret = $GLOBALS['db']->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('theme.style', 'lightblue')");
		if(!$ret)
			return false;
	}
	
	$temp = $GLOBALS['db']->GetAll("SELECT * FROM `".DB_PREFIX."_settings` WHERE setting = 'theme.style.color'");
	if(count($temp) == 0)
	{
		$ret = $GLOBALS['db']->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('theme.style.color', '')");
		if(!$ret)
			return false;
	}
	
	$temp = $GLOBALS['db']->GetAll("SELECT * FROM `".DB_PREFIX."_settings` WHERE setting = 'dash.intro.title'");
	if(count($temp) == 1)
	{
		$ret = $GLOBALS['db']->Execute("DELETE FROM `sb_settings` WHERE `setting` = 'dash.intro.title'");
		if(!$ret)
			return false;
	}
	
	$temp = $GLOBALS['db']->GetAll("SELECT * FROM `".DB_PREFIX."_settings` WHERE setting = 'template.title'");
	if(count($temp) == 1)
	{
		$ret = $GLOBALS['db']->Execute("UPDATE `sb_settings` SET `setting` = 'template.title', `value` = 'SourceBans :: MATERIAL' WHERE `setting` = 'template.title'");
		if(!$ret)
			return false;
	}

	return true;
?>