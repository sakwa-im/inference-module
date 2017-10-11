<?php

namespace Sakwa\Logging;

trait LogTrait
{
    /**
     * @var string $loggerLogChannel
     */
    protected static $loggerLogChannel = 'main';


    /**
     * @var string|null $loggingLogPrefix
     */
    protected static $loggingLogPrefix;

    /**
     * Log a message object with the TRACE level.
     *
     * @param mixed     $message   message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    protected function trace($message, $throwable = null)
    {
        Facade::getInstance(self::$loggerLogChannel)->trace(self::loggingGetLogPrefix() . $message, $throwable);
    }

    /**
     * Log a message object with the DEBUG level.
     *
     * @param mixed     $message   message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    protected static function debug($message, $throwable = null)
    {
        Facade::getInstance(self::$loggerLogChannel)->debug(self::loggingGetLogPrefix() . $message, $throwable);
    }

    /**
     * Log a message object with the INFO Level.
     *
     * @param mixed     $message   message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    protected static function info($message, $throwable = null)
    {
        Facade::getInstance(self::$loggerLogChannel)->info(self::loggingGetLogPrefix() . $message, $throwable);
    }

    /**
     * Log a message with the WARN level.
     *
     * @param mixed     $message   message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    protected static function warn($message, $throwable = null)
    {
        Facade::getInstance(self::$loggerLogChannel)->warn(self::loggingGetLogPrefix() . $message, $throwable);
    }

    /**
     * Log a message object with the ERROR level.
     *
     * @param mixed     $message   message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    protected static function error($message, $throwable = null)
    {
        Facade::getInstance(self::$loggerLogChannel)->error(self::loggingGetLogPrefix() . $message, $throwable);
    }

    /**
     * Log a message object with the FATAL level.
     *
     * @param mixed     $message   message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    protected static function fatal($message, $throwable = null)
    {
        Facade::getInstance(self::$loggerLogChannel)->fatal(self::loggingGetLogPrefix() . $message, $throwable);
    }

    /**
     * Function for getting the log prefix
     *
     * @return string
     */
    protected static function loggingGetLogPrefix()
    {
        if (is_null(self::$loggingLogPrefix)) {
            $replacements = array(
                'Inference'  => 'I',
                'Execution'  => 'E',
                'Evaluation' => 'E',
                'Expression' => 'E',
                'Model'      => 'M',
                'Module'     => 'M',
                'State'      => 'S',
                'Entity'     => 'E',
                'Variable'   => 'V',
                'Factory'    => 'F',
                'Utils'      => 'U',
                'Registry'   => 'R',
                'Runner'     => 'R'
            );

            $search  = array();
            $replace = array();

            foreach ($replacements as $key => $value) {
                $search[] = '\\'.$key.'\\';
                $search[] = '\\'.$key.' ';

                $replace[] = '\\'.$value.'\\';
                $replace[] = '\\'.$value.' ';
            }

            $className = str_replace($search,
                                     $replace,
                                     substr(__CLASS__, 5).' ');

            self::$loggingLogPrefix = substr($className, 1).'- ';
        }

        return self::$loggingLogPrefix;
    }
}