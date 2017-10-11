<?php

namespace Sakwa\Expression\Planner\Strategy;

use Sakwa\Expression\Parser\Element;

class ProcessInversionOperator extends Base {

    public function evaluate(\Sakwa\Expression\Parser\Element $element)
    {
        /**
         * @var Element[] $elementSet
         */
        $elementSet = array();

        /**
         * @var Element[] $elements
         */
        $elements = array();

        /**
         * @var Element $currentElement
         */
        $currentElement;

        /**
         * @var Element $nextElement
         */
        $nextElement;

        $childElements = $element->getChildren();

        for ($i = 0; $i < count($childElements); $i++) {
            $currentElement = (isset($childElements[$i])) ? $childElements[$i] : null;
            $nextElement    = ($i < count($childElements) - 1) ? $childElements[$i + 1] : null;

            if ($currentElement->getElementType() == Element::TOKEN_INVERT && !is_null($nextElement)) {
                $elements[] = new Element(Element::TOKEN_NUMBER, '-1');
                $elements[] = new Element(Element::TOKEN_OPERATOR, '*');
                $elements[] = $nextElement;

                $groupElement = new Element(Element::TOKEN_GROUP, '');
                $groupElement->setChildren($elements);
                $elementSet[] = $groupElement;

                $i++;
                continue;
            }
            elseif ($currentElement->getElementType() == Element::TOKEN_GROUP) {
                $this->evaluate($currentElement);
            }

            $elementSet[] = $currentElement;
        }

        if (count($elementSet)) {
            $element->setChildren($elementSet);
        }
    }
}