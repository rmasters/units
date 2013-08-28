<?php

namespace Units;

class Facade
{
    protected static $instance;

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array(array(self::getInstance(), $name), $arguments);
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Convert;
        }
        return self::$instance;
    }
}