<?php

namespace Sakwa\DecisionModel\Enum;

/**
 * VariableType
 * enum class declaring valid variable types
 */
class VariableEquality extends Base
{
    const equal = 0,
          basetype = 1,
          type = 2,
          value = 4,
          domain = 8,
          min = 16,
          max = 32,
          major = 60,
          blocking = 3;

    protected static $enum = array(
        self::equal => 'equal',
        self::basetype => 'basetype',
        self::type => 'type',
        self::value => 'value',
        self::domain => 'domain',
        self::min => 'min',
        self::max => 'max',
        self::major => 'major',
        self::blocking => 'blocking',
    );

}