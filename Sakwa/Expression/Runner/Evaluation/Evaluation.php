<?php

namespace Sakwa\Expression\Runner\Evaluation;

use Sakwa\Exception;

/**
 * Calculates the result by passed values and operator.
 */
class Evaluation extends Base
{

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
        '!'  => 'notEqual',
        '>'  => 'greater',
        '<'  => 'lower',
        '==' => 'equals',
        '!=' => 'notEqual',
        '<=' => 'lowerThenEquals',
        '>=' => 'greaterThenEquals');

    /**
     * Returns the calculated result.
     *
     * @return Value
     * @throws \Sakwa\Exception
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

    /**
     * Function for adding\concatenating the left and right values
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluateAdition($elementLeft, $elementRight)
    {
        if ($this->elementLeft->isLiteral() || $this->elementRight->isLiteral()) {
            return new Value($elementLeft . $elementRight, Value::IS_LITERAL);
        }
        return new Value($elementLeft + $elementRight);
    }

    /**
     * Function for subtracting the right value from the left value
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluateMinus($elementLeft, $elementRight)
    {
        return new Value($elementLeft - $elementRight);
    }

    /**
     * Function for dividing the left value with the right value
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluateDevision($elementLeft, $elementRight)
    {
        return new Value($elementLeft / $elementRight);
    }

    /**
     * Function for multiplying the left value with the right value
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluateMultiplication($elementLeft, $elementRight)
    {
        return new Value($elementLeft * $elementRight);
    }

    /**
     * This function calculates the exponent of the left value
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluatePow($elementLeft, $elementRight)
    {
        return new Value(pow($elementLeft, $elementRight));
    }

    /**
     * This function calculate the modulus of the left value based on the right value
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluateModulus($elementLeft, $elementRight)
    {
        return new Value($elementLeft % $elementRight);
    }

    /**
     * Function for evaluating if the left value is equal to the right value, function returns a boolean value
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluateEquals($elementLeft, $elementRight)
    {
        return new Value(($elementLeft == $elementRight), Value::IS_BOOLEAN);
    }

    /**
     * Function for evaluating if the right value is lover then the left value, function returns a boolean value
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluateLower($elementLeft, $elementRight)
    {
        return new Value($elementLeft < $elementRight);
    }

    /**
     * Function for evaluating if the right value is greater then the left value, function returns a boolean value
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluateGreater($elementLeft, $elementRight)
    {
        return new Value($elementLeft > $elementRight);
    }

    /**
     * Function for evaluating if the left value is not equal to the right value, function returns a boolean value
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluateNotEqual($elementLeft, $elementRight)
    {
        return new Value(($elementLeft != $elementRight), Value::IS_BOOLEAN);
    }

    /**
     * Function for evaluating if the right value is lover then or equal to the left value, function returns a boolean value
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluateLowerThenEquals($elementLeft, $elementRight)
    {
        return new Value(($elementLeft <= $elementRight), Value::IS_BOOLEAN);
    }

    /**
     * Function for evaluating if the right value is greater then or equal to the left value, function returns a boolean value
     *
     * @param mixed $elementLeft
     * @param mixed $elementRight
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function evaluateGreaterThenEquals($elementLeft, $elementRight)
    {
        return new Value(($elementLeft >= $elementRight), Value::IS_BOOLEAN);
    }
}