<?php

use Sakwa\Expression\Runner\Functions\Factory;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldResolvePluginDefinitionCorrectly()
    {
        $definition = Factory::getPlugins()['max'];

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

    /**
     * @test
     */
    public function shouldDetectPluginsWhenGettingAPluginByName()
    {
        $obj = new FactoryClearPlugins();

        $data = Factory::getPlugin('abs');
        $this->assertNotNull($data);
    }

    /**
     * @test
     */
    public function shouldNotFailWhenGettingTheDefinitionOfANogExistentPlugin()
    {
        $data = Factory::getPlugin('plugin_does_not_exist');
        $this->assertNull($data);
    }

    /**
     * @test
     */
    public function shouldDetectPluginsWhenCheckingAPluginByName()
    {
        $obj = new FactoryClearPlugins();

        $this->assertTrue(Factory::hasPlugin('abs'));
    }
}

class FactoryClearPlugins extends Factory
{
    public function __construct()
    {
        self::$plugins = null;
    }
}