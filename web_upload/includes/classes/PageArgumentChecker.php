<?php

class PageArgumentChecker
{
    public static function FirstCheck($method, $arg)
    {
        switch ($method)
        {
            case "GET":
              if (!isset($_GET["$arg"]))
              {
                  echo "Ошибка доступа";
                  exit();
              }
              break;
            case "POST":
              if (!isset($_POST["$arg"]))
              {
                  echo "Ошибка доступа";
                  exit();
              }
              break;
        }
    }
}
