<?php

namespace Tests\Inference\Execution\Model;

use Sakwa\Inference\Module\Factory;
use Sakwa\Inference\Module\Configuration;

class BranchTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldThrowExceptionWhenInvalidBranchExpressionIsUsed()
    {
        $configuration = new Configuration();
        $configuration->setDecisionModelUri('tests/Models/InvalidBranchExpression.sdm');
        $im = Factory::createInferenceModule($configuration);
        $im->start();
    }
}