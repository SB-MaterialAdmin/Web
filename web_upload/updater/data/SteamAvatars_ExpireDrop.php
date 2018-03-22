<?php
$GLOBALS['db']->Execute("ALTER TABLE `" . DB_PREFIX . "_avatars` DROP `expires`;");
