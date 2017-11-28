<?php

namespace Sakwa\Expression\Runner\Evaluation;

use Sakwa\Exception;

/**
 * Calculates the result by passed values and operator.
 */
class Assignment extends Base
{
    protected $operators = array(
        '=',
        '+=',
        '-=',
        '/=',
        '*=',
        '%='
    );

    /**
     * Returns the calculated result.
     *
     * @return Value
     * @throws Exception
     * @todo refactor naar gelijk model als de evaluation variant
     */
    public function evaluate()
    {
        if (!$this->isValidOperator()) {
            throw new Exception('Invalid operator for evaluation: "' . $this->operator . '"', \Sakwa\Expression\Runner::RUNNER_EXCEPTION_INVALID_OPERATOR);
        }

        /**
         * @var \Sakwa\Inference\State\Entity\Variable $leftEntity
         */
        $leftEntity = $this->elementLeft->getValue();

        switch ($this->operator) {
            case '=':
                $leftEntity->setValue($this->elementRight->getValue());
                break;
            case '+='://TODO: betere afhandleing voor literals
                $leftEntity->setValue($leftEntity->getValue() + $this->elementRight->getValue());
                break;
            case '-=':
                $leftEntity->setValue($leftEntity->getValue() - $this->elementRight->getValue());
                break;
            case '/=':
                $leftEntity->setValue($leftEntity->getValue() / $this->elementRight->getValue());
                break;
            case '*=':
                $leftEntity->setValue($leftEntity->getValue() * $this->elementRight->getValue());
                break;
            case '%=':
                $leftEntity->setValue($leftEntity->getValue() % $this->elementRight->getValue());
                break;
        }

        //TODO: we moeten ook literal en andere types kunnen verwerken...
        return new Value($leftEntity->getValue(), Value::IS_NUMERIC);

    }

    /**
     * Checks if the operator is in the list of valid operators.
     *
     * @return bool
     */
    protected function isValidOperator()
    {
        return in_array($this->operator, $this->operators);
    }
}