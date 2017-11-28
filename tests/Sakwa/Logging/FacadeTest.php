<?php

use Sakwa\Logging\Facade;
use Sakwa\Utils\Registry;

class FacadeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldImplementAllTypesOfLogFlow()
    {
        $mockLogger = $this->createMock('\Sakwa\Logging\Facade');

        $logFacade = Facade::getInstance();
        $loggerBackup = $logFacade->getLogger();
        $logFacade->setLogger($mockLogger);

        foreach (array('trace', 'debug', 'info', 'warn', 'error', 'fatal') as $logType) {
            $mockLogger
                ->expects($this->once())
                ->method($logType)
                ->with($this->anything(), $this->anything());

            $logFacade->{$logType}('test '.$logType);
        }

        $logFacade->setLogger($loggerBackup);
    }
}