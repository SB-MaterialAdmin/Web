<?php
class UserManager {
  private static $instance = NULL;
  private static $aid = -1;

  public static function init($aid, $hash = '') {
    if (self::$instance !== NULL)
      return;

    if (is_int($aid))
      self::$aid = $aid;

    self::$instance = new CUserManager(self::$aid, $hash);
    self::$aid = self::$instance->aid;

    if (self::$aid != -1) {
      self::updateEntity('lastvisit', time()); // update visiting time
    }
  }

  public static function getInstance() {
    if (self::$instance === NULL)
      self::init(-1);

    return self::$instance;
  }

  public static function login($username, $password, &$reason) {
    if (empty($password)) {
      $reason = 'Не указан пароль.';
      return false;
    }

    $reason = 'Неверный логин и/или пароль';

    $DB = \DatabaseManager::GetConnection();
    $DB->Prepare('SELECT `aid`, `password`, `expired` FROM `{{prefix}}admins` WHERE `user` = :username');
    $DB->BindData('username', $username);
    $Result = $DB->Finish();

    $Data = $Result->Single();
    $Result->EndData();

    if (!$Data) {
      return false;
    }

    if (empty($Data['password'])) {
      $reason = 'У пользователя не задан пароль. Обратитесь к администратору.';
      return false;
    }

    // try use new algo.
    if (password_verify($password, $Data['password']))
      return self::ContinueLogin($Data, $reason);

    // using old algo.
    if ($Data['password'] == sha1(sha1('SourceBans' . $password))) {
      // rehash user with new algo.
      $Data['password'] = password_hash($password, PASSWORD_DEFAULT);
      $DB->Prepare('UPDATE `{{prefix}}admins` SET `password` = :password WHERE `aid` = :id');
      $DB->BindMultipleData([
        'password'  => $Data['password'],
        'id'        => $Data['aid']
      ]);
      $DB->Finish();

      // and continue login logic.
      return self::ContinueLogin($Data, $reason);
    }
    return false;
  }

  public static function forceLoginBySteam($steamId, &$reason) {
    if (!is_object($steamId) || get_class($steamId) != 'CSteamId')
      throw new \LogicException('Invalid SteamID object passed.');

    $DB = \DatabaseManager::GetConnection();
    $DB->Prepare('SELECT `aid`, `password`, `expired` FROM `{{prefix}}admins` WHERE `authid` LIKE :auth');
    $DB->BindData('auth', '%' . str_replace('STEAM_0:', '', $steamId->v2));

    $Result = $DB->Finish();
    $UserData = $Result->Single();
    $Result->EndData();

    if (!$UserData) {
      $reason = 'Пользователя с Вашим SteamID не найдено.';
      return false;
    }

    return self::ContinueLogin($UserData, $reason);
  }

  public static function getMyID() {
    return self::$aid;
  }

  private static function ContinueLogin($UserData, &$reason) {
    if ($UserData['expired'] != 0 && $UserData['expired'] < time()) {
      $reason = 'Ваши привилегии истекли. Их необходимо продлить для дальнейшего использования.';
      return false;
    }

    $_SESSION['admin_id'] = intval($UserData['aid']);
    $_SESSION['admin_hash'] = $UserData['password'];
    \session_write_close();
    return true;
  }

  private static function updateEntity($field, $value) {
    $DB = \DatabaseManager::GetConnection();
    $DB->Prepare("UPDATE `{{prefix}}admins` SET $field = :value WHERE `aid` = :id");
    $DB->BindData('id', self::$aid);
    $DB->BindData('value', $value);
    $DB->Finish();
  }
}