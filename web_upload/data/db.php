<?php
if (!defined('IN_SB'))
  exit();

/**
 * This file contains all database configurations for
 * using in SourceBans in new DB Framework.
 */
\DatabaseManager::CreateConfig('SourceBans', [
  'dsn'     => 'mysql:dbname=sourcebans;host=127.0.0.1;charset=UTF8',
  'user'    => 'sourcebans',
  'pass'    => 'password',
  'prefix'  => 'sb_',
  'options' => []
]);