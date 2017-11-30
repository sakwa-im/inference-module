<?php

use Sakwa\DecisionModel\DecisionTree;
use Sakwa\Persistence\TestPersistence;

class DecisionTreeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToConstructAnDeciscionModelObject()
    {
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

        $persistance = new TestPersistence($data);
        $obj = new DecisionTree($persistance);

        $this->assertInstanceOf('\Sakwa\Persistence\TestPersistence', $obj->getPersistence());

        $this->assertInstanceOf('\Sakwa\DecisionModel\BaseNode', $obj->retrieve());
        $this->assertInstanceOf('\Sakwa\DecisionModel\BaseNode', $obj->getRoot());
    }
}