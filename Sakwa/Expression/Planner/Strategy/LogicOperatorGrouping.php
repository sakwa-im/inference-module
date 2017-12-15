<?php

namespace Sakwa\Expression\Planner\Strategy;

use Sakwa\Expression\Parser\Element;

/**
 * Class FunctionCallLogicalGrouping
 *
 * This optimiser class is used for adding grouping to ensure the correct order of execution when using logic operators
 *
 * @package Sakwa\Expression\Planner\Strategy
 */
class LogicOperatorGrouping extends Base
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
            if ($childElement->getElementType() == Element::TOKEN_LOGIC_OPERATOR && count($elementSet) > 0) {
                if ($elementSet[0]->getElementType() == Element::TOKEN_OPERATOR) {
                    $elementGroups[] = array_shift($elementSet);
                }
                if (count($elementSet) > 0 && $elementSet[count($elementSet) - 1]->getElementType() == Element::TOKEN_OPERATOR) {
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
            if ($elementSet[0]->getElementType() == Element::TOKEN_OPERATOR) {
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