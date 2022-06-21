<?php
$GLOBALS['db']->Execute('ALTER TABLE
  `' . DB_PREFIX . '_servers`
MODIFY COLUMN
  `rcon` VARCHAR(256);');
// Source Engine supports RCON password with size "256".