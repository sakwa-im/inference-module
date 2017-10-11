<?php

namespace Sakwa\DecisionModel\Variables;

use Sakwa\DecisionModel\VariableDef;
use Sakwa\DecisionModel\Enum\VariableType;

class Boolean extends VariableDef
{

    public function __construct($name, $type = null)
    {
        parent::__construct($name, VariableType::boolean);
    }
}