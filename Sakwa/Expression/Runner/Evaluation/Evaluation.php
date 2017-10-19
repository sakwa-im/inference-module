<?php

namespace Sakwa\Expression\Runner\Evaluation;

use Sakwa\Exception;
use Sakwa\Expression\Runner\Evaluation\Value;
use Sakwa\Logging\LogTrait;

/**
 * Calculates the result by passed values and operator.
 */
class Evaluation extends Base
{
    use LogTrait;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var string[]
     */
    protected $validOperators = array('+', '-', '/', '*', '^', '%', '=', '!', '>', '<', '==', '!=', '<=', '>=');

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
        $this->operator = $operator->getValue();
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

        switch ($this->operator) {
            case '+':
                if ($this->elementLeft->isLiteral() || $this->elementRight->isLiteral()) {
                    $result = new Value($elementLeft . $elementRight, Value::IS_LITERAL);
                } else {
                    $result = new Value($elementLeft + $elementRight);
                }
                break;
            case '-':
                $result = new Value($elementLeft - $elementRight);
                break;
            case '/':
                $result = new Value($elementLeft / $elementRight);
                break;
            case '*':
                $result = new Value($elementLeft * $elementRight);
                break;
            case '^':
                $result = new Value(pow($elementLeft, $elementRight));
                break;
            case '%':
                $result = new Value($elementLeft % $elementRight);
                break;
            case '!':
                $result = new Value($elementLeft != $elementRight);
                break;
            case '>':
                $result = new Value($elementLeft > $elementRight);
                break;
            case '<':
                $result = new Value($elementLeft < $elementRight);
                break;

            case '==':
                $result = new Value(($elementLeft == $elementRight), Value::IS_BOOLEAN);
                break;
            case '!=':
                $result = new Value(($elementLeft != $elementRight), Value::IS_BOOLEAN);
                break;
            case '>=':
                $result = new Value(($elementLeft >= $elementRight), Value::IS_BOOLEAN);
                break;
            case '<=':
                $result = new Value(($elementLeft <= $elementRight), Value::IS_BOOLEAN);
                break;
            default:
                $result = new Value(null);
                break;
        }

        return $result;
    }

    /**
     * Checks if the operator is in the list of valid operators.
     *
     * @return bool
     */
    protected function isValidOperator()
    {
        return in_array($this->operator, $this->validOperators);
    }

}