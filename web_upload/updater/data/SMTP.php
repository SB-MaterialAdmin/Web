<?php
$config = array();
$SMTPFilePath = INCLUDES_PATH . '/smtp-func.php';
if (file_exists($SMTPFilePath))
    require_once($SMTPFilePath);
else
    $config = array('smtp_username' => '', 'smtp_port' => '', 'smtp_host' => '', 'smtp_password' => '', 'smtp_charset' => '', 'smtp_from' => '', 'enabled' => 0);

$insq = "INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('%s', %s);";
$qs = array(sprintf($insq, 'smtp.enabled', \MaterialAdmin\DataStorage::ADOdb()->qstr((isset($config['enabled'])?'0':'1'))),
       sprintf($insq, 'smtp.username', \MaterialAdmin\DataStorage::ADOdb()->qstr($config['smtp_username'])),
       sprintf($insq, 'smtp.port', \MaterialAdmin\DataStorage::ADOdb()->qstr($config['smtp_port'])),
       sprintf($insq, 'smtp.host', \MaterialAdmin\DataStorage::ADOdb()->qstr($config['smtp_host'])),
       sprintf($insq, 'smtp.password', \MaterialAdmin\DataStorage::ADOdb()->qstr($config['smtp_password'])),
       sprintf($insq, 'smtp.charset', \MaterialAdmin\DataStorage::ADOdb()->qstr($config['smtp_charset'])),
       sprintf($insq, 'smtp.from', \MaterialAdmin\DataStorage::ADOdb()->qstr($config['smtp_from'])));

foreach ($qs as $query)
    if (!\MaterialAdmin\DataStorage::ADOdb()->Execute($query)) return false;

if (!isset($config['enabled']))
    @unlink($SMTPFilePath);
return true;
?>
