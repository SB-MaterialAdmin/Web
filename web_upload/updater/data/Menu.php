<?php
$insq_menu = "INSERT INTO `".DB_PREFIX."_menu` (`id`, `text`, `description`, `url`, `system`, `enabled`, `priority`) VALUES";
$qs = array("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."_menu` ( `id` int(11) NOT NULL AUTO_INCREMENT, `text` varchar(256) NOT NULL, `description` varchar(450) NOT NULL, `url` varchar(300) NOT NULL, `system` tinyint(1) NOT NULL, `enabled` tinyint(1) NOT NULL, `priority` int(11) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=8;",
       $insq_menu . " (1, '<i class=''zmdi zmdi-home zmdi-hc-fw''></i> Главная', 'Главная страница SourceBans. Список серверов, последних банов и блоков.', 'index.php?p=home', 1, 1, 1000);", 
       $insq_menu . " (2, '<i class=''zmdi zmdi-input-composite zmdi-hc-fw''></i> Серверы', 'Список всех серверов и их текущий статус.', 'index.php?p=servers', 1, 1, 999);", 
       $insq_menu . " (3, '<i class=''zmdi zmdi-lock-outline zmdi-hc-fw''></i> Список банов', 'Список всех когда-либо выданных банов.', 'index.php?p=banlist', 1, 1, 998);", 
       $insq_menu . " (4, '<i class=''zmdi zmdi-mic-off zmdi-hc-fw''></i> Список мутов/гагов', 'Список всех когда-либо выданных мутов и гагов.', 'index.php?p=commslist', 1, 1, 997);",
       $insq_menu . " (5, '<i class=''zmdi zmdi-plus-circle-o-duplicate zmdi-hc-fw''></i> Пожаловаться на игрока', 'Здесь вы можете оставить жалобу на игрока.', 'index.php?p=submit', 1, 0, 996);",
       $insq_menu . " (6, '<i class=''zmdi zmdi-comment-edit zmdi-hc-fw''></i> Апелляция бана', 'Вы можете подать апелляцию вашего бана, предоставив доказательства невиновности.', 'index.php?p=protest', 1, 0, 995);", 
       $insq_menu . " (7, '<i class=''zmdi zmdi-accounts zmdi-hc-fw''></i> Админлист', 'Список администраторов на доступных серверах.', 'index.php?p=adminlist', 1, 0, 994);");

foreach ($qs as $query)
    if (!$GLOBALS['db']->Execute($query)) return false;

return true;
?>
