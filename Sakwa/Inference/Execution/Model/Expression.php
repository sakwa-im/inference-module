<?php

namespace Sakwa\Inference\Execution\Model;

use Sakwa\Expression\Engine;

/**
 * Class Assignment
 *
 * @package Sakwa\Inference\Execution\Model
 * @property \Sakwa\DecisionModel\Expression $node
 */
class Expression extends Base
{
    /**
     * @var boolean
     */
    protected $hasInfluenceOnBranchingBehaviour = true;

    /**
     * @var boolean
     */
    protected $expression_is_evaluated = false;

    /**
     * @param array $options
     * @return bool
     */
    public function evaluate($options = array())
    {
        if (!$this->expression_is_evaluated) {
            $this->evaluateExpression();
            $this->expression_is_evaluated = true;
            return true;
        }
        return false;
    }

    protected function evaluateExpression()
    {
        $engine = new Engine();
        $engine->setExpression($this->node->getExpression());
        $engine->processExpression();
    }
}