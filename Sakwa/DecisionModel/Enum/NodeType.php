<?php

namespace Sakwa\DecisionModel\Enum;

/**
 * NodeType
 * enum class declaring valid node types
 */
class NodeType extends Base
{
    const unknown = 0,
        Tree = 1,
        Root = 2,
        VariableDefinitions = 3,
        VarDefinition = 4,
        DomainObject = 5,
        Expression = 6,
        Branch = 7;

    protected static $enum = array(
        self::unknown             => 'unknown',
        self::Tree                => 'Tree',
        self::Root                => 'Root',
        self::VariableDefinitions => 'VariableDefinitions',
        self::VarDefinition       => 'VarDefinition',
        self::DomainObject        => 'DomainObject',
        self::Expression          => 'Expression',
        self::Branch              => 'Branch'
    );

}