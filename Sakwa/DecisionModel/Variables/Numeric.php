<?php

namespace Sakwa\DecisionModel\Variables;

use Sakwa\DecisionModel\VariableDef;
use Sakwa\DecisionModel\Enum\VariableType;
use Sakwa\DecisionModel\Enum\NodeType;

class Numeric extends VariableDef
{
    private $min  = null;
    private $max  = null;
    private $step = null;

    public function __construct($name, $type = null)
    {
        $this->setVariableType(VariableType::numeric);
        parent::__construct($name, NodeType::VarDefinition);
    }

    protected function _fill(
        \Sakwa\Persistence\Record $record)
    {
        parent::_fill($record);
        $this->setValue($record->intVariableValue);
        $this->setMin($record->intVariableMinvalue);
        $this->setMax($record->intVariableMaxvalue);
        $this->setStep($record->intVariableStepvalue);
    }

    public function getMin()
    {
        return $this->min;
    }

    public function getMax()
    {
        return $this->max;
    }

    public function getStep()
    {
        return $this->step;
    }

    public function setMin($min)
    {
        $this->min = $min;
    }

    public function setMax($max)
    {
        $this->max = $max;
    }

    public function setStep($step)
    {
        $this->step = $step;
    }
}