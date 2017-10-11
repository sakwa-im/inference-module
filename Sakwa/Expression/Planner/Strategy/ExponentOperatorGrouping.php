<?php

namespace Sakwa\Expression\Planner\Strategy;

use Sakwa\Exception;
use Sakwa\Expression\Parser\Element;

class ExponentOperatorGrouping extends Base {

    public function evaluate(\Sakwa\Expression\Parser\Element $element)
    {
        /**
         * @var Element[] $operatorGroup
         */
        $operatorGroup = array();

        /**
         * @var Element[] $elementSet
         */
        $elementSet = array();

        foreach ($element->getChildren() as $childElement) {
            if (count($operatorGroup) > 0) {
                if ($childElement->getElementType() == Element::TOKEN_GROUP) {
                    $this->evaluate($childElement);
                }
                $operatorGroup[] = $childElement;

                if ($childElement->getElementType() != Element::TOKEN_INVERT) {
                    $groupElement = new Element(Element::TOKEN_GROUP, '');
                    $groupElement->setChildren($operatorGroup);
                    $elementSet[] = $groupElement;

                    $operatorGroup = array();
                }
                continue;
            }
            elseif ($childElement->getElementType() == Element::TOKEN_OPERATOR && $childElement->getToken() =='^' && count($elementSet) > 0 && count($element->getChildren()) > 3) {
                $operatorGroup = array();
                $operatorGroup[] = array_pop($elementSet);
                $operatorGroup[] = $childElement;
                continue;
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