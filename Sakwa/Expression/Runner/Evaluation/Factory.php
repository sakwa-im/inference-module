<?php

namespace Sakwa\Expression\Runner\Evaluation;

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
     */
    public static function createEvaluationHandler(Value $elementLeft, Value $elementRight, Value $operator)
    {
        //TODO: fix check op character =
        if ($operator->getValue() == '=') {
            return new Assignment($elementLeft, $elementRight);
        }
        else {
            return new Evaluation($elementLeft, $elementRight, $operator);
        }
    }
}