<?php

use Sakwa\Persistence\Record;
use Sakwa\DecisionModel\Variables\Enum;
use Sakwa\DecisionModel\Enum\VariableType;

class EnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToConstruct()
    {
        $rec = new Record();
        $rec->variableType         = 'enumeration';
        $rec->enumVariableBranches = array('foo', 'bar');
        $rec->enumVariable         = 'foo';

        $obj = new Enum('foobar');
        $obj->fill($rec);

        $this->assertEquals(VariableType::enumeration, $obj->getVariableType());

        $this->assertCount(2, $obj->getBranches());
        $this->assertTrue(($obj->getBranches()[0] == 'foo'));
        $this->assertTrue(($obj->getBranches()[1] == 'bar'));
    }
}