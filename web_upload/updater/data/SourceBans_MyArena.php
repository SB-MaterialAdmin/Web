<?php 
$find_su = false;
$find_oa = false;
$find_og = false;

$mods_struct = $GLOBALS['db']->GetAll("DESCRIBE `" . DB_PREFIX . "_mods");
$tables = $GLOBALS['db']->GetAll("SHOW TABLES;");

/* Check */
foreach ($tables as $table) { // TABLES
    if ($obj[0] == DB_PREFIX . "_overrides")
        $find_oa = true;
    else if ($obj[0] == DB_PREFIX . "_srvgroups_overrides")
        $find_og = true;
}

foreach ($mods_struct as $obj) { // MODS STRUCTURE
    if ($obj['Field'] == "steam_universe") {
        $find_su = true;
        break;
    }
}

/* Process requests */
if (!$find_su) {
    $GLOBALS['db']->Execute("ALTER TABLE `" . DB_PREFIX . "_mods` ADD `steam_universe` int(11)");
    $GLOBALS['db']->Execute("ALTER TABLE `" . DB_PREFIX . "_mods` `steam_universe` SET DEFAULT 0;");
}

if (!$find_oa) {
    $GLOBALS['db']->Execute("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "_overrides` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `type` enum('command','group') NOT NULL,
                                `name` varchar(32) NOT NULL,
                                `flags` varchar(30) NOT NULL,
                                PRIMARY KEY (`id`),
                                UNIQUE KEY `type` (`type`,`name`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
}

if (!$find_og) {
    $GLOBALS['db']->Execute("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "_srvgroups_overrides` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `group_id` smallint(5) unsigned NOT NULL,
                                `type` enum('command','group') NOT NULL,
                                `name` varchar(32) NOT NULL,
                                `access` enum('allow','deny') NOT NULL,
                                PRIMARY KEY (`id`),
                                UNIQUE KEY `group_id` (`group_id`,`type`,`name`)
                            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
}

$GLOBALS['db']->Execute("DROP TABLE IF EXISTS `" . DB_PREFIX . "_net_country`, `" . DB_PREFIX . "_net_country_ip`");
