<?php

namespace Sakwa\Logging;

use Sakwa\Utils\File;
use Sakwa\Utils\Registry;

class Facade {
    /**
     * @var string $logChannel
     */
    protected $logChannel = 'main';

    /**
     * @var \Logger $log
     */
    protected $log;

    /**
     * @var boolean $loggerIsInitialized
     */
    protected static $loggerIsInitialized = false;

    private function __construct($logChannel = 'main')
    {
        $this->initLogger();
        $this->createLogChannel($logChannel);
    }

    /**
     * Function for getting the correct instance (based on the logging channel) of the loffing facade
     * @param string $logChannel
     * @return \Sakwa\Logging\Facade
     */
    public static function getInstance($logChannel = 'main')
    {
        if (!Registry::has(__NAMESPACE__.$logChannel)) {
            Registry::add(__NAMESPACE__.$logChannel, (new self($logChannel)));
        }

        return Registry::get(__NAMESPACE__.$logChannel);
    }

    /**
     * Function for initialising the configuration
     */
    protected function initLogger()
    {
        if(!self::$loggerIsInitialized) {
            \Logger::configure(File::createFilePath('Config', 'Log4php', 'config.xml'));
            self::$loggerIsInitialized = true;
        }
    }

    /**
     * Function for creating the logger
     * @param string $logChannel
     */
    protected function createLogChannel($logChannel)
    {
        $this->log = \Logger::getLogger($logChannel);
        $this->info('Start logging at: '.date('c'));
        $this->info('Log4php configuration file: '.File::createFilePath('Config', 'Log4php', 'config.xml'));
    }

    /**
     * @return \Logger
     */
    public function getLogger()
    {
        return $this->log;
    }

    /**
     * @param \Logger $logger
     */
    public function setLogger($logger)
    {
        $this->log = $logger;
    }

    /**
     * Log a message object with the TRACE level.
     *
     * @param mixed $message message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    public function trace($message, $throwable = null) {
        $this->log->trace($message, $throwable);
    }

    /**
     * Log a message object with the DEBUG level.
     *
     * @param mixed $message message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    public function debug($message, $throwable = null) {
        $this->log->debug($message, $throwable);
    }

    /**
     * Log a message object with the INFO Level.
     *
     * @param mixed $message message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    public function info($message, $throwable = null) {
        $this->log->info($message, $throwable);
    }

    /**
     * Log a message with the WARN level.
     *
     * @param mixed $message message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    public function warn($message, $throwable = null) {
        $this->log->warn($message, $throwable);
    }

    /**
     * Log a message object with the ERROR level.
     *
     * @param mixed $message message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    public function error($message, $throwable = null) {
        $this->log->error($message, $throwable);
    }

    /**
     * Log a message object with the FATAL level.
     *
     * @param mixed $message message
     * @param Exception $throwable Optional throwable information to include in the logging event.
     */
    public function fatal($message, $throwable = null) {
        $this->log->fatal($message, $throwable);
    }
}