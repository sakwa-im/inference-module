<?php

use Sakwa\Persistence\Record;
use Sakwa\DecisionModel\Variables\Numeric;
use Sakwa\DecisionModel\Enum\VariableType;

class NumericTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToConstruct()
    {
        $rec = new Record();
        $rec->intVariableValue     = 42;
        $rec->intVariableMinvalue  = 112;
        $rec->intVariableMaxvalue  = 1872;
        $rec->intVariableStepvalue = 1986;
        $rec->variableType         = 'numeric';

        $obj = new Numeric('foobar');
        $obj->fill($rec);

        $this->assertEquals(VariableType::numeric,      $obj->getVariableType());
        $this->assertEquals($rec->intVariableValue,     $obj->getValue());
        $this->assertEquals($rec->intVariableMinvalue,  $obj->getMin());
        $this->assertEquals($rec->intVariableMaxvalue,  $obj->getMax());
        $this->assertEquals($rec->intVariableStepvalue, $obj->getStep());
    }
}