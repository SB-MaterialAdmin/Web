<?php
class Autoloader {
  private static $Paths = [];
  private static $Initialized = FALSE;

  public static function RegisterPath($Path, $PSR = '') {
    self::$Paths[] = [$PSR, $Path];

    if (self::$Initialized === FALSE)
      self::Initialize();
  }

  public static function Initialize() {
    if (self::$Initialized)
      return;

    spl_autoload_register(function($ClassName) {
      self::TriggerLoad($ClassName);
    });
    self::$Initialized = true;
  }

  private static function TriggerLoad($ClassName) {
    foreach (self::$Paths as $Path) {
      $PSR = $Path[0];
      $DirPath = $Path[1];

      if (!empty($PSR)) {
        $Pos = strpos($ClassName, $PSR);
        if ($Pos === FALSE || $PSR != 0)
          continue;

        $ClassName = str_replace($PSR, '', $ClassName);
      }

      $ClassName = str_replace('\\', '/', $ClassName);

      $ClassPath = "{$DirPath}/{$ClassName}.php";
      if (file_exists($ClassPath)) {
        require($ClassPath);

        if (is_callable([$ClassName, 'boot']))
          call_user_func_array([$ClassName, 'boot'], []);
        break;
      }
    }
  }
}