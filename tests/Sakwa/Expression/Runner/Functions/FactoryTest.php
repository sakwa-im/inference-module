<?php


use Sakwa\Expression\Engine;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldResolvePluginDefinitionCorrectly()
    {
        $definition = \Sakwa\Expression\Runner\Functions\Factory::getPlugins()['max'];

        $this->assertEquals($definition['function'],    'max');
        $this->assertEquals($definition['name'],        'Max');
        $this->assertEquals($definition['class'],       'Sakwa\\Expression\\Runner\\Functions\\Plugin\\Max');

        $this->assertCount(1, $definition['parameters']);
    }

    /**
     * @test
     * @dataProvider dataProviderShouldResolvePluginParametersCorrectly
     */
    public function shouldResolvePluginParametersCorrectly($index, $expectedResult)
    {
        $definition = \Sakwa\Expression\Runner\Functions\Factory::getPlugins()['max'];

        $this->assertEquals($definition['parameters'][$index], $expectedResult);
    }

    public function dataProviderShouldResolvePluginParametersCorrectly()
    {
        return array(
            array(0, array('name' => 'params', 'default' => null, 'optional' => true, 'variadic' => true))
        );
    }

}