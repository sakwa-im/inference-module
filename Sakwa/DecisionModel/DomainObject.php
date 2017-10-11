<?php

namespace Sakwa\DecisionModel;

/**
 * DomainObject
 */
class DomainObject extends BaseNode
{

    /**
     * @param string $name
     * @param int $type
     */
    public function __construct($name, $type = null)
    {
        parent::__construct($name, Enum\NodeType::DomainObject);
    }
}