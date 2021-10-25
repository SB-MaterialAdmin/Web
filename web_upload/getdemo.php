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
if(is_null($id))
    die('No id parameter.');

$DB = \DatabaseManager::GetConnection();
$DB->Prepare('SELECT `filename`, `origname`, `demtype` FROM `{{prefix}}demos` WHERE `demid` = :id;');
$DB->BindData('id',   $id);

$Result = $DB->Finish();
$Demo = $Result->Single();
$Result->EndData();
if (!$Demo)
  die('Demo not found.');

$path = SB_DEMOS . '/' . $Demo['filename'];
$type = $Demo['demtype'];

if(strcasecmp($type, 'U') != 0 && strcasecmp($type, 'B') != 0 && strcasecmp($type, 'S') != 0)
    die('Bad type');

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
