<?php
\MaterialAdmin\DataStorage::ADOdb()->Execute("INSERT INTO `" . DB_PREFIX . "_settings` (`setting`, `value`) VALUES ('theme.splashscreen', '1'), ('theme.home.stats', '1');");
