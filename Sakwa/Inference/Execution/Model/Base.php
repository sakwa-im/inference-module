<?php

namespace Sakwa\Inference\Execution\Model;

abstract class Base
{
    /**
     * @var boolean
     */
    protected $hasInfluenceOnBranchingBehaviour = false;

    /**
     * @var \Sakwa\DecisionModel\BaseNode
     */
    protected $node;


    /**
     * Base constructor.
     *
     * @param \Sakwa\DecisionModel\BaseNode $node
     */
    public function __construct(\Sakwa\DecisionModel\BaseNode $node)
    {
        $this->node = $node;
        $this->_init();
    }

    /**
     * Function for initializing the IEM object
     * @return void
     */
    protected function _init()
    {
        // should be overridden/implemented by subclass
    }

    /**
     * @param array $options
     * @return boolean
     */
    abstract public function evaluate($options = array());

    /**
     * @return void
     */
    public function postEvaluate()
    {
        // should be overridden/implemented by subclass
    }

    /**
     * Function for checking if this node had influence on the branching behaviour
     * @return boolean
     */
    public function getHasInfluenceOnBranchingBehaviour()
    {
        return $this->hasInfluenceOnBranchingBehaviour;
    }
}