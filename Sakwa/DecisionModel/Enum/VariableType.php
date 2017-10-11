<?php

namespace Sakwa\DecisionModel\Enum;

/**
 * VariableType
 * enum class declaring valid variable types
 */
class VariableType extends Base
{
    const numeric     = 0,
          character   = 1,
          enumeration = 2,
          date        = 3,
          boolean     = 4;

    public static $enum = array(
        self::numeric     => 'numeric',
        self::character   => 'character',
        self::enumeration => 'enumeration',
        self::date        => 'date',
        self::boolean     => 'boolean'
    );

}