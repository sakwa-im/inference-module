<?php

namespace Sakwa\DecisionModel\Variables;

use Sakwa\DecisionModel\VariableDef;
use Sakwa\DecisionModel\Enum\VariableType;
use Sakwa\DecisionModel\Enum\NodeType;

class Enum extends VariableDef
{
    private $branches = array();

    public function __construct($name, $type = null)
    {
        $this->setVariableType(VariableType::enumeration);
        parent::__construct($name, NodeType::VarDefinition);
    }

    protected function _fill(
        \Sakwa\Persistence\Record $record)
    {
        parent::_fill($record);
        $this->setBranches($record->enumVariableBranches);
        $this->setValue($record->enumVariable);
    }

    public function getBranches()
    {
        return $this->branches;
    }

    public function setBranches($branches)
    {
        $this->branches = $branches;
    }
}