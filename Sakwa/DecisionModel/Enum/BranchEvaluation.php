<?php

namespace Sakwa\DecisionModel\Enum;

/**
 * VariableType
 * enum class declaring valid variable types
 */
class BranchEvaluation extends Base
{
    const once   = 0,
          always = 1;

    public static $enum = array(
        self::once   => 'Once',
        self::always => 'Always'
    );

}