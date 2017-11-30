<?php

use Sakwa\Utils\Guid;
use Sakwa\Persistence\Record;
use Sakwa\DecisionModel\Expression;
use Sakwa\DecisionModel\Enum\NodeType;

class ExpressionTest extends \PHPUnit\Framework\TestCase
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
        $record->reference   = "$guid";

        $obj = new Expression('foobar');
        $obj->fill($record);

        $this->assertEquals(NodeType::Expression, $obj->getType());
        $this->assertEquals('1+1', $obj->getExpression());
    }
}