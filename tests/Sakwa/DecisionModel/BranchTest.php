<?php

use Sakwa\Utils\Guid;
use Sakwa\Persistence\Record;
use Sakwa\DecisionModel\Branch;
use Sakwa\DecisionModel\Enum\NodeType;
use Sakwa\DecisionModel\Enum\BranchEvaluation;

class BranchTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToConstruct()
    {
        $guid = new Guid();

        $record = new Record();
        $record->expression       = '1+1';
        $record->description      = 'test foobar';
        $record->reference        = "$guid";
        $record->branchEvaluation = 'Once';

        $obj = new Branch('foobar');
        $obj->fill($record);

        $this->assertEquals(NodeType::Branch, $obj->getType());
        $this->assertEquals('1+1', $obj->getExpression());
        $this->assertEquals(BranchEvaluation::once, $obj->getBranchEvaluation());
    }

    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldThrowExceptionWhenInvalidTypeIsUsed()
    {
        $guid = new Guid();

        $record = new Record();
        $record->expression       = '1+1';
        $record->description      = 'test foobar';
        $record->reference        = "$guid";
        $record->branchEvaluation = 'Bogus';

        $obj = new Branch('foobar');
        $obj->fill($record);
    }
}