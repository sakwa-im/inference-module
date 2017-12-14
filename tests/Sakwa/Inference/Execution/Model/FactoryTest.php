<?php

namespace Tests\Inference\Execution\Model;

use Sakwa\Inference\Execution\Model\Factory;
use Sakwa\DecisionModel\BaseNode;
use Sakwa\DecisionModel\Enum\NodeType;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @dataProvider dataProviderShouldBeAbleToGetCorrectIEMObject
     */
    public function shouldBeAbleToGetCorrectIEMObject($objectType, $expectedResult)
    {
        $node = new BaseNode('foobar', $objectType);
        $iem = Factory::createModel($node);

        $this->assertInstanceOf($expectedResult, $iem);
    }

    public function dataProviderShouldBeAbleToGetCorrectIEMObject()
    {
        return array(
            array(NodeType::Branch,        '\\Sakwa\\Inference\\Execution\\Model\\Branch'),
            array(NodeType::Expression,    '\\Sakwa\\Inference\\Execution\\Model\\Expression'),
            array(NodeType::Root,          '\\Sakwa\\Inference\\Execution\\Model\\Root'),
            array(NodeType::VarDefinition, '\\Sakwa\\Inference\\Execution\\Model\\VariableDef'),
            array(NodeType::DomainObject,  '\\Sakwa\\Inference\\Execution\\Model\\DomainObject')
        );
    }
}