<?php

use Sakwa\Expression\Parser;

class ParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * @dataProvider dataProviderShouldEvaluateExpressionsCorrectly
     */
    public function shouldEvaluateExpressionsCorrectly($expression, $expectedResult)
    {
        $parser = new Parser();

        $parser->setExpression($expression);
        $parser->parseExpression();
        $element = $parser->getElement();
        $actualResult = $element->__toString();

        $this->assertEquals($expectedResult, $actualResult);
        $this->assertEquals($expression, $parser->getExpression());
    }

    public function dataProviderShouldEvaluateExpressionsCorrectly()
    {
        return array(
            array('1.0',                                        '1.0'),
            array('4+4+"4"+4+4',                                '4 + 4 + "4" + 4 + 4'),
            array('{4260c630-e31e-43d9-8814-60e06d6f243f}.add', '{4260c630-e31e-43d9-8814-60e06d6f243f} . add'),
            array('"ab\\"cd\\\\efg"',                           '"ab\\"cd\\\\efg"')
        );
    }
}