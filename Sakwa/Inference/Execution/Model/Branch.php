<?php

namespace Sakwa\Inference\Execution\Model;

use Sakwa\DecisionModel\Enum\BranchEvaluation;
use Sakwa\Exception;
use Sakwa\Inference\State\Manager;
use Sakwa\Expression\Engine;

/**
 * Class Branch
 *
 * @package Sakwa\Inference\Execution\Model
 * @property \Sakwa\DecisionModel\Branch $node
 */
class Branch extends Base {

    /**
     * @var boolean
     */
    protected $hasInfluenceOnBranchingBehaviour = true;

    /**
     * @var boolean
     */
    protected $branch_is_tested = false;

    /**
     * @var boolean
     */
    protected $branch_has_retest = false;

    /**
     * @param array $options
     * @return bool
     */
    public function evaluate($options = array())
    {
        if (!$this->branch_is_tested || $this->node->getBranchEvaluation() == BranchEvaluation::always) {
            $branch = false;

            $entityManager = Manager::getInstance();

            $engine = new Engine();
            $engine->setExpression($this->node->getExpression());

            $hasDirtyEntities = false;

            foreach ($engine->getAllEntitiesFromExpression() as $entity) {
                if ($entity->isDirty()) {
                    $hasDirtyEntities = true;
                    break;
                }
            }

            if (!$this->branch_is_tested || ($this->branch_is_tested && $hasDirtyEntities)) {
                $result = $engine->processExpression();

                if (!$result->isBoolean()) {
                    throw new Exception('Invalid branch expression detected: "'.$this->node->getExpression().'".');
                }

                if ($result->getValue()) {
                    $entityManager->branch($this->node->getReference());
                    $branch = true;
                }
            }

            $this->branch_is_tested = true;

            return $branch;
        }
        return false;
    }

    public function postEvaluate()
    {
        Manager::getInstance()->revert($this->node->getReference());
    }
}