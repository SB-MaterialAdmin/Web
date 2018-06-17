<?PHP
require_once('init.php');
require_once(sprintf('%s/LightOpenID.php', INCLUDES_PATH));
require_once(sprintf('%s/SteamOpenID.php', INCLUDES_PATH));

if (defined('DEVELOPER_MODE')) {
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', true);
  ini_set('display_startup_errors', true);
}

function RedirectToSite($url = SB_WP_URL, $text = "", $NotJS = false) {
  if (!headers_sent() && !$NotJS) {
    Header(sprintf('Location: %s', $url));
  } else {
    Header("Content-Type: text/html; charset=UTF8");
    echo("<script>");
    if ($text != "")
      printf('alert("%s");', addslashes($text));
    printf('document.location.href="%s";</script>', $url);
  }

  exit();
}

$Site = SB_WP_URL;
$Site = str_replace(array('https', 'http', '://'), '', $Site);

$AuthResult = SteamAuthorize($Site);
if (!$AuthResult)
  RedirectToSite(); // Something error. User cancelled authentication?
else if (strpos($AuthResult, 'steamcommunity') !== false) {
  if (isset($_GET['reason']))
    $_SESSION['why'] = $_GET['reason'];
  else
    $_SESSION['why'] = 'admin_auth';
  $_SESSION['from'] = $_SERVER['HTTP_REFERER'];

  \SessionManager::closeWrite();
  RedirectToSite($AuthResult); // Auth started. Redirect to Steam.
} else {
  // Auth success. Steam returned SteamID64
  $SteamID = CSteamId::factory($AuthResult);

  switch ($_SESSION['why']) {
    case 'user_auth': {
      $_SESSION['steam']  = $SteamID->v2;
      @session_write_close();
      RedirectToSite($_SESSION['from'], 'Вы успешно авторизованы в системе. Теперь Ваш SteamID будет автоматически подставляться там, где это возможно.', true);
      break;
    }

    case 'admin_auth': {
      $ReasonMsg = '';
      if (\UserManager::forceLoginBySteam($SteamID, $ReasonMsg))
        RedirectToSite($_SESSION['from'], 'Администратор найден, переадресация...');

      RedirectToSite($_SESSION['from'], "Произошла ошибка: $ReasonMsg", true);
      break;
    }
  }
}