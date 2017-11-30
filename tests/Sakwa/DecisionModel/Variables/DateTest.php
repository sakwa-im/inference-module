<?php

use Sakwa\DecisionModel\Variables\Date;
use Sakwa\DecisionModel\Enum\VariableType;

class DateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToConstruct()
    {
        $obj = new Date('foobar');
        $this->assertEquals(VariableType::date, $obj->getVariableType());
    }
}