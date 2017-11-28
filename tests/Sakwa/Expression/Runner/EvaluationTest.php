<?php

use Sakwa\Expression\Parser\Element;
use Sakwa\Expression\Runner\Evaluation\Evaluation;
use Sakwa\Expression\Runner\Evaluation\Value;

class EvaluationTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \Sakwa\Expression\Runner\Evaluation\Evaluation
     */
    protected $sit;

    /**
     * @test
     * @dataProvider dataProviderShouldEvaluateValidOperators
     */
    public function shouldCorrectlyEvaluateOperators($operator, $expectedResult)
    {
        $sit = new Evaluation(new Value(6), new Value(3), new Value(new Element(Element::TOKEN_OPERATOR, $operator), Value::IS_OPERATOR));
        $result = $sit->evaluate();
        $this->assertEquals($expectedResult, $result->getValue());
    }

    public function dataProviderShouldEvaluateValidOperators()
    {
        return array(
            array('+', 9),
            array('-', 3),
            array('/', 2),
            array('*', 18),
            array('^', 216),
            array('%', 0),
            array('==', false),
            array('!', true),
            array('>', true),
            array('>=', true),
            array('<', false),
            array('<=', false)
        );
    }

    /**
     * @test
     * @dataProvider dataProviderShouldNotEvaluateInvalidOperators
     * @expectedException \Sakwa\Exception
     */
    public function shouldNotEvaluateInvalidOperators($operator)
    {
        $sit = new Evaluation(new Value(1), new Value(2), new Value(new Element(Element::TOKEN_OPERATOR, $operator), Value::IS_OPERATOR));
        $sit->evaluate();
    }

    public function dataProviderShouldNotEvaluateInvalidOperators()
    {
        return array(
            array('', false),
            array(',', false),
            array('.', false),
            array('(', false),
        );
    }

}