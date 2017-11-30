<?php

namespace Sakwa\DecisionModel\Variables;

use Sakwa\DecisionModel\VariableDef;
use Sakwa\DecisionModel\Enum\VariableType;
use Sakwa\DecisionModel\Enum\NodeType;

class Boolean extends VariableDef
{

    public function __construct($name, $type = null)
    {
        $this->setVariableType(VariableType::boolean);
        parent::__construct($name, NodeType::VarDefinition);
    }
}