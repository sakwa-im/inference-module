<?php

use Sakwa\Expression\Parser\Element\Base;
use Sakwa\Utils\Guid;
use Sakwa\Persistence\Record;
use Sakwa\DecisionModel\BaseNode;
use Sakwa\DecisionModel\Enum\NodeType;

class BaseNodeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToConstructAnExpressionObject()
    {
        $guid = new Guid();

        $record = new Record();
        $record->expression  = '1+1';
        $record->description = 'test foobar';
        $record->reference   = new Guid();

        $obj1 = new BaseNode('foobar', NodeType::Branch);
        $obj1->fill($record);
        $obj1->setGuid($guid);

        $this->assertEquals('foobar', $obj1->getName());
        $this->assertEquals($record->reference, "{$obj1->getReference()}");
        $this->assertCount(0, $obj1->getChildren());
        $this->assertFalse($obj1->hasChildren());
        $this->assertInstanceOf('\Sakwa\Utils\Guid', $obj1->getGuid());


        $child1 = new BaseNode('child1', NodeType::Branch);
        $child1->setGuid($guid);
        $record->reference = new Guid();
        $child1->fill($record);

        \Sakwa\Utils\Registry::getInstance($guid)->addKey($child1->getReference(), $child1);

        $obj1->addChild($child1);
        $this->assertCount(1, $obj1->getChildren());
        $obj1->removeChild($child1);
        $this->assertCount(0, $obj1->getChildren());

        \Sakwa\Utils\Registry::getInstance($guid)->addKey($obj1->getReference(), $obj1);

        $obj1->addChild($child1);
        $child1->setParent($obj1);
        $this->assertCount(1, $obj1->getChildren());

        $this->assertInstanceOf('\Sakwa\DecisionModel\BaseNode', $child1->getParent());
    }

    /**
     * @test
     */
    public function shouldBeAbleToGenerateGuid()
    {
        $obj = new BaseNode('foobar');
        $this->assertInstanceOf('\Sakwa\Utils\Guid', $obj->getGuid());
    }
}