<?php

namespace Sakwa\DecisionModel;

/**
 * Mappings for sakwa objects
 */
class ObjectMapping
{
    public static $mapping = array(
        'sakwa.IBaseNodeImpl'       => '\Sakwa\DecisionModel\BaseNode',
        'sakwa.CharVariableImpl'    => '\Sakwa\DecisionModel\Variables\Char',
        'sakwa.NumericVariableImpl' => '\Sakwa\DecisionModel\Variables\Numeric',
        'sakwa.IntVariableImpl'     => '\Sakwa\DecisionModel\Variables\Numeric',
        'sakwa.EnumVariableImpl'    => '\Sakwa\DecisionModel\Variables\Enum',
        'sakwa.BoolVariableImpl'    => '\Sakwa\DecisionModel\Variables\Boolean',
        'sakwa.DateVariableImpl'    => '\Sakwa\DecisionModel\Variables\Date',
        'sakwa.IDomainObjectImpl'   => '\Sakwa\DecisionModel\DomainObject',
        'sakwa.IExpressionImpl'     => '\Sakwa\DecisionModel\Expression',
        'sakwa.IBranchImpl'         => '\Sakwa\DecisionModel\Branch',
        'sakwa.IRootNodeImpl'       => '\Sakwa\DecisionModel\BaseNode',
    );

    /**
     * @param string $sourceType
     * @return string
     * @throws Exception
     */
    public static function getObjectName($sourceType)
    {
        if (!array_key_exists($sourceType, self::$mapping)) {
            throw new Exception('Unknown type: '.$sourceType);
        }
        return self::$mapping[$sourceType];
    }
}