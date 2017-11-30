<?php

use Sakwa\DecisionModel\VariableDef;
use Sakwa\Persistence\Record;
use Sakwa\Utils\Guid;
use Sakwa\DecisionModel\Enum\VariableType;

class VariableDefTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToSetTheValue()
    {
        $var = new VariableDef('foo');

        $this->assertNull($var->getValue());
        $var->setValue(123);
        $this->assertEquals(123, $var->getValue());
    }

    /**
     * @test
     */
    public function shouldBeAbleToSetTheVariableType()
    {
        $var = new VariableDef('foo');

        $this->assertNull($var->getVariableType());
        $var->setVariableType(123);
        $this->assertEquals(123, $var->getVariableType());
    }

    /**
     * @test
     */
    public function shouldBeAbleToSetTheInitializeMode()
    {
        $var = new VariableDef('foo');

        $this->assertNull($var->getInitializeMode());
        $var->setInitializeMode(0);
        $this->assertEquals(0, $var->getInitializeMode());
    }

    /**
     * @test
     */
    public function shouldBeAbleToFillTheRecord()
    {
        $guid = new Guid();

        $record = new Record();
        $record->reference        = "$guid";
        $record->description      = 'foo bar';
        $record->initializeMode   = 'None';
        $record->dateVariable     = '28-11-2017';
        $record->intVariableValue = 0;

        foreach (array(VariableType::character, VariableType::boolean, VariableType::date) as $variableType) {
            $var = new VariableDef('foo');
            $var->setVariableType($variableType);
            $var->fill($record);
        }

        $this->assertEquals(0, $var->getInitializeMode());
    }

    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldThrowExceptionWhenInvalidInitializeModeIsUsed()
    {
        $guid = new Guid();

        $record = new Record();
        $record->reference      = "$guid";
        $record->description    = 'foo bar';
        $record->initializeMode = 'DoesNotExistAsInitializeMode';

        $var = new VariableDef('foo');
        $var->fill($record);
    }
}