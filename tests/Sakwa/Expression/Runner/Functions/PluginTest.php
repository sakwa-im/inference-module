<?php

namespace Sakwa\Expression\Runner\Functions\Plugin;

use Sakwa\Expression\Engine;

class PluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldParsePluginDefinitionCorrectly()
    {
        $plugin = new PluginTestClass();
        $definition = $plugin->getDefinition();

        $this->assertEquals($definition['function'],    'plugintestclass');
        $this->assertEquals($definition['description'], 'Test van reflection mechanism');
        $this->assertEquals($definition['name'],        'PluginTestClass');
        $this->assertEquals($definition['class'],       'Sakwa\\Expression\\Runner\\Functions\\Plugin\\PluginTestClass');

        $this->assertCount(3, $definition['parameters']);
    }

    /**
     * @test
     * @dataProvider dataProviderShouldParsePluginParametersCorrectly
     */
    public function shouldParsePluginParametersCorrectly($index, $expectedResult)
    {
        $plugin = new PluginTestClass();
        $definition = $plugin->getDefinition();

        $this->assertEquals($definition['parameters'][$index], $expectedResult);
    }

    public function dataProviderShouldParsePluginParametersCorrectly()
    {
        return array(
            array(0, array('name' => 'param1', 'default' => null, 'optional' => false, 'variadic' => false)),
            array(1, array('name' => 'param2', 'default' => 123,  'optional' => true,  'variadic' => false)),
            array(2, array('name' => 'params', 'default' => null, 'optional' => true,  'variadic' => true))
        );
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
            array('sum(10)', 10),
            array('sum (10, 22)', 32),
            array('max(42, -63)', 42),
            array('80 + sum (10, 20, 30) + max(42, 63)', 203)
        );
    }
}

class PluginTestClass extends Base
{
    /**
     * Test van reflection mechanism
     * @param       $param1
     * @param int   $param2
     * @param array ...$params
     */
    public function plugintestclass($param1, $param2 = 123, ...$params)
    {
        //
    }
}