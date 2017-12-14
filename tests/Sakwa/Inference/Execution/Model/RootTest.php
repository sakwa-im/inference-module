<?php

use Sakwa\DecisionModel\BaseNode;
use Sakwa\DecisionModel\Enum\NodeType;
use Sakwa\Inference\Execution\Model\Root;

class RootTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleEvaluate()
    {
        $node = new BaseNode('foobar', NodeType::Root);
        $iem = new Root($node);

        $this->assertTrue($iem->evaluate());
        $this->assertFalse($iem->evaluate());
    }
}