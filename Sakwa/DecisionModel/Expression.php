<?php

namespace Sakwa\DecisionModel;

use Sakwa\DecisionModel\Traits\Resolve\Expression AS ExperessionTrait;

/**
 * Expression node
 */
class Expression extends BaseNode
{
    use ExperessionTrait;

    /**
     * @param string $name
     * @param int $type
     */
    public function __construct($name, $type = null)
    {
        parent::__construct($name, Enum\NodeType::Expression);
    }
}