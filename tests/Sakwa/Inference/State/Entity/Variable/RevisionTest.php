<?php

namespace Tests\Sakwa\Inference\State\Entity\Variable;

use Sakwa\Inference\State\Entity\Variable\Revision;

class ContextTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToCastRevisionToString()
    {
        $revision = new Revision('123');
        $this->assertEquals('123', ((string)$revision));
    }
}