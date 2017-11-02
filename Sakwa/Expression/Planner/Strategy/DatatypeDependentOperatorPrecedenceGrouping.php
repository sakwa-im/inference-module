<?php

namespace Sakwa\Expression\Planner\Strategy;

use Sakwa\Expression\Parser\Element;

/**
 * Class DatatypeDependentOperatorPrecedenceGrouping
 *
 * This optimiser class is used to ensure 4 + 4 + "4" + 4 + 4 will answer "848"
 *
 * @package Sakwa\Expression\Planner\Strategy
 */
class DatatypeDependentOperatorPrecedenceGrouping extends Base
{
    public function evaluate(\Sakwa\Expression\Parser\Element $element)
    {
        /**
         * @var Element[] $elementSet
         */
        $elementSet = array();

        /**
         * @var Element $elementCarry
         */
        $elementCarry = null;

        /**
         * @var Element[] $elementGroups
         */
        $elementGroups = array();

        foreach ($element->getChildren() as $childElement) {
            if ($childElement->getElementType() == Element::TOKEN_LITERAL && count($elementSet) > 0) {
                if ($elementSet[0]->isElementTypeOfTheOperatorFamily()) {
                    $elementGroups[] = array_shift($elementSet);
                }
                if ($elementSet[count($elementSet) - 1]->isElementTypeOfTheOperatorFamily()) {
                    $elementCarry = array_pop($elementSet);
                }

                if(count($elementSet) > 0) {
                    if (count($elementSet) > 1) {
                        $groupElement = new Element(Element::TOKEN_GROUP, '');
                        $groupElement->setChildren($elementSet);
                        $elementGroups[] = $groupElement;
                    }
                    else {
                        $elementGroups[] = $elementSet[0];
                    }
                }

                if(!is_null($elementCarry)) {
                    $elementGroups[] = $elementCarry;
                    $elementCarry = null;
                }

                $elementSet = array();
                $elementGroups[] = $childElement;
                continue;
            }
            elseif ($childElement->getElementType() == Element::TOKEN_GROUP) {
                $this->evaluate($childElement);
            }

            $elementSet[] = $childElement;
        }

        if(count($elementSet) > 0 && count($elementGroups) > 0) {
            if ($elementSet[0]->isElementTypeOfTheOperatorFamily()) {
                $elementGroups[] = array_shift($elementSet);
            }

            if (count($elementSet) > 0) {
                if (count($elementSet) > 1) {
                    $groupElement = new Element(Element::TOKEN_GROUP, '');
                    $groupElement->setChildren($elementSet);
                    $elementGroups[] = $groupElement;
                }
                else {
                    $elementGroups[] = $elementSet[0];
                }
            }
        }

        if (count($elementGroups) > 0) {
            $element->setChildren($elementGroups);
        }
    }
}