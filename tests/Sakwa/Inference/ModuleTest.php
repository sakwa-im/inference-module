<?php

namespace Test\Inference;

use Sakwa\Inference\Module\Factory;
use Sakwa\Inference\Module\Configuration;

class ModuleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToInferAnModel()
    {
        $configuration = new Configuration();
        $configuration->setDecisionModelUri('tests/Models/Tiny-expression.sdm');
        $im = Factory::createInferenceModule($configuration);

        $this->assertInstanceOf('\\Sakwa\\Inference\\Module', $im);

        $im->start();
    }
}