<?php
if (!defined('IN_SB')) exit();

/**
 * This file contains all database configurations for
 * using in SourceBans in new DB Framework.
 */
\DatabaseManager::CreateConfig('SourceBans', [
  'dsn'     => '',
  'user'    => '',
  'pass'    => '',
  'prefix'  => '',
  'options' => [
    \PDO::ATTR_ERRMODE  => \PDO::ERRMODE_EXCEPTION
  ]
]);