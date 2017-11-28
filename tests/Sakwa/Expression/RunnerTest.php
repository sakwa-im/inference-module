<?php

use Sakwa\Expression\Parser\Element;
use Sakwa\Expression\Runner;
use Sakwa\Utils\Registry;

class RunnerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldLogErrorWhenInvalidOperatorIsUsed()
    {
        $root = new Element(Element::TOKEN_ROOT, '');

        $expressionNodes = array();
        $expressionNodes[] = new Element(Element::TOKEN_NUMBER, 1);
        $expressionNodes[] = new Element(Element::TOKEN_OPERATOR, '$'); //Invalid operator
        $expressionNodes[] = new Element(Element::TOKEN_NUMBER, 1);

        $root->setChildren($expressionNodes);

        $runner = new Runner();
        $runner->setElement($root);
        $value = $runner->run();

        $this->assertNull($value->getValue());
        $this->assertTrue($value->isNull());
    }
}