<?php

namespace Sakwa\Inference;

use Sakwa\Inference\Execution\Model\Factory;
use Sakwa\Inference\State\Manager;
use Sakwa\Inference\Execution\Chain;
use Sakwa\Logging\LogTrait;
use Sakwa\Utils\Registry;

class Module
{
    use LogTrait;

    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $context;

    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $previous_context;


    public function __construct(\Sakwa\DecisionModel\DecisionTree $decisionModel)
    {
        $this->createNewInferenceStateContext();

        Registry::set('decisionModel', $decisionModel, State::getInstance()->getContext());
    }

    public function start()
    {
        self::info('Starting inference');
        $this->switchToContext();
        $this->inferNextDecisionNode($this->getRootNode());
        $this->restorePreviousContext();
        self::info('Completed inference');
    }

    /**
     * @param \Sakwa\DecisionModel\BaseNode $node
     */
    protected function inferNextDecisionNode(\Sakwa\DecisionModel\BaseNode $node)
    {
        $chain = new Chain();
        $chain->addEntity($node->getChildren());

        $previousNode = null;
        $childNode = false;
        $executionModel = null;

        while (true) {
            if ($childNode === false) {
                $childNode = $chain->nextNode();

                if (!is_null($childNode)) {
                    $executionModel = Factory::createModel($childNode);
                    $chain->setExecutionModel($executionModel);
                }
            }
            else {
                $executionModel = $chain->getExecutionModel();

                if (is_null($executionModel)) {
                    // @codeCoverageIgnoreStart
                    $executionModel = Factory::createModel($childNode);
                    $chain->setExecutionModel($executionModel);
                    // @codeCoverageIgnoreEnd
                }
            }


            if (!$chain->getCommitBehaviourEnabled() && $executionModel->getHasInfluenceOnBranchingBehaviour()) {
                $chain->setCommitBehaviourEnabled();
            }

            if (!is_null($executionModel) && $executionModel->evaluate()) {
                $previousNode = $chain->getEntity();
                $chain->setNodeEvaluated();
                $chain->addEntity($childNode->getChildren());
                $childNode = false;
                continue;
            }


            while (is_null(($childNode = $chain->nextNode()))) {
                if (!is_null(($parentEntity = $chain->getParentEntity()))) {
                    $executionModel = $parentEntity->getExecutionModel();

                    if (!is_null($executionModel)) {
                        $executionModel->postEvaluate();
                    }
                }

                if ($chain->getCommitBehaviourEnabled() && $previousNode->isLeafNode()) {
                    Manager::getInstance()->commit();
                }

                if ($chain->hasParentEntity()) {
                    $chain->popEntity();
                }
                else {
                    break 2;
                }
            }

            if (!is_null($childNode)) {
                $executionModel = Factory::createModel($childNode);
                $chain->setExecutionModel($executionModel);
            }
            else {
                // @codeCoverageIgnoreStart
                break;
                // @codeCoverageIgnoreEnd
            }
        }
    }

    /**
     * @return \Sakwa\DecisionModel\BaseNode
     */
    protected function getRootNode()
    {
        return Registry::get('decisionModel', State::getInstance()->getContext())->retrieve();
    }

    /**
     * Function for creating a new inference state context
     */
    protected function createNewInferenceStateContext()
    {
        $state = State::getInstance();

        $this->previous_context = $state->getContext();
        $this->context = $state->createNewContext();

        self::debug('Create state context: ' . $this->context);
    }

    /**
     * Function for restoring the previous inference state context
     */
    protected function restorePreviousContext()
    {
        self::debug('Restore previous context: ' . $this->previous_context);
        State::getInstance()->setContext($this->previous_context);
    }

    /**
     * Function for switching to the new inference state context
     */
    protected function switchToContext()
    {
        self::debug('Switch state context: ' . $this->context);
        State::getInstance()->setContext($this->context);
    }
}