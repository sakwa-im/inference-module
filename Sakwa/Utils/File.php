<?php

namespace Sakwa\Utils;

class File
{
    protected static $initialised = false;

    protected static function initialize()
    {
        if (self::$initialised) {
            return;
        }

        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        if (!defined('BASE_PATH')) {
            define('BASE_PATH', realpath(dirname(__DIR__ .DS.'..'.DS.'..'.DS.'..')).DS);
        }

        self::$initialised = true;
    }

    public static function createFilePath()
    {
        self::initialize();
        return BASE_PATH.implode(DS, func_get_args());
    }
}