<?php

namespace Sakwa\Expression\Planner\Strategy;

use Sakwa\Expression\Parser\Element;

/**
 * Class DetectAssignmentOperator
 *
 * This optimiser class is used for detecting the difference between equals sign
 *
 * @package Sakwa\Expression\Planner\Strategy
 */
class DetectAssignmentOperatorIteration1 extends Base
{
    public function evaluate(\Sakwa\Expression\Parser\Element $element)
    {
        /**
         * @var Element[] $elementSet
         */
        $elementSet = array();

        /**
         * @var Element $currentElement
         */
        $currentElement;

        /**
         * @var Element $previousElement
         */
        $previousElement;

        /**
         * @var Element $previousPreviousElement
         */
        $previousPreviousElement;

        $childElements = $element->getChildren();

        for ($i = 0; $i < count($childElements); $i++) {
            $nextElement    = (isset($childElements[$i + 1])) ? $childElements[$i + 1] : null;
            $currentElement = $childElements[$i];

            if (!is_null($nextElement) && $nextElement->getElementType() == Element::TOKEN_LOGIC_OPERATOR && $nextElement->getToken() == '=' && in_array($currentElement->getToken(), array('/', '*', '%'))) {
                $i++;
                $currentElement->setToken($currentElement->getToken().$nextElement->getToken());
                $currentElement->setElementType(Element::TOKEN_ASSIGNMENT_OPERATOR);
            }

            $elementSet[] = $currentElement;
        }

        if (count($elementSet) > 0) {
            $element->setChildren($elementSet);
        }
    }
}