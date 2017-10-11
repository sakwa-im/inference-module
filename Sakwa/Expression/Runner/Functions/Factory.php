<?php

namespace Sakwa\Expression\Runner\Functions;

class Factory
{
    protected static $plugins = null;

    /**
     * @return array containing the definition of available plugins
     */
    public static function getPlugins()
    {
        if (is_null(self::$plugins)) {
            self::detectPlugins();
        }

        return self::$plugins;
    }

    /**
     * @param string $pluginName
     *
     * @return \Sakwa\Expression\Runner\Functions\Plugin\Base
     */
    public static function getPlugin($pluginName)
    {
        if (is_null(self::$plugins)) {
            self::detectPlugins();
        }

        if (isset(self::$plugins[$pluginName])) {
            $plugin = self::$plugins[strtolower($pluginName)];

            return new $plugin['class']();
        }
    }

    /**
     * @param string $pluginName
     *
     * @return \Sakwa\Expression\Runner\Functions\Plugin\Base
     */
    public static function hasPlugin($pluginName)
    {
        if (is_null(self::$plugins)) {
            self::detectPlugins();
        }

        return isset(self::$plugins[strtolower($pluginName)]);
    }

    /**
     * Function for detecting available plugins
     */
    protected static function detectPlugins()
    {
        if (is_null(self::$plugins)) {
            self::$plugins = array();

            $dir = dir(__DIR__.DIRECTORY_SEPARATOR.'Plugin');

            while (($path = $dir->read()) !== false) {
                if (in_array($path, array('.', '..', 'Base.php'))) {
                    continue;
                }

                $rawClassName = substr($path, 0, strpos($path, '.'));
                $className    = 'Sakwa\\Expression\\Runner\\Functions\\Plugin\\'.$rawClassName;
                $functionName = strtolower($rawClassName);

                $obj = new $className();

                self::$plugins[strtolower($functionName)] = $obj->getDefinition();
            }
        }
    }
}