<?php
$warnings = $GLOBALS['db']->GetAll("SELECT `id`, `reason`, `expires`, `user` AS `from` FROM `" . DB_PREFIX . "_warns` INNER JOIN `" . DB_PREFIX . "_admins` ON `" . DB_PREFIX . "_warns`.`afrom` = `" . DB_PREFIX . "_admins`.`aid` WHERE `arecipient` = " . (int) $_GET['id'] . ";");
foreach ($warnings as &$warning) {
	$expires = (int) $warning['expires'];
	if ($expires > time()) {
		$warning['expires'] = "Через&nbsp;".round((($expires - time()) / 86400),0) . "&nbsp;дней&nbsp;(".date('До d.m.Y в <b>H:i</b>', $expires).")";
		$warning['expired'] = false;
	} else if ($warning['expires'] == -1) {
		$warning['expires'] = "Снят";
		$warning['expired'] = true;
	} else {
		$warning['expires'] = "Истёк";
		$warning['expired'] = true;
	}
}
$theme->assign('Warnings', $warnings);
$theme->assign('count', count($warnings));

$theme->assign('myId', $userbank->GetAid());
$theme->assign('thisId', $_GET['id']);

$theme->display('page_admin_admins_warnings.tpl');
