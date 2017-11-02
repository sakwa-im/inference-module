<?php

namespace Sakwa\Expression\Planner\Strategy;

use Sakwa\Expression\Parser\Element;

/**
 * Class InterpretMinusOperator
 *
 * This optimiser class is used for interpreting the correct meening of the minus sign
 *
 * @package Sakwa\Expression\Planner\Strategy
 */
class InterpretMinusOperator extends Base
{
    public function evaluate(\Sakwa\Expression\Parser\Element $element)
    {
        /**
         * @var Element[] $elementSet
         */
        $elementSet = array();

        /**
         * @var Element[] $elementGroups
         */
        $elementGroups = array();

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

        for ($i = 1; $i <= count($childElements); $i++) {
            $nextElement     = (isset($childElements[$i + 1])) ? $childElements[$i + 1] : null;
            $currentElement  = (isset($childElements[$i])) ? $childElements[$i] : null;
            $previousElement = $childElements[$i - 1];

            if (!is_null($currentElement)) {
                switch ($currentElement->getElementType()) {
                    case Element::TOKEN_OPERATOR:
                        if ($currentElement->getToken() == '-' && $previousElement->getToken() == '-' && !is_null($nextElement)) {
                            $currentElement->setToken('+');
                            continue 2;
                        }
                        break;

                    case Element::TOKEN_NUMBER:
                    case Element::TOKEN_GROUP:
                        $previousPreviousElement = ($i > 1) ? $childElements[$i - 2] : null;

                        if ($previousElement->getElementType() == Element::TOKEN_OPERATOR && $previousElement->getToken() == '-') {
                            if (is_null($previousPreviousElement) || in_array($previousPreviousElement->getElementType(), array(Element::TOKEN_OPERATOR))) {
                                if ($currentElement->getElementType() == Element::TOKEN_NUMBER) {
                                    $currentElement->setToken($previousElement->getToken() . $currentElement->getToken());
                                }
                                else {
                                    $elementSet[] = new Element(Element::TOKEN_INVERT, '-1*');
                                }
                                continue 2;
                            }
                        }
                        break;
                }
            }

            if ($previousElement->getElementType() == Element::TOKEN_GROUP) {
                $this->evaluate($previousElement);
            }

            $elementSet[] = $previousElement;
        }

        if (count($elementSet)) {
            $element->setChildren($elementSet);
        }
    }
}