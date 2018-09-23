<?php
use Data\TimeZone as DataSet;

class TimeZone {
  private static $data;
  private static $TZ;
  private static $format;

  public static function boot() {
    self::$data = DataSet::getTimeZones();
    self::SetTimeZone('Europe/London');
  }

  public static function getCurrentInternalTZ() {
    return self::$TZ;
  }

  public static function getDataSet() {
    return self::$data;
  }

  public static function setTimeZone($TZ) {
    date_default_timezone_set($TZ);
    self::$TZ = $TZ;
  }

  public static function setFormat($format) {
    self::$format = $format;
  }

  public static function FormatTime($ts) {
    return date(self::$format, $ts);
  }
}