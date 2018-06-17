<?php
spl_autoload_register(function($className) {
  $dirname = dirname(__FILE__);
  $path    = "$dirname/$className.php";

  if (file_exists($path))
    require_once($path);
});