<?php

namespace Sakwa\Expression\Planner\Strategy;

use Sakwa\Expression\Parser\Element;
use Sakwa\Expression\Parser\Element\FunctionCall;

/**
 * Class FunctionCallLogicalGrouping
 *
 * This optimiser class is used for extracting plugin function calls from expressions
 *
 * @package Sakwa\Expression\Planner\Strategy
 */
class LibraryFunctionCall extends Base
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
            if ($childElement->getElementType() == Element::TOKEN_IDENTIFIER && count($childElements) > 0 && $childElements[0]->getElementType() == Element::TOKEN_GROUP) {

                $childElement = new FunctionCall($childElement);
                $group = array_shift($childElements);

                if ($group->hasChildren()) {
                    $parameters = $this->splitParameterExpressions($group->getChildren());

                    foreach ($parameters as $parameter) {
                        $this->evaluate($parameter);
                    }

                    $childElement->setChildren($parameters);
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

    /**
     * @param \Sakwa\Expression\Parser\Element[] $childElements
     * @return \Sakwa\Expression\Parser\Element[]
     */
    protected function splitParameterExpressions($childElements)
    {
        /**
         * @var Element[] $elementGroups
         */
        $elementGroups = array();

        /**
         * @var Element[] $elementSet
         */
        $elementSet = array();

        foreach($childElements as $childElement) {
            if ($childElement->getElementType() == Element::TOKEN_PARAMETER_SEPARATOR) {
                if (count($elementSet) > 0) {
                    $groupElement = new Element(Element::TOKEN_PARAMETER, '');
                    $groupElement->setChildren($elementSet);
                    $elementGroups[] = $groupElement;

                    $elementSet = array();
                }
            }
            else {
                $elementSet[] = $childElement;
            }
        }

        if (count($elementSet) > 0) {
            $groupElement = new Element(Element::TOKEN_PARAMETER, '');
            $groupElement->setChildren($elementSet);
            $elementGroups[] = $groupElement;
        }

        return $elementGroups;
    }
}