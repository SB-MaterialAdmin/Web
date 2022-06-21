<?php
class Asserts {
  public static function requireLogin() {
    if (\UserManager::getMyID() == -1)
      self::ReRoute('login');
  }

  public static function isNotLogged($page = 'home') {
    if (\UserManager::getMyID() != -1)
      self::ReRoute($page);
  }

  /**
   * UTILs
   */
  private static function ReRoute($route, $group = NULL, $option = NULL) {
    $route = "?p={$route}";
    if ($group !== NULL)
      $route .= "&c={$group}";
    if ($option !== NULL)
      $route .= "&o={$option}";

    FatalRefresh("index.php{$route}");
  }
}