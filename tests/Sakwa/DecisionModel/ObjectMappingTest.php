<?php

use \Sakwa\DecisionModel\ObjectMapping;

class ObjectMappingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToMapToInternalClassName()
    {
        $this->assertEquals('\Sakwa\DecisionModel\BaseNode', ObjectMapping::getObjectName('sakwa.IBaseNodeImpl'));
    }
    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldThrowExceptionInCaseOfInvalidName()
    {
        ObjectMapping::getObjectName('DoesNotExistInTheMapping');
    }
}