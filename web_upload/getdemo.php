<?php
require_once('init.php');

$id   = filterInput(INPUT_GET, 'id',    FILTER_SANITIZE_NUMBER_INT);
$type = filterInput(INPUT_GET, 'type',  FILTER_SANITIZE_STRING);
if(is_null($id) || is_null($type))
    die('No id or type parameter.');

if(strcasecmp($type, 'U') != 0 && strcasecmp($type, 'B') != 0 && strcasecmp($type, 'S') != 0)
    die('Bad type');

$DB = \DatabaseManager::GetConnection();
$DB->Prepare('SELECT `filename`, `origname` FROM `{{prefix}}demos` WHERE `demtype` = :type AND `demid` = :id;');
$DB->BindData('type', $type);
$DB->BindData('id',   $id);

$Result = $DB->Finish();
$Demo = $Result->Single();
$Result->EndData();
if (!$Demo)
  die('Demo not found.');

$path = SB_DEMOS . '/' . $demo['filename'];

if ($type != 'U' && (!in_array($demo['filename'], scandir(SB_DEMOS)) || !file_exists($path)))
  die('File not found.');

if ($type != 'U'){
  $demo['filename'] = basename($demo['filename']);

  Header('Content-type: application/force-download');
  Header('Content-Transfer-Encoding: Binary');
  Header('Content-disposition: attachment; filename="' . $demo['origname'] . '"');
  Header('Content-Length: ' . filesize($path));
  readfile($path);
} else
  Header('Location: '.$Demo['origname'], true, 301);