<?php

use Sakwa\DecisionModel\BaseNode;
use Sakwa\DecisionModel\Enum\NodeType;
use Sakwa\Inference\Execution\Model\Generic;

class GenericTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleEvaluate()
    {
        $node = new BaseNode('foobar', NodeType::unknown);
        $iem = new Generic($node);

        $this->assertFalse($iem->evaluate());
        $this->assertFalse($iem->evaluate());
    }
}