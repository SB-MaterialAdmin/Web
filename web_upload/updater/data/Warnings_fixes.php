<?php
/* Фикс системы предупреждений */
$table_struct = $GLOBALS['db']->GetAll("DESCRIBE `". DB_PREFIX . "_warns`");
foreach ($table_struct as &$field) {
    if ($field['Field'] == "id") {
        if ($field['Key'] != "PRI") {
            $GLOBALS['db']->Execute("ALTER TABLE `" . DB_PREFIX . "_warns`
                                        ADD UNIQUE KEY `id` (`id`);");
        }
        if ($field['Extra'] != "auto_increment") {
            $GLOBALS['db']->Execute("ALTER TABLE `" . DB_PREFIX . "_warns`
                                        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
        }
    }
}

/* Для новой серверной части, чтобы ошибки не спамила. В 1.1.6 надо сделать... */
$GLOBALS['db']->Execute("ALTER TABLE `" . DB_PREFIX . "_srvgroups`
                            ADD `maxbantime` INT NOT NULL DEFAULT '-1' AFTER `groups_immune`,
                            ADD `maxmutetime` INT NOT NULL DEFAULT '-1' AFTER `maxbantime`;");

/* Обновление иконки МОДа TF2 */
if ($GLOBALS['db']->GetOne("SELECT `icon` FROM `" . DB_PREFIX . "_mods` WHERE `modfolder` = 'tf';") == "tf2.gif") {
    $GLOBALS['db']->Execute("UPDATE `" . DB_PREFIX . "_mods`
                                SET `icon` = 'tf2.png'
                                WHERE `modfolder` = 'tf';");
}

/* Удаление неиспользуемого контента в /images/games/ */
$data = scandir(SB_ICONS);
foreach ($data as &$obj) {
    if (!is_file(sprintf("%s/%s", SB_ICONS, $obj)))
        continue;

    if ((int) $GLOBALS['db']->GetOne(sprintf("SELECT COUNT(`icon`) FROM `%s_mods` WHERE `icon` = %s;", DB_PREFIX, $GLOBALS['db']->qstr($obj))) == 0) {
        unlink(sprintf("%s/%s", SB_ICONS, $obj));
    }
}
