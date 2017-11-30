<?php

use Sakwa\Persistence\Record;
use Sakwa\DecisionModel\Variables\Char;
use Sakwa\DecisionModel\Enum\VariableType;

class CharTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToConstruct()
    {
        $rec = new Record();
        $rec->charVariableValue = '';
        $rec->variableType = 'character';

        $obj = new Char('foobar');
        $obj->fill($rec);

        $this->assertEquals(VariableType::character, $obj->getVariableType());
    }
}