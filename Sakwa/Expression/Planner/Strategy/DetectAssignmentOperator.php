<?php

namespace Sakwa\Expression\Planner\Strategy;

use Sakwa\Expression\Parser\Element;

class DetectAssignmentOperator extends Base {

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
            if ($childElement->getElementType() == Element::TOKEN_LOGIC_OPERATOR) {
                $token = $childElement->getToken();

                if (in_array($token, array('+=', '-=', '*=', '/=', '%='))) {
                    $childElement->setElementType(Element::TOKEN_ASSIGNMENT_OPERATOR);
                }
                elseif ($token[0] == '=') {
                    if (strlen($token) == 1) {
                        $childElement->setElementType(Element::TOKEN_ASSIGNMENT_OPERATOR);
                    }
                    elseif ($token[1] == '=') {
                        $childElement->setToken('==');
                    }
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