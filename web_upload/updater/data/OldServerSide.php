<?php
\MaterialAdmin\DataStorage::ADOdb()->Execute("INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('feature.old_serverside', '1');");
