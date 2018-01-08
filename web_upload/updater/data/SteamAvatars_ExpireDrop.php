<?php
\MaterialAdmin\DataStorage::ADOdb()->Execute("ALTER TABLE `" . DB_PREFIX . "_avatars` DROP `expires`;");
