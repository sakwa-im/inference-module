<?php

namespace Sakwa\Expression;

use Sakwa\Expression\Parser\Element;

class Planner
{

    /**
     * Precedence of rules is important in the parsing of expressions.
     *
     * @var string[] $rules
     */
    protected static $rules = array(
        '\Sakwa\Expression\Planner\Strategy\DetectAssignmentOperatorIteration1',
        '\Sakwa\Expression\Planner\Strategy\InterpretMinusOperator',
        '\Sakwa\Expression\Planner\Strategy\ExponentOperatorGrouping',
        '\Sakwa\Expression\Planner\Strategy\ArithmeticOperatorGrouping',
        '\Sakwa\Expression\Planner\Strategy\LogicOperatorGrouping',
        '\Sakwa\Expression\Planner\Strategy\ProcessInversionOperator',
        '\Sakwa\Expression\Planner\Strategy\DetectAssignmentOperatorIteration2',
        '\Sakwa\Expression\Planner\Strategy\ProcessUnaryOperator',
        '\Sakwa\Expression\Planner\Strategy\DatatypeDependentOperatorPrecedenceGrouping',
        '\Sakwa\Expression\Planner\Strategy\FunctionCallLogicalGrouping',
        '\Sakwa\Expression\Planner\Strategy\LibraryFunctionCall'
    );

    /**
     * @var \Sakwa\Expression\Parser\Element[] $elements
     */
    protected $elements = array();

    /**
     * @var \Sakwa\Expression\Parser\Element $element
     */
    protected $element;

    /**
     * @param \Sakwa\Expression\Parser\Element $element
     */
    public function setElement(\Sakwa\Expression\Parser\Element $element)
    {
        $this->element = $element;
    }

    public function planExpression()
    {
        foreach (self::$rules as $className) {
            /** @var \Sakwa\Expression\Planner\Strategy\Base $rule */
            $rule = new $className();
            $rule->evaluate($this->element);
        }
    }

    /**
     * Function for getting the root element for the parsed expression
     * @return \Sakwa\Expression\Parser\Element
     */
    public function getElement()
    {
        return $this->element;
    }
}