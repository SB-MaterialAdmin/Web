<?php
include_once("init.php");
$exportpublic = (isset($GLOBALS['config']['config.exportpublic']) && $GLOBALS['config']['config.exportpublic'] == "1");
if(!$userbank->HasAccess(ADMIN_OWNER) && !$exportpublic) {
    echo "У Вас нет доступа к данной функции.";
    exit();
} else if(!isset($_GET['type'])) {
	echo "Используйте только линки в самой системе!";
    exit();
}

if($_GET['type'] == 'steam') {
	header('Content-Type: text/plain');
	header('Content-Disposition: attachment; filename="banned_user.cfg"');
	$bans = \MaterialAdmin\DataStorage::ADOdb()->Execute("SELECT authid FROM `".DB_PREFIX."_bans` WHERE length = '0' AND RemoveType IS NULL AND type = '0'");
	$num = $bans->RecordCount();
	for($x=0;$x<$num;$x++) {
		print("banid 0 ".$bans->fields['authid']."\r\n");
		$bans->MoveNext();
	}
} elseif($_GET['type'] == 'ip') {
	header('Content-Type: text/plain');
	header('Content-Disposition: attachment; filename="banned_ip.cfg"');
	$bans = \MaterialAdmin\DataStorage::ADOdb()->Execute("SELECT ip FROM `".DB_PREFIX."_bans` WHERE length = '0' AND RemoveType IS NULL AND type = '1'");
	$num = $bans->RecordCount();
	for($x=0;$x<$num;$x++) {
		print("addip 0 ".$bans->fields['ip']."\r\n");
		$bans->MoveNext();
	}
}