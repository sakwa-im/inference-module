<?php

use Sakwa\Utils\Registry;

class RegistryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldGetNullWhenGettingInvalidKey()
    {
        $this->assertNull(Registry::get('foobar'));
    }

    /**
     * @test
     */
    public function shouldNotBeAbleToClone()
    {
        $reg = new RegistryCloneTest();
        $obj = $reg->testClone();

        $this->assertInstanceOf('\RegistryCloneTest', $obj);
    }

    /**
     * @test
     */
    public function shouldImplicitlyCreateGlobalContext()
    {
        $reg = new RegistryCloneTest();
        $reg->clearGlobalContext();

        $reg = Registry::getInstance();
        $this->assertInstanceOf('\\Sakwa\\Utils\\Registry', $reg);
    }
}

class RegistryCloneTest extends Registry
{
    public function clearGlobalContext()
    {
        self::$global_context = null;
    }

    public function __construct()
    {
        //
    }

    public function testClone()
    {
        return clone $this;
    }
}