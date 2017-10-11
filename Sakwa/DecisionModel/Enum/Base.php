<?php

namespace Sakwa\DecisionModel\Enum;

/**
 * Base enum class
 */
abstract class Base
{
    /**
     * @var array
     */
    protected static $enum = array();

    /**
     * Function for checking the validity of an enum id
     * @param int $enumVal
     * @return bool
     */
    public static function isValid($enumVal)
    {
        return \array_key_exists($enumVal, static::$enum);
    }

    /**
     * Returns enum value by string representation
     * @param string $stringValue
     * @return integer
     */
    public static function getEnumValue($stringValue)
    {
        $vals = \array_keys(static::$enum, $stringValue);
        return reset($vals);
    }

    /**
     * Returns string representation by enum value
     * @param integer $enumVal
     * @return string
     */
    public static function getEnumString($enumVal)
    {
        return static::$enum[$enumVal];
    }

    /**
     * Function for checking the validity of enum value
     * @param string $stringValue
     * @return boolean
     */
    public static function isValueEnumValue($stringValue)
    {
        return \in_array($stringValue, static::$enum);
    }
}