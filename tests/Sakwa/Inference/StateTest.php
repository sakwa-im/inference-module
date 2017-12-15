<?php

namespace Test\Inference;

use Sakwa\Inference\State;

class StateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToInferAnModel()
    {
        $contexts = State::getInstance()->getAllAvailableContexts();
        $this->assertInstanceOf('\\Sakwa\\Utils\\EntityList', $contexts);
    }
}