<?php
function RegisterDirForAutoload($dir = NULL) {
  if ($dir === NULL)
    $dirname = dirname(__FILE__);

  spl_autoload_register(function($className) use ($dir) {
    $ClassPath = str_replace('\\', '/', $className);
    $path      = "$dir/$ClassPath.php";

    if (file_exists($path))
      require_once($path);

    if (is_callable([$className, 'boot']))
      call_user_func_array([$className, 'boot'], []);
  });
}