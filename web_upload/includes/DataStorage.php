<?php
if(!defined("IN_SB")) { echo "Ошибка доступа!"; die(); }

namespace MaterialAdmin;
class DataStorage {
    private $_assignments;

    public static function register($name, $instance) {
        if (isset(self::$_assignments[$name])) {
            return;
        }

        self::$_assignments[$name] = $instance;
    }

    public static function __callStatic($name, $arguments) {
        if (!isset(self::$_assignments[$name])) {
            return null;
        }

        return self::$_assignments;
    }
}