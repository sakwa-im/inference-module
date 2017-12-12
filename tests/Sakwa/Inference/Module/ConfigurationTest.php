<?php

namespace Test\Inference\Module;

use Sakwa\Inference\Module\Configuration;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldBeAbleToSelectAnInvalidPersistenceDriver()
    {
        $configuration = new Configuration();
        $configuration->setPersistenceDriver('foo');
    }
}