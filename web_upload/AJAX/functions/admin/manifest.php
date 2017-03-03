<?php
if (!defined('IN_SB'))
    die("DIE, HACKER-MOTHERFUCKER, DIE!");

return array(
    /* MODs */
    'AddMod'                    => __DIR__ . '/ModFunctions.php',
    'RemoveMod'                 => __DIR__ . '/ModFunctions.php',

    /* Groups */
    'AddGroup'                  => __DIR__ . '/GroupsFunctions.php',
    'EditGroup'                 => __DIR__ . '/GroupsFunctions.php',
    'RemoveGroup'               => __DIR__ . '/GroupsFunctions.php',
    'AddServerGroupName'        => __DIR__ . '/GroupsFunctions.php',
    'UpdateGroupPermissions'    => __DIR__ . '/GroupsFunctions.php',
    
    /* Admins */
    'AddAdmin'                  => __DIR__ . '/AdminFunctions.php',
    'AddSupport'                => __DIR__ . '/AdminFunctions.php',
    'AddWarning'                => __DIR__ . '/WarnsFunctions.php',
    'RemoveAdmin'               => __DIR__ . '/AdminFunctions.php',
    'RehashAdmins'              => __DIR__ . '/AdminFunctions.php',
    'RemoveWarning'             => __DIR__ . '/WarnsFunctions.php',
    'EditAdminPerms'            => __DIR__ . '/AdminFunctions.php',
    'removeExpiredAdmins'       => __DIR__ . '/AdminFunctions.php',
    'UpdateAdminPermissions'    => __DIR__ . '/AdminFunctions.php',
    
    /* Servers */
    'SendRcon'                  => __DIR__ . '/ServerFunctions.php',
    'AddServer'                 => __DIR__ . '/ServerFunctions.php',
    'RemoveServer'              => __DIR__ . '/ServerFunctions.php',
    'SetupEditServer'           => __DIR__ . '/ServerFunctions.php',
    
    /* Player actions */
    'AddBan'                    => __DIR__ . '/PlyActionsFunctions.php', // +
    'SendMail'                  => __DIR__ . '/PlyActionsFunctions.php', // +
    'SetupBan'                  => __DIR__ . '/PlyActionsFunctions.php', // +
    'GroupBan'                  => __DIR__ . '/PlyActionsFunctions.php', // +
    'AddBlock'                  => __DIR__ . '/PlyActionsFunctions.php', // +
    'GetGroups'                 => __DIR__ . '/PlyActionsFunctions.php', // +
    'AddComment'                => __DIR__ . '/PlyActionsFunctions.php', // +
    'BanFriends'                => __DIR__ . '/PlyActionsFunctions.php', // +
    'KickPlayer'                => __DIR__ . '/PlyActionsFunctions.php', 
    'SendMessage'               => __DIR__ . '/PlyActionsFunctions.php', // +
    'EditComment'               => __DIR__ . '/PlyActionsFunctions.php', // +
    'PrepareReban'              => __DIR__ . '/PlyActionsFunctions.php', // +
    'RemoveComment'             => __DIR__ . '/PlyActionsFunctions.php', // +
    'PrepareReblock'            => __DIR__ . '/PlyActionsFunctions.php', // +
    'PastePlayerData'           => __DIR__ . '/PlyActionsFunctions.php', // +
    'RemoveSubmission'          => __DIR__ . '/PlyActionsFunctions.php', // +
    'RemoveProtest'             => __DIR__ . '/PlyActionsFunctions.php', // +
    'BanMemberOfGroup'          => __DIR__ . '/PlyActionsFunctions.php', // +
    'PrepareBlockFromBan'       => __DIR__ . '/PlyActionsFunctions.php', // +
    'ViewCommunityProfile'      => __DIR__ . '/PlyActionsFunctions.php'  // +
);
