<?php
function RegisterDirForAutoload($dir = NULL) {
  if ($dir === NULL)
    $dirname = dirname(__FILE__);

  spl_autoload_register(function($className) use ($dir) {
    $className = str_replace('\\', '/', $className);
    $path    = "$dir/$className.php";

    if (file_exists($path))
      require_once($path);
  });
}