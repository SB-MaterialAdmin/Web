<?php
$config = array();
$SMTPFilePath = INCLUDES_PATH . '/smtp-func.php';
if (file_exists($SMTPFilePath))
    require_once($SMTPFilePath);
else
    $config = array('smtp_username' => '', 'smtp_port' => '', 'smtp_host' => '', 'smtp_password' => '', 'smtp_charset' => '', 'smtp_from' => '', 'enabled' => 0);

$insq = "INSERT INTO `".DB_PREFIX."_settings` (`setting`, `value`) VALUES ('%s', %s);";
$qs = array(sprintf($insq, 'smtp.enabled', $GLOBALS['db']->qstr((isset($config['enabled'])?'0':'1'))),
       sprintf($insq, 'smtp.username', $GLOBALS['db']->qstr($config['smtp_username'])),
       sprintf($insq, 'smtp.port', $GLOBALS['db']->qstr($config['smtp_port'])),
       sprintf($insq, 'smtp.host', $GLOBALS['db']->qstr($config['smtp_host'])),
       sprintf($insq, 'smtp.password', $GLOBALS['db']->qstr($config['smtp_password'])),
       sprintf($insq, 'smtp.charset', $GLOBALS['db']->qstr($config['smtp_charset'])),
       sprintf($insq, 'smtp.from', $GLOBALS['db']->qstr($config['smtp_from'])));

foreach ($qs as $query)
    if (!$GLOBALS['db']->Execute($query)) return false;

if (!isset($config['enabled']))
    @unlink($SMTPFilePath);
return true;
?>
