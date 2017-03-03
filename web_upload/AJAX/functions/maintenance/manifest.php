<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

return array(
    /* System Maintenance */
    'Maintenance'               => __DIR__ . '/System.php',
    'CheckVersion'              => __DIR__ . '/System.php',
    
    /* Profile Management */
    'ChangeEmail'               => __DIR__ . '/ProfileManagement.php',
    'CheckPassword'             => __DIR__ . '/ProfileManagement.php',
    'ChangePassword'            => __DIR__ . '/ProfileManagement.php',
    'CheckSrvPassword'          => __DIR__ . '/ProfileManagement.php',
    'ChangeSrvPassword'         => __DIR__ . '/ProfileManagement.php',
    'ChangeAdminsInfos'         => __DIR__ . '/ProfileManagement.php'
);
