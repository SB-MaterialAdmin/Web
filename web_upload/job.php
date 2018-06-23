<?php
include('init.php');
Header('Content-Type: application/json; charset=UTF8');

ignore_user_abort(true);
set_time_limit(0);

$CronToken = filterInput(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
if ($CronToken != $_SESSION['CronToken']) {
  echo(json_encode([
    'result'  => false,
    'code'    => 1,
    'reason'  => 'Cron Token validation failed.'
  ]));

  exit();
}

$CronToken = md5(time() . $_SESSION['CronToken']);
$_SESSION['CronToken'] = $CronToken;

\SessionManager::closeWrite();
\CronManager::run(1, 10);

$More = \CronManager::requiredToRun();

$Response = [
  'result'  => true,
  'more'    => $More
];

if ($More)
  $Response['token']  = $CronToken;

if (connection_status() == CONNECTION_NORMAL)
  echo(json_encode($Response));