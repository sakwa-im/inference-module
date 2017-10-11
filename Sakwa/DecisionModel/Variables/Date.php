<?php

namespace Sakwa\DecisionModel\Variables;

use Sakwa\DecisionModel\VariableDef;
use Sakwa\DecisionModel\Enum\VariableType;

class Date extends VariableDef
{

    public function __construct($name, $type = null)
    {
        parent::__construct($name, VariableType::date);
    }
}