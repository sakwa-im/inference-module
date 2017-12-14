<?php

namespace Tests\Inference\Module;

use Sakwa\Inference\Module\Factory;
use Sakwa\Inference\Module\Configuration;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToCreateInferenceModule()
    {
        $configuration = new Configuration();
        $configuration->setPersistenceDriver('test');

        $data = array(array('class-type'      => 'sakwa.IRootNodeImpl',
                            'reference'       => '99fd2891-ea1e-46b4-9dfa-69645534ee9d',
                            'count'           => 1,
                            'node-type'       => 'Root',
                            'name'            => 'Tiny-model',
                            'description'     => '',
                            'domain-template' => 'templates\Domain template.sdt'),
                      array('class-type'      => 'sakwa.IBaseNodeImpl',
                            'reference'       => '4736f41c-3fd4-4e54-a344-10f306e7fc30',
                            'count'           => 0,
                            'node-type'       => 'VariableDefinitions',
                            'name'            => 'Variable definitions',
                            'description'     => ''));

        $im = Factory::createInferenceModule($configuration, $data);

        $this->assertInstanceOf('\\Sakwa\\Inference\\Module', $im);
    }

    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function souldThrowExceptionWhenOpeningNonexistentFile()
    {
        $configuration = new Configuration();
        $im = Factory::createInferenceModule($configuration);
    }

    /**
     * @test
     */
    public function souldBeAbleToOpenFile()
    {
        $configuration = new Configuration();
        $configuration->setDecisionModelUri('tests/Models/Tiny-expression.sdm');
        $im = Factory::createInferenceModule($configuration);

        $this->assertInstanceOf('\\Sakwa\\Inference\\Module', $im);
    }
}