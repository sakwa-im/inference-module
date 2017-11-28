<?php

use Sakwa\Utils\Registry;

class LogTraitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToLogAllTypes()
    {
        $loggerBackup = Registry::get('Sakwa\Loggingmain');

        $mockLogger = $this->createMock('\Sakwa\Logging\Facade');
        Registry::set('Sakwa\Loggingmain', $mockLogger);

        foreach (array('trace', 'debug', 'info', 'warn', 'error', 'fatal') as $logType) {
            $mockLogger
                ->expects($this->once())
                ->method($logType)
                ->with($this->anything(), $this->anything());

            $obj = $this->generateTestClass($logType);
            $obj->runTest();
        }

        Registry::set('Sakwa\Loggingmain', $loggerBackup);
    }

    protected function generateTestClass($funtion = 'info')
    {
        $className = 'TestLogTrait'.ucfirst(strtolower($funtion));
        $functionUnderTest = strtolower($funtion);

        $code = <<<CODE
class {$className}
{
    use \Sakwa\Logging\LogTrait;

    public function runTest()
    {
        self::{$functionUnderTest}('test {$funtion}');
    }
}
CODE;

        eval($code);
        return new $className();
    }
}