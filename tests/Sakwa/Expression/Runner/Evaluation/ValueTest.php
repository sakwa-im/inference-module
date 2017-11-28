<?php

use Sakwa\Expression\Runner\Evaluation\Value;
use Sakwa\Expression\Parser\Element;


class ValueTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function valueShouldBeAbleToBeNULL()
    {
        $value = new Value(null, Value::IS_NULL);
        $this->assertTrue($value->isNull());
    }

    /**
     * @test
     */
    public function valueShouldBeAbleToBeNumeric()
    {
        $value = new Value(1, Value::IS_NUMERIC);
        $this->assertTrue($value->isNumeric());
    }
    /**
     * @test
     */
    public function valueShouldBeAbleToBeLiteral()
    {
        $value = new Value('abc', Value::IS_LITERAL);
        $this->assertTrue($value->isLiteral());
    }

    /**
     * @test
     */
    public function valueShouldBeAbleToBeBoolean()
    {
        $value = new Value(false, Value::IS_BOOLEAN);
        $this->assertTrue($value->isBoolean());
    }

    /**
     * @test
     */
    public function valueShouldBeAbleToBeEntity()
    {
        $value = new Value(new stdClass(), Value::IS_ENTITY);
        $this->assertTrue($value->isEntity());
    }

    /**
     * @test
     */
    public function valueShouldBeAbleToBeOperator()
    {
        $value = new Value(new Element(Element::TOKEN_OPERATOR, '-'), Value::IS_OPERATOR);
        $this->assertTrue($value->isOperator());
    }

    /**
     * @test
     */
    public function valueShouldBeAbleToBeDate()
    {
        $value = new Value('13-05-1986', Value::IS_DATE);
        $this->assertTrue($value->isDate());
    }

    /**
     * @test
     */
    public function shouldNotBeAbleToSetValue()
    {
        $value = new Value(null, Value::IS_NULL);
        $value->setValue(123);

        $this->assertTrue($value->isNull());
    }

    /**
     * @test
     */
    public function shouldBeAbleToSetValue()
    {
        $value = new Value(123, Value::IS_NUMERIC);
        $value->setValue(456);

        $this->assertTrue($value->isNumeric());
        $this->assertEquals(456, $value->getValue());
    }
}