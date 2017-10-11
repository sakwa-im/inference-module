<?php

namespace Sakwa\DecisionModel\Variables;

use Sakwa\DecisionModel\VariableDef;
use Sakwa\DecisionModel\Enum\VariableType;

class Char extends VariableDef
{

    public function __construct($name, $type = null)
    {
        parent::__construct($name, VariableType::character);
    }

    public function retrieve()
    {

    }

    protected function _fill(
        \Sakwa\Persistence\Record $record)
    {
        parent::_fill($record);
        $this->setValue($record->charVariableValue);
    }
}