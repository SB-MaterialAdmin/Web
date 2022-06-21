<?php
class DatabaseManager {
  private static $_connections  = [];
  private static $_configs      = [];

  public static function CreateConfig($ConfigName, $Data) {
    self::$_configs[$ConfigName] = $Data;
  }

  public static function IsConnected($ConfigName = 'SourceBans') {
    return isset(self::$_connections[$ConfigName]);
  }

  public static function GetConnection($ConfigName = 'SourceBans') {
    if (!self::IsConnected($ConfigName))
      self::InitConnection($ConfigName);

    return self::$_connections[$ConfigName];
  }

  private static function InitConnection($ConfigName = 'SourceBans') {
    if (!isset(self::$_configs[$ConfigName]))
      throw new \LogicException("Database configuration `$ConfigName` not exists!");

    if (self::IsConnected($ConfigName))
      return;

    try {
      $cfg = self::$_configs[$ConfigName];
      self::$_connections[$ConfigName] = new \Database($cfg);
    } catch (\Exception $e) {
      \ExceptionHandler::handle($e);
    }
  }
}