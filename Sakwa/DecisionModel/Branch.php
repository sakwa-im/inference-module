<?php

namespace Sakwa\DecisionModel;

use Sakwa\DecisionModel\Traits\Resolve\Expression AS ExperessionTrait;
use Sakwa\DecisionModel\Enum\BranchEvaluation;

class Branch extends BaseNode
{
    use ExperessionTrait {
        ExperessionTrait::_fill AS _base_fill;
    }

    /**
     * @var integer \Sakwa\DecisionModel\Enum\BranchEvaluation
     */
    protected $branchEvaluation = BranchEvaluation::once;

    /**
     * @var string
     */
    protected $branchOperator = '=';

    /**
     * @param string $name
     * @param int $type
     */
    public function __construct($name, $type = null)
    {
        parent::__construct($name, Enum\NodeType::Branch);
    }

    /**
     * @param \Sakwa\Persistence\Record $record
     * @throws \Sakwa\Exception
     * @return void
     */
    protected function _fill(\Sakwa\Persistence\Record $record)
    {
        $this->_base_fill($record);

        if (!is_null($record->branchEvaluation) && $record->branchEvaluation != '') {
            if (BranchEvaluation::isValueEnumValue($record->branchEvaluation)) {
                $this->branchEvaluation = BranchEvaluation::getEnumValue($record->branchEvaluation);
            }
            else {
                throw new Exception('Invalid branch evaluation type: '.$record->branchEvaluation.', '.BranchEvaluation::getEnumValue($record->branchEvaluation));
            }
        }
    }

    /**
     * @return int
     */
    public function getBranchEvaluation()
    {
        return $this->branchEvaluation;
    }
}