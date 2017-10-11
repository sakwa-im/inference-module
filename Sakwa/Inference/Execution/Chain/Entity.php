<?php

namespace Sakwa\Inference\Execution\Chain;

use Sakwa\Utils\Guid;
use Sakwa\Utils\Registry;
use Sakwa\Inference\State;


class Entity
{
    /**
     * @var \Sakwa\DecisionModel\BaseNode[] $nodes
     */
    public $nodes;

    /**
     * @var string[] $nodeKeys
     */
    protected $nodeKeys = array();

    /**
     * @var string $nodeKey
     */
    protected $nodeKey = null;

    /**
     * @var \Sakwa\Inference\Execution\Model\Base $executionModel
     */
    public $executionModel;

    /**
     * @var boolean $nodeEvaluated
     */
    public $nodeEvaluated = false;

    /**
     * @var boolean $commitBehaviourEnabled
     */
    public $commitBehaviourEnabled = false;

    /**
     * @param \Sakwa\DecisionModel\BaseNode[] $nodes
     */
    public function __construct($nodes)
    {
        $this->setNodes($nodes);
    }

    /**
     * @param \Sakwa\Inference\Execution\Model\Base $executionModel
     */
    public function setExecutionModel(\Sakwa\Inference\Execution\Model\Base $executionModel)
    {
        $this->executionModel = $executionModel;
    }

    /**
     * @return \Sakwa\Inference\Execution\Model\Base
     */
    public function getExecutionModel()
    {
        return $this->executionModel;
    }

    /**
     * @param boolean $evaluated
     */
    public function setNodeEvaluated($evaluated = true)
    {
        $this->nodeEvaluated = $evaluated;
    }

    /**
     * @return boolean
     */
    public function getNodeEvaluated()
    {
        return $this->nodeEvaluated;
    }

    /**
     * Function for setting the commit behaviour enabled
     */
    public function setCommitBehaviourEnabled()
    {
        $this->commitBehaviourEnabled = true;
    }

    /**
     * @return boolean
     */
    public function getCommitBehaviourEnabled()
    {
        return $this->commitBehaviourEnabled;
    }

    /**
     * @param \Sakwa\DecisionModel\BaseNode[] $nodes
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
        $this->nodeKeys = array_keys($this->nodes);
    }

    /**
     * @return null|\Sakwa\DecisionModel\BaseNode
     */
    public function nextNode()
    {
        if (count($this->nodeKeys) > 0) {
            $this->nodeKey = array_shift($this->nodeKeys);
            return $this->nodes[$this->nodeKey];
        }
        else {
            $this->nodeKeys = array_keys($this->nodes);
        }
        return null;
    }

    /**
     * @return \Sakwa\DecisionModel\BaseNode
     */
    public function currentNode()
    {
        if (is_null($this->nodeKey)) {
            return null;
        }
        return $this->nodes[$this->nodeKey];
    }

    /**
     * @return boolean
     */
    public function isLeafNode()
    {
        if (is_null($this->nodeKey)) {
            return false;
        }

        if (count($this->nodeKeys) == 0) {
            return (count($this->currentNode()->getChildren()) == 0);
        }

        return false;
    }
}