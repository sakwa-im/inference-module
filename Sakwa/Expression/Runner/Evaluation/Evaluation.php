<?php

namespace Sakwa\Expression\Runner\Evaluation;

use Sakwa\Exception;

/**
 * Calculates the result by passed values and operator.
 */
class Evaluation extends Base
{
    /**
     * @var string $operator
     */
    protected $operator;

    /**
     * @var array $operators
     */
    protected $operators = array(
        '+'  => 'adition',
        '-'  => 'minus',
        '/'  => 'devision',
        '*'  => 'multiplication',
        '^'  => 'pow',
        '%'  => 'modulus',
        '!'  => 'evaluateNotEqual',
        '>'  => 'lower',
        '<'  => 'greater',
        '==' => 'equals',
        '!=' => 'notEqual',
        '<=' => 'lowerThenEquals',
        '>=' => 'greaterThenEquals');

    /**
     * Evaluation constructor.
     *
     * @param Value $elementLeft
     * @param Value $elementRight
     * @param Value $operator
     */
    public function __construct($elementLeft, $elementRight, $operator)
    {
        parent::__construct($elementLeft, $elementRight);
        /**
         * @var \Sakwa\Expression\Parser\Element $element
         */
        $element = $operator->getValue();

        $this->operator = $element->getToken();
    }

    /**
     * Returns the calculated result.
     *
     * @return Value
     * @throws Exception
     */
    public function evaluate()
    {
        if (!$this->isValidOperator()) {
            throw new Exception('Invalid operator for evaluation: "' . $this->operator . '"', \Sakwa\Expression\Runner::RUNNER_EXCEPTION_INVALID_OPERATOR);
        }

        if ($this->elementLeft->isEntity()) {
            $elementLeft = $this->elementLeft->getValue()->getValue();
        } else {
            $elementLeft = $this->elementLeft->getValue();
        }
        if ($this->elementRight->isEntity()) {
            $elementRight = $this->elementRight->getValue()->getValue();
        } else {
            $elementRight = $this->elementRight->getValue();
        }

        self::debug("$elementLeft $this->operator $elementRight");

        $functionName = 'evaluate'.ucfirst($this->operators[$this->operator]);

        return $this->$functionName($elementLeft, $elementRight);
    }

    /**
     * Checks if the operator is in the list of valid operators.
     *
     * @return bool
     */
    protected function isValidOperator()
    {
        $operator_keys = array_keys($this->operators);
        return in_array($this->operator, $operator_keys);
    }


    protected function evaluateAdition($elementLeft, $elementRight)
    {
        if ($this->elementLeft->isLiteral() || $this->elementRight->isLiteral()) {
            return new Value($elementLeft . $elementRight, Value::IS_LITERAL);
        }
        return new Value($elementLeft + $elementRight);
    }

    protected function evaluateMinus($elementLeft, $elementRight)
    {
        return new Value($elementLeft - $elementRight);
    }

    protected function evaluateDevision($elementLeft, $elementRight)
    {
        return new Value($elementLeft / $elementRight);
    }

    protected function evaluateMultiplication($elementLeft, $elementRight)
    {
        return new Value($elementLeft * $elementRight);
    }

    protected function evaluatePow($elementLeft, $elementRight)
    {
        return new Value(pow($elementLeft, $elementRight));
    }

    protected function evaluateModulus($elementLeft, $elementRight)
    {
        return new Value($elementLeft % $elementRight);
    }

    protected function evaluateEquals($elementLeft, $elementRight)
    {
        return new Value(($elementLeft == $elementRight), Value::IS_BOOLEAN);
    }

    protected function evaluateLower($elementLeft, $elementRight)
    {
        return new Value($elementLeft < $elementRight);
    }

    protected function evaluateGreater($elementLeft, $elementRight)
    {
        return new Value($elementLeft > $elementRight);
    }

    protected function evaluateNotEqual($elementLeft, $elementRight)
    {
        return new Value(($elementLeft != $elementRight), Value::IS_BOOLEAN);
    }

    protected function evaluateLowerThenEquals($elementLeft, $elementRight)
    {
        return new Value(($elementLeft <= $elementRight), Value::IS_BOOLEAN);
    }

    protected function evaluateGreaterThenEquals($elementLeft, $elementRight)
    {
        return new Value(($elementLeft >= $elementRight), Value::IS_BOOLEAN);
    }

}