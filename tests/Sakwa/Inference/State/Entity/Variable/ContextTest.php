<?php

use Sakwa\Utils\Guid;
use Sakwa\Inference\State\Entity\Variable\Context;

class ContextTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToGetGuids()
    {
        $guid         = new Guid();
        $context      = new Guid();
        $cycleContext = new Guid();

        $obj = new Context($guid, $context, $cycleContext);

        $this->assertEquals($guid,         $obj->getGuid());
        $this->assertEquals($context,      $obj->getContext());
        $this->assertEquals($cycleContext, $obj->getCycleContext());
    }

    /**
     * @test
     */
    public function shouldBeAbleToCommitWithoutGuid()
    {
        $guid         = new Guid();
        $context      = new Guid();
        $cycleContext = new Guid();

        $obj = new Context($guid, $context, $cycleContext);
        $obj->setValue(42);

        $commitPoint = $obj->commit();

        $this->assertInstanceOf('\\Sakwa\\Inference\\State\Entity\Variable\CommitPoint', $commitPoint);
    }
}