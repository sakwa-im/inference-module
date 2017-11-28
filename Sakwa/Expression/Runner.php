<?php

namespace Sakwa\Expression;

use Sakwa\Exception;
use Sakwa\Logging\LogTrait;
use Sakwa\Expression\Runner\Evaluation\Value as EvaluationValue;
use Sakwa\Expression\Runner\Evaluation\Factory;

class Runner {

    use LogTrait;

    const RUNNER_EXCEPTION_MISSING_IDENTIFIER_SUBSTITUTE = 1;
    const RUNNER_EXCEPTION_INVALID_OPERATOR = 2;

    /**
     * @var \Sakwa\Expression\Parser\Element $element
     */
    protected $element;

    /**
     * @var \Sakwa\Inference\State\Manager $entityManager
     */
    protected $entityManager;

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
     * @throws \Sakwa\Exception
     */
    protected function runElement(\Sakwa\Expression\Parser\Element $element)
    {
        $result = new EvaluationValue(null, EvaluationValue::IS_NULL);
        $children = $element->getChildren();
        $subEvaluation = array();

        foreach ($children as $child) {
            if ($child->getElementType() == \Sakwa\Expression\Parser\Element::TOKEN_GROUP) {
                $value = $this->runElement($child);
            } else {
                $value = $child->getValue();
            }

            // Handle expressions having only one value.
            if ($result->isNull()) {
                $result = $value;
            }

            // Split expressions into subexpressions having two values and an operator.
            $subEvaluation[] = $value;
            if (count($subEvaluation) == 3) {
                /**
                 * @var EvaluationValue $left
                 * @var EvaluationValue $operator
                 * @var EvaluationValue $right
                 */
                list($left, $operator, $right) = $subEvaluation;

                $handler = Factory::createEvaluationHandler($left, $right, $operator);
                $result  = $handler->evaluate();
                $subEvaluation = array($result);
            }
        }

        return $result;
    }
}