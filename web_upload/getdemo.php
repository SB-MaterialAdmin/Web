<?php
require_once('init.php');

if (\App::options()->demoEnabled == false)
{
  http_response_code(400);
  header('X-DenyReason: Disabled feature');
  echo('This action cannot be completed: Demos disabled');

  return;
}

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

$path = SB_DEMOS . '/' . $Demo['filename'];

if ($type != 'U' && (!in_array($Demo['filename'], scandir(SB_DEMOS)) || !file_exists($path)))
  die('File not found.');

if ($type != 'U'){
  $demo['filename'] = basename($Demo['filename']);

  Header('Content-type: application/force-download');
  Header('Content-Transfer-Encoding: Binary');
  Header('Content-disposition: attachment; filename="' . $Demo['origname'] . '"');
  Header('Content-Length: ' . filesize($path));
  readfile($path);
} else
  Header('Location: '.$Demo['origname'], true, 301);
