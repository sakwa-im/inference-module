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
}

class RegistryCloneTest extends Registry
{
    public function __construct()
    {
        //
    }

    public function testClone()
    {
        return clone $this;
    }
}