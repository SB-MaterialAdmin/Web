<?php
	$ret = $GLOBALS['db']->Execute("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."_avatars` (`authid` varchar(35) NOT NULL, `url` varchar(150) NOT NULL, `expires` int(11) NOT NULL, UNIQUE KEY `authid` (`authid`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
	if(!$ret)
		return false;

	return true;
?>