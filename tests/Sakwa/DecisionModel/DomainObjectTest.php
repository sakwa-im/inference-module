<?php

use Sakwa\Utils\Guid;
use Sakwa\Persistence\Record;
use Sakwa\DecisionModel\DomainObject;
use Sakwa\DecisionModel\Enum\NodeType;

class DomainObjectTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToConstructAnDomainObject()
    {
        $guid = new Guid();

        $record = new Record();
        $record->description = 'test foobar';
        $record->reference   = "$guid";

        $obj = new DomainObject('foobar');
        $obj->fill($record);

        $this->assertEquals(NodeType::DomainObject, $obj->getType());
    }
}