<?php

namespace Sakwa\DecisionModel\Variables;

use Sakwa\DecisionModel\VariableDef;
use Sakwa\DecisionModel\Enum\VariableType;
use Sakwa\DecisionModel\Enum\NodeType;

class Char extends VariableDef
{

    public function __construct($name, $type = null)
    {
        $this->setVariableType(VariableType::character);
        parent::__construct($name, NodeType::VarDefinition);
    }

    protected function _fill(
        \Sakwa\Persistence\Record $record)
    {
        parent::_fill($record);
        $this->setValue($record->charVariableValue);
    }
}