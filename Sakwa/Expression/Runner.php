<?php

namespace Sakwa\Expression;

use Sakwa\Exception;
use Sakwa\Logging\LogTrait;
use Sakwa\Expression\Runner\Evaluation\Value as EvaluationValue;
use Sakwa\Expression\Parser\Element;

class Runner {

    use LogTrait;

    const RUNNER_EXCEPTION_MISSING_IDENTIFIER_SUBSTITUTE = 1;
    const RUNNER_EXCEPTION_INVALID_OPERATOR = 2;

    /**
     * @var array
     */
    protected $entities = array();

    /**
     * @var \Sakwa\Expression\Parser\Element $element
     */
    protected $element;

    /**
     * Fuction for setting entitys
     * @param string $entityName
     * @param mixed $entityValue
     */
    public function createEntity($entityName, $entityValue)
    {
        $this->entities[$entityName] = $entityValue;
    }

    /**
     * @param \Sakwa\Expression\Parser\Element $element
     */
    public function setElement(\Sakwa\Expression\Parser\Element $element)
    {
        $this->element = $element;
    }

    /**
     * Returns the evaluation result
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    public function run()
    {
        $result = new EvaluationValue(null, EvaluationValue::IS_NULL);

        try {
            $result = $this->runElement($this->element);
        } catch (Exception $e) {
            self::error($e->getMessage(), $e);
        }

        return $result;
    }

    /**
     * @param \Sakwa\Expression\Parser\Element $element $element
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    protected function runElement(\Sakwa\Expression\Parser\Element $element)
    {
        $result = new EvaluationValue(null, EvaluationValue::IS_NULL);
        $children = $element->getChildren();
        $subEvaluation = array();

        foreach ($children as $child) {
            if ($child->getElementType() == \Sakwa\Expression\Parser\Element::TOKEN_FUNCTION_CALL) {
                $value = $child->evaluate();
            } elseif ($child->getElementType() == \Sakwa\Expression\Parser\Element::TOKEN_VARIABLE_IDENTIFIER) {
                $value = new EvaluationValue($child->getEntity(), EvaluationValue::IS_ENTITY);
            } elseif ($child->getElementType() == \Sakwa\Expression\Parser\Element::TOKEN_GROUP) {
                $value = $this->runElement($child);
            } elseif (in_array($child->getElementType(), array(\Sakwa\Expression\Parser\Element::TOKEN_OPERATOR, \Sakwa\Expression\Parser\Element::TOKEN_LOGIC_OPERATOR))) {
                $value = new EvaluationValue($child->getToken(), EvaluationValue::IS_OPERATOR);
            } else {
                $value = new EvaluationValue($child->getToken(), (($child->getElementType() == Element::TOKEN_LITERAL) ? EvaluationValue::IS_LITERAL : EvaluationValue::IS_NUMERIC));
            }

            // Handle expressions having only one value.
            if ($result->isNull()) {
                $result = $value;
            }

            // Split expressions into subexpressions having two values and an operator.
            $subEvaluation[] = $value;
            if (count($subEvaluation) == 3) {
                $evaluation = new \Sakwa\Expression\Runner\Evaluation($subEvaluation[0], $subEvaluation[2], $subEvaluation[1]);
                $result = $evaluation->evaluate();

                $subEvaluation = array($result);
            }
        }

        return $result;
    }
}