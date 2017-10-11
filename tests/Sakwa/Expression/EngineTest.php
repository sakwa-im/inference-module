<?php

use Sakwa\Expression\Engine;

class EngineTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @dataProvider dataProviderShouldEvaluateExpressionsCorrectly
     */
    public function shouldEvaluateExpressionsCorrectly($expression, $expectedResult)
    {
        $engine = new Engine();

        $engine->setExpression($expression);
        $actualResult = $engine->processExpression();
        $this->assertEquals($expectedResult, $actualResult->getValue());
    }

    public function dataProviderShouldEvaluateExpressionsCorrectly()
    {
        return array(
            array('1',   1),
            array('-1', -1),

            array('1+1',   2),
            array('1+-1',  0),
            array('1--1',  2),
            array('1*1',   1),
            array('1/1',   1),
            array('1*-1', -1),

            array('1+1*2',    3),
            array('1*(1-1)',  0),
            array('1+2+3+4',  10),
            array('2^-(2+2)', 0.0625),

            array('1-1+1*1/1',    1),
            array('1-(1+1)*1/1', -1)
        );
    }
}