<?php
	$_admins = $GLOBALS['db']->Execute("ALTER TABLE `" . DB_PREFIX . "_admins` ADD 
			`expired` 	int(11) NULL,
			`skype`		varchar(128) NULL,
			`comment`	varchar(128) NULL,
			`vk`		varchar(128) NULL");

	if(!$_admins)
		return false;
		
	$_settings = $GLOBALS['db']->Execute("INSERT INTO `" . DB_PREFIX . "_settings` (`setting`, `value`) VALUES
			('dash.intro.text', '<center><p>SB Material Design упешно установлена!</p><p>Добро пожаловать :)</p></center>'),
			('template.logo', 'images/logos/sb-dark.png'),
			('template.title', 'SourceBans :: MATERIAL'),
			('config.enableprotest', '0'),
			('config.enablesubmit', '0'),
			('config.dateformat', 'd.m.Y в H:i'),
			('config.dateformat_ver2', 'd.m.Y'),
			('config.theme', 'new_box'),
			('config.text_home', 'Добро пожаловать на сайт игрового портала: AZAZA'),
			('config.text_mon', 'У вас есть возможность управлять игроками через мониторинг(test)'),
			('config.text_acc', 'Успешный вход в систему!'),
			('config.text_acc2', 'Подробно ознакомьтесь с данными на это странице!'),
			('template.global', '0'),
			('dash.info_block',	'1'),
			('dash.info_block_text',	'<h1>Медовое сообщество</h1><br><center><img src=\"themes/new_box/img/pchelka.jpg\" class=\"img-responsive\" alt=\"\"></center>Дополнительная инфа о нас. ТЕСТ. текстик: Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.<br />Дополнительная инфа о нас. ТЕСТ. текстик: Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.<br />Дополнительная инфа о нас. ТЕСТ. текстик: Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.<br /><b>Все указывается в настройках!</b>'),
			('dash.info_vk',	'http://vk.com/'),
			('dash.info_steam',	'http://steam.com/'),
			('dash.info_yout',	'http://youtube.com'),
			('dash.info_face',	'http://facebock.com/'),
			('dash.info_block_text_t',	'С уважением, главная администрация.'),
			('page.adminlist',	'0'),
			('page.xleb',	'1'),
			('theme.style', 'lightblue'),
			('theme.style.color', '');");
	
	if(!$_settings)
		return false;

	$ret = $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_admins` SET `expired` = '0' WHERE `aid` = '1'");
	if(!$ret)
		return false;
	
	return true;
?>
