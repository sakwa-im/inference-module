<?php

namespace Sakwa\Expression\Planner\Strategy;

use Sakwa\Expression\Parser\Element;

/**
 * Class InterpretMinusOperator
 *
 * This optimiser class is used transforming two consecutive "-" or "+" signs to "--" or "++"
 *
 * @package Sakwa\Expression\Planner\Strategy
 */
class ProcessUnaryOperator extends Base
{
    public function evaluate(\Sakwa\Expression\Parser\Element $element)
    {
        /**
         * @var Element[] $elementSet
         */
        $elementSet = array();

        /**
         * @var Element[] $elementSet
         */
        $childElements = $element->getChildren();

        while (!is_null(($childElement = array_shift($childElements)))) {
            if ($childElement->getElementType() == Element::TOKEN_ASSIGNMENT_OPERATOR && $childElement->getToken() == '=') {
                if (count($elementSet) > 0 && $elementSet[count($elementSet) - 1]->getElementType() == Element::TOKEN_OPERATOR) {
                    $previousItem = array_pop($elementSet);
                    $childElement->setToken($previousItem->getToken() . $childElement->getToken());
                }
            }
            elseif ($childElement->getElementType() == Element::TOKEN_OPERATOR && in_array($childElement->getToken(), array('+', '-'))) {
                if (count($elementSet) > 0 && $elementSet[count($elementSet) - 1]->getToken() == $childElement->getToken()) {
                    $previousItem = array_pop($elementSet);
                    $childElement->setToken($previousItem->getToken().$childElement->getToken());
                }
            }
            elseif ($childElement->getElementType() == Element::TOKEN_GROUP) {
                $this->evaluate($childElement);
            }

            $elementSet[] = $childElement;
        }

        if (count($elementSet) > 0) {
            $element->setChildren($elementSet);
        }
    }
}