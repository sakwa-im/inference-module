<?php

use Sakwa\Expression\Parser;
use Sakwa\Expression\Planner;

class PlannerTest extends \PHPUnit\Framework\TestCase
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

        $planner = new Planner();
        $planner->setElement($parser->getElement());
        $planner->planExpression();
        $element = $planner->getElement();

        $actualResult = $element->__toString();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function dataProviderShouldEvaluateExpressionsCorrectly()
    {
        return array(
            array('{4260c630-e31e-43d9-8814-60e06d6f243f}.add(123,567+890+{4260c630-e31e-43d9-8814-60e06d6f243f}.get(1,2+3,4))=42', '({4260c630-e31e-43d9-8814-60e06d6f243f}.add(123, 567 + 890 + {4260c630-e31e-43d9-8814-60e06d6f243f}.get(1, 2 + 3, 4))) = 42'),
            array('{4260c630-e31e-43d9-8814-60e06d6f243f}.add(123, 567+890)', '{4260c630-e31e-43d9-8814-60e06d6f243f}.add(123, 567 + 890)'),
            array('6=2*{4260c630-e31e-43d9-8814-60e06d6f243f}+0',             '6 = ((2 * {4260c630-e31e-43d9-8814-60e06d6f243f}) + 0)'),
            array('a = 1.2 * 5',                                              'a = (1.2 * 5)'),
            array('4+4+"4"+4+4',                                              '(4 + 4) + "4" + (4 + 4)'),
            array('2^-(2+2)',                                                 '(2 ^ (-1 * (2 + 2)))'),
            array('a--',                                                      'a --'),
            array('1 ==== 1',                                                 '1 == 1'),
            array('1 - -1',                                                   '1 + 1'),
            array('(1 + 1) * 1',                                              '(1 + 1) * 1'),
            array('1 + (1 + 1) * 1',                                          '1 + ((1 + 1) * 1)'),
            array('1 * (1 + 1) + 1',                                          '(1 * (1 + 1)) + 1'),
            array('1 + "1" = "1" + 1',                                       '(1 + "1") = ("1" + 1)')
        );
    }
}