<?php

namespace Tests\Sakwa\Inference\Execution\Model;

use Sakwa\DecisionModel\BaseNode;
use Sakwa\DecisionModel\Enum\NodeType;
use Sakwa\Inference\Execution\Model\VariableDef;
use Sakwa\Utils\Guid;
use Sakwa\Utils\Registry;
use Sakwa\Inference\State;

class VariableDefTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleEvaluate()
    {
        $node = new BaseNode('foobar', NodeType::unknown);
        $node->setGuid(new Guid('44444444-dddd-5555-eeee-666666666666'));
        $node->setReference(new Guid('44444444-dddd-5555-eeee-666666666666'));
        $iem = new VariableDef($node);

        $decisionModel = new \Sakwa\DecisionModel\VariableDef('foobar');
        $decisionModel->setGuid(new Guid('44444444-dddd-5555-eeee-666666666666'));

        Registry::set('decisionModel', $decisionModel, State::getInstance()->getContext());

        \Sakwa\Utils\Registry::getInstance(new Guid('44444444-dddd-5555-eeee-666666666666'))->addKey(new Guid('44444444-dddd-5555-eeee-666666666666'), $decisionModel);

        $this->assertTrue($iem->evaluate(array('cycleContext' => new Guid())));
        $this->assertFalse($iem->evaluate());
    }
}