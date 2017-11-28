<?php

namespace Sakwa\Expression\Runner\Evaluation;

use Sakwa\Expression\Parser\Element;

/**
 * Calculates the result by passed values and operator.
 */
class Factory
{
    /**
     * Evaluation constructor.
     *
     * @param Value $elementLeft
     * @param Value $elementRight
     * @param Value $operator
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Base
     */
    public static function createEvaluationHandler(Value $elementLeft, Value $elementRight, Value $operator)
    {
        /**
         * @var \Sakwa\Expression\Parser\Element $element
         */
        $element = $operator->getValue();

        if ($element->getElementType() == Element::TOKEN_ASSIGNMENT_OPERATOR) {
            return new Assignment($elementLeft, $elementRight, $operator);
        }
        else {
            return new Evaluation($elementLeft, $elementRight, $operator);
        }
    }
}