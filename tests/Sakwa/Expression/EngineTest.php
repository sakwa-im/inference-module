<?php

use Sakwa\Expression\Engine;

class EngineTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function validateAvailabilityOfCachingMechanism()
    {
        $engine = new Engine();
        $engine->disableCaching();
        $this->assertFalse($engine->isCacheEnabled());

        $engine->enableCaching();
        $this->assertTrue($engine->isCacheEnabled());

        $expression = '1+2+3';

        $engine->setExpression($expression);
        $engine->setExpression($expression);
    }

    /**
     * @test
     */
    public function shouldBeAbleToRetriefEntities()
    {
        $engine = new Engine();
        $this->assertCount(0, $engine->getAllEntitiesFromExpression());

        $engine->setExpression('1+2+3');
        $this->assertCount(0, $engine->getAllEntitiesFromExpression());

        $engine->setExpression('{44444444-dddd-5555-eeee-666666666666} + 2 + {11111111-aaaa-2222-bbbb-333333333333}');
        $this->assertCount(2, $engine->getAllEntitiesFromExpression());
    }

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
            array('1-(1+1)*1/1', -1),
            array('4+4+"4"+4+4', '848'),
            array('2 - 1 != 2 - 1',  false)
        );
    }
}