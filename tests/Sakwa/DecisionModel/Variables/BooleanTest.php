<?php

use Sakwa\DecisionModel\Variables\Boolean;
use Sakwa\DecisionModel\Enum\VariableType;

class BooleanTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToConstruct()
    {
        $obj = new Boolean('foobar');
        $this->assertEquals(VariableType::boolean, $obj->getType());
    }
}