<?php
class SBCronJob {
  private static function db() {
    return \DatabaseManager::GetConnection();
  }

  public static function PruneBans(&$data, &$finished) {
    $aid = \UserManager::getMyID();
    if ($aid == -1)
      $aid = 0;

    $DB = self::db();

    $DB->Query('UPDATE `{{prefix}}bans` SET `RemovedBy` = 0, `RemoveType` = "E", `RemovedOn` = UNIX_TIMESTAMP() WHERE `length` != 0 AND `ends` < UNIX_TIMESTAMP() AND `RemoveType` IS NULL');

    $DB->Prepare('UPDATE `{{prefix}}protests` SET `archiv` = 3, `archivedby` = :adminid WHERE `archiv` = 0 AND `bid` IN ((SELECT `bid` FROM `{{prefix}}bans` WHERE `RemoveType` = "E"))');
    $DB->BindData('adminid', $aid);
    $DB->Finish()->EndData();

    $DB->Prepare('UPDATE `{{prefix}}submissions` SET `archiv` = 3, `archivedby` = :adminid WHERE `archiv` = 0 AND (SteamId IN((SELECT `authid` FROM `{{prefix}}bans` WHERE `type` = 0 AND `RemoveType` IS NULL)) OR sip IN((SELECT `ip` FROM `{{prefix}}bans` WHERE `type` = 1 AND `RemoveType` IS NULL)))');
    $DB->BindData('adminid', $aid);
    $DB->Finish()->EndData();

    $DB->Query('DELETE FROM `{{prefix}}bans` WHERE `authid` NOT REGEXP "^STEAM_[0-9]:[0-9]:[0-9]"');

    $finished = true;
  }

  public static function PruneComms(&$data, &$finished) {
    self::db()->Query('UPDATE `{{prefix}}comms` SET `RemovedBy` = 0, `RemoveType` = "E", `RemovedOn` = UNIX_TIMESTAMP() WHERE `length` != 0 and `ends` < UNIX_TIMESTAMP() and `RemoveType` IS NULL');

    $finished = true;
  }

  public static function ServerCache(&$data, &$finished) {
    $finished = true;
  }

  public static function UpdateChecker(&$data, &$finished) {
    $DB = self::db();
    $DB->Prepare('UPDATE `{{prefix}}settings` SET `value` = :data WHERE `setting` = "sb.updater"');
    $DB->BindData('data', @file_get_contents("https://raw.githubusercontent.com/SB-MaterialAdmin/Web/" . MA_BRANCH . "/updates.json"));
    $DB->Finish();

    $finished = true;
  }

  public static function CollectStats(&$data, &$finished) {
    // We collect stats like PHP version, MySQL version.
    // Our server receives:
    // -> PHP version
    // -> MySQL version
    // -> SB address
    // -> SB version (database and generic)
    // -> Bans, comms, servers count
    $DB  = self::db();
    $ver = $DB->GetAttribute(\PDO::ATTR_SERVER_VERSION);
    $dbver  = $DB->Query('SELECT `value` FROM `{{prefix}}settings` WHERE `setting` = "config.version";')->Single();
    $counts = $DB->Query('SELECT (SELECT COUNT(*) FROM `{{prefix}}bans`) AS `bans`, (SELECT COUNT(*) FROM `{{prefix}}comms`) AS `comms`, (SELECT COUNT(*) FROM `{{prefix}}servers`) AS `servers`;')->Single();

    $body = [
      'php'     => phpversion(),
      'mysql'   => $ver,
      'counts'  => serialize($counts),
      'address' => SB_WP_URL,
      'version' => serialize([
        'files' => SB_VERSION,
        'db'    => $dbver['value']
      ])
    ];

    // Make HTTP query.
    \HTTP::request('https://kruzefag.ru', 'POST')->setData($body)
      ->run('sourcebans/stats.php');
    $finished = true;
  }

  public static function UpdateAvatarCache(&$data, &$finished) {
    // TODO: migrate avatar cache updater.
    $finished = true;
  }
}