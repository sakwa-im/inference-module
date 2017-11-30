<?php

namespace Sakwa\Inference\Execution\Model;

use Sakwa\Logging\LogTrait;

class Factory {
    use LogTrait;

    public static function createModel(\Sakwa\DecisionModel\BaseNode $node)
    {
        self::debug('Creating '.\Sakwa\DecisionModel\Enum\NodeType::getEnumString($node->getType()).' IEM');

        switch($node->getType()) {
            case \Sakwa\DecisionModel\Enum\NodeType::Branch:
                return new Branch($node);
                break;

            case \Sakwa\DecisionModel\Enum\NodeType::Expression:
                return new Expression($node);
                break;


            case \Sakwa\DecisionModel\Enum\NodeType::Root:
                return new Root($node);
                break;

            case \Sakwa\DecisionModel\Enum\NodeType::VarDefinition:
                return new VariableDef($node);
                break;

            case \Sakwa\DecisionModel\Enum\NodeType::DomainObject:
                return new DomainObject($node);
                break;

            default:
                return new Generic($node);
                break;
        }
    }
}