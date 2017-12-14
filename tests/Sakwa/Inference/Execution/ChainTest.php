<?php

use Sakwa\Inference\Execution\Chain;
use Sakwa\DecisionModel\BaseNode;
use Sakwa\DecisionModel\Enum\NodeType;

class ChainTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function getExecutionModelShouldNotFailWhenNoEntityIsSet()
    {
        $chain = new Chain();
        $this->assertNull($chain->getExecutionModel());
    }

    /**
     * @test
     */
    public function getCommitBehaviourEnabledShouldNotFailWhenNoEntityIsSet()
    {
        $chain = new Chain();
        $this->assertNull($chain->getCommitBehaviourEnabled());
    }

    /**
     * @test
     */
    public function getNodeEvaluatedShouldNotFailWhenNoEntityIsSet()
    {
        $chain = new Chain();
        $this->assertNull($chain->getNodeEvaluated());
    }

    /**
     * @test
     */
    public function getNodeEvaluatedShouldNotFailWhenTheEntityIsSet()
    {
        $node = array(new BaseNode('foobar', NodeType::Branch));
        $chain = new Chain();
        $chain->addEntity($node);

        $this->assertfalse($chain->getNodeEvaluated());
    }

    /**
     * @test
     */
    public function nextNodeShouldNotFailWhenNoEntityIsSet()
    {
        $chain = new Chain();
        $this->assertNull($chain->nextNode());
    }

    /**
     * @test
     */
    public function currentNodeShouldNotFailWhenNoEntityIsSet()
    {
        $chain = new Chain();
        $this->assertNull($chain->currentNode());
    }

    /**
     * @test
     */
    public function currentNodeShouldNotFailWhenTheEntityIsSet()
    {
        $node = array(new BaseNode('foobar', NodeType::Branch));
        $chain = new Chain();
        $chain->addEntity($node);
        $chain->nextNode();

        $this->assertInstanceOf('\\Sakwa\\DecisionModel\\BaseNode', $chain->currentNode());
    }
}