<?php

namespace Sakwa\Inference\Execution;

use Sakwa\Inference\Execution\Chain\Entity;
use Sakwa\Utils\Guid;
use Sakwa\Utils\Registry;
use Sakwa\Inference\State;


class Chain
{
    /**
     * @var \Sakwa\Inference\Execution\Chain\Entity[] $entities
     */
    protected $entities = array();

    /**
     * @param \Sakwa\DecisionModel\BaseNode[] $nodes
     */
    public function addEntity($nodes)
    {
        $entity = new Entity($nodes);

        if (!is_null(($currentEntity = $this->getEntity()))) {
            if ($currentEntity->getCommitBehaviourEnabled()) {
                $entity->setCommitBehaviourEnabled();
            }
        }

        $this->entities[] = $entity;
    }

    /**
     * @return null|\Sakwa\Inference\Execution\Chain\Entity
     */
    public function getEntity()
    {
        if (count($this->entities) > 0) {
            $index = count($this->entities) - 1;

            return $this->entities[$index];
        }

        return null;
    }

    /**
     * @return null|\Sakwa\Inference\Execution\Chain\Entity
     */
    public function getParentEntity()
    {
        if (count($this->entities) > 1) {
            $index = count($this->entities) - 2;

            return $this->entities[$index];
        }

        return null;
    }

    /**
     * @return null|\Sakwa\Inference\Execution\Chain\Entity
     */
    public function popEntity()
    {
        return array_pop($this->entities);
    }

    /**
     * @return boolean
     */
    public function hasParentEntity()
    {
        return (count($this->entities) > 1);
    }

    /**
     * @param \Sakwa\Inference\Execution\Model\Base $executionModel
     */
    public function setExecutionModel(\Sakwa\Inference\Execution\Model\Base $executionModel)
    {
        if (!is_null(($entity = $this->getEntity()))) {
            $entity->setExecutionModel($executionModel);
        }
    }

    /**
     * @return null|\Sakwa\Inference\Execution\Model\Base
     */
    public function getExecutionModel()
    {
        if (!is_null(($entity = $this->getEntity()))) {
            return $entity->getExecutionModel();
        }
        return null;
    }

    /**
     * @param boolean $evaluated
     */
    public function setNodeEvaluated($evaluated = true)
    {
        if (!is_null(($entity = $this->getEntity()))) {
            $entity->setNodeEvaluated($evaluated);
        }
    }

    /**
     * @return null|\Sakwa\Inference\Execution\Model\Base
     */
    public function getNodeEvaluated()
    {
        if (!is_null(($entity = $this->getEntity()))) {
            return $entity->getNodeEvaluated();
        }
        return null;
    }

    /**
     * Function for setting the commit behaviour
     */
    public function setCommitBehaviourEnabled()
    {
        if (!is_null(($entity = $this->getEntity()))) {
            $entity->setCommitBehaviourEnabled();
        }
    }

    /**
     * @return null|\Sakwa\Inference\Execution\Model\Base
     */
    public function getCommitBehaviourEnabled()
    {
        if (!is_null(($entity = $this->getEntity()))) {
            return $entity->getCommitBehaviourEnabled();
        }
        return null;
    }

    /**
     * @return null|\Sakwa\DecisionModel\BaseNode
     */
    public function nextNode()
    {
        if (!is_null(($entity = $this->getEntity()))) {
            return $entity->nextNode();
        }

        return null;
    }

    /**
     * @return null|\Sakwa\DecisionModel\BaseNode
     */
    public function currentNode()
    {
        if (!is_null(($entity = $this->getEntity()))) {
            return $entity->currentNode();
        }

        return null;
    }
}