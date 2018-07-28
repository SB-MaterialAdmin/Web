<?php
class Loader {
  private static $_dirs = [];

  public static function Register($dir = NULL) {
    if ($dir === NULL)
      $dir = dirname(__FILE__);

    self::$_dirs[] = $dir;
  }

  public static function Init() {
    spl_autoload_register(function($className) {
      self::doLoad($className);
    });
  }

  private static function doLoad($className) {
    $className = str_replace('\\', '/', $className);

    foreach (self::$_dirs as $dir) {
      $path    = "$dir/$className.php";

      if (file_exists($path)) {
        require_once($path);
        return;
      }
    }
  });
}