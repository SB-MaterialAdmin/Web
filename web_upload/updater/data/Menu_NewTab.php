<?php
if (!\MaterialAdmin\DataStorage::ADOdb()->Execute("ALTER TABLE `".DB_PREFIX."_menu` ADD `newtab` INT(4) NOT NULL DEFAULT '0' AFTER `enabled`;"))
    return false;
return true;
?>
