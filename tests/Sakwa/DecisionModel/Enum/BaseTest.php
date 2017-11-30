<?php

use Sakwa\DecisionModel\Enum\NodeType;

class BaseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToGetTheStringOfAnEnum()
    {
        $this->assertEquals('Branch', NodeType::getEnumString(7));
        $this->assertEquals(7, NodeType::getEnumValue('Branch'));
    }
}