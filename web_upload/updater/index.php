<?php
// *************************************************************************
//  This file is part of SourceBans++.
//
//  Copyright (C) 2014-2016 Sarabveer Singh <me@sarabveer.me>
//
//  SourceBans++ is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, per version 3 of the License.
//
//  SourceBans++ is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with SourceBans++. If not, see <http://www.gnu.org/licenses/>.
//
//  This file is based off work covered by the following copyright(s):  
//
//   SourceBans 1.4.11
//   Copyright (C) 2007-2015 SourceBans Team - Part of GameConnect
//   Licensed under GNU GPL version 3, or later.
//   Page: <http://www.sourcebans.net/> - <https://github.com/GameConnect/sourcebansv1>
//
// *************************************************************************

// Check PHP environment.
if (version_compare(PHP_VERSION, '5.5') == -1) {
  Header('Content-Type: text/plain; charset=UTF8');
  echo("Работа SourceBans невозможна: для работы требуется PHP версии 5.5 и выше.\n");
  echo('На данный момент установлена версия ' . PHP_VERSION);
  exit();
}

if (!file_exists('../data/db.php')) {
  define('IN_SB', true);
  include('../data/config.php');

  $configuration = [
    'dsn' => sprintf('mysql:dbname=%s;host=%s;charset=UTF8;port=%d', urlencode(DB_NAME), urlencode(DB_HOST), DB_PORT),
    'user' => DB_USER,
    'pass' => DB_PASS,
    'prefix' => DB_PREFIX . '_',
    'options' => [
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION // TODO: move this to another mechanism
    ]
  ];

  // Here we placed in some time
  $exportedConfiguration = var_export($configuration, true);

  $config  = "<?php\n";
  $config .= "if (!defined('IN_SB')) exit();\n\n";

  $config .= "/**\n";
  $config .= " * This file contains all database configurations for\n";
  $config .= " * using in SourceBans in new DB Framework.\n";
  $config .= " */\n";
  $config .= "\DatabaseManager::CreateConfig('SourceBans', $exportedConfiguration);";

  if (!is_writable('../data/')) {
    $config = htmlspecialchars($config);

    Header("Content-Type: text/html; charset=UTF8");
    ?>
<!DOCTYPE HTML>
<html>
  <body>
    <p>Не удаётся записать конфигурационный файл для фреймворка работы с БД.</p>
    <p>Пожалуйста, скопируйте и вставьте следующий текст в <em>data/db.php</em> для продолжения работы апдейтера:</p>
    <pre><?= $config ?></pre>
    <p>Этот текст автоматически пропадёт, когда файл будет создан и записан.</p>
    <script>setTimeout(location.reload(), 5000);</script>
  </body>
</html>
    <?php
    exit();
  }

  file_put_contents('../data/db.php', $config);
  Header("Content-Type: text/html; charset=UTF8");
  echo('<script>setTimeout(location.reload, 500);</script>');
  exit();
}

 define('IS_UPDATE', true);

 ignore_user_abort(true);
 set_time_limit(0);

 include "../init.php";
 $theme->clearCompiledTemplate();

 define('IS_AJAX',   isset($_GET['updater_ajax_call']));

 include INCLUDES_PATH . "/CUpdate.php";
 $updater = new CUpdater();
 
 $setup = "Проверка текущей версии SourceBans...<b> " . $updater->getCurrentRevision() . "</b>";
 if(!$updater->needsUpdate())
 {
	if (IS_AJAX)
		die(json_encode(['result' => false, 'reason' => "Система в обновлениях не нуждается."]));
	$setup .= "<br /><br />Обновления не нужны. Удалите папку <b>updater</b>!";
	$theme->assign('setup', $setup);
	$theme->assign('progress', "");
	$theme->display('updater.tpl');
	die();
 }
 $setup .= "<br />Обновление до версии: <b>" . $updater->getLatestPackageVersion() . "</b>";
 
 $progress = $updater->doUpdates();
 
 if (IS_AJAX)
	die(json_encode(['result' => true]));
 $theme->assign('setup', $setup);
 $theme->assign('progress', $progress);
 $theme->display('updater.tpl');
?>
