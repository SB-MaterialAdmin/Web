<?php
class SessionManager {
  public static function startSession($name, $expires = 86400, $path = '/', $domain = null) {
    session_name($name . '_SB');

    $domain = isset($domain) ? $domain : $_SERVER['SERVER_NAME'];
    $secure = ($_SERVER['SERVER_PORT'] === 443) ? true : false;

    session_set_cookie_params($expires, $path, $domain, $secure, true);
    session_start();

    if (self::ValidateSession()) {
      if (!self::PreventHijacking()) {
        $_SESSION = [];
        self::regenerateSession();

        $_SESSION = [
          'user_agent'  => hash('sha256', $_SERVER['HTTP_USER_AGENT']),
          'expires'     => time() + $expires
        ];
      } else if ((rand(1, 100) <= 10) && !isset($_POST['xajax'])) {
        self::regenerateSession();
      }
    }
  }

  public static function checkSession() {
    if (!isset($_SESSION['user_agent']))
      return false;

    if (!self::validateSession() || !self::preventHijacking()) {
      session_destroy();
      session_start();

      return false;
    }

    return true;
  }

  public static function closeWrite() {
    @session_write_close();
  }

  protected static function preventHijacking() {
    if (!isset($_SESSION['user_agent']))
      return false;

    if ($_SESSION['user_agent'] !== hash('sha256', $_SERVER['HTTP_USER_AGENT']))
      return false;

    return true;
  }

  protected static function regenerateSession() {
    $_SESSION['expires'] = time() + 10;

    session_regenerate_id(false);
    $newSession = session_id();

    self::closeWrite();
    session_id($newSession);
    session_start();
    unset($_SESSION['expires']);
  }

  protected static function validateSession() {
    return (
      !isset($_SESSION['expires']) ||
      $_SESSION['expires'] >= time()
    );
  }

  /**
   * @section CSRF
   */
  public static function initCsrf() {
    if (isset($_SESSION['csrf']))
      return;

    $_SESSION['csrf'] = md5($_SESSION['user_agent']);
    $_SESSION['csrf_valid'] = time() + 45;
  }

  public static function getCsrfToken() {
    if (!isset($_SESSION['csrf']))
      self::initCsrf();
    return $_SESSION['csrf'];
  }

  public static function checkCsrf($where = INPUT_POST) {
    if (!isset($_SESSION['csrf']))
      return false;
    if ($_SESSION['csrf_valid'] <= time())
      return false;

    $valid = (self::getCsrfToken() == filter_input($where, '__sb_csrf', FILTER_SANITIZE_STRING));
    
    if ($valid)
      $_SESSION['csrf_valid'] = time() + 45;
    return $valud;
  }

  /**
   * @section Session Name
   */
  public static function getSessionName() {
    if (defined('SB_SESSION')) {
      $session = constant('SB_SESSION');
      if (!empty($session))
        return $session;
    }

    return substr(md5($_SERVER['SERVER_NAME']), 0, 8);
  }
}