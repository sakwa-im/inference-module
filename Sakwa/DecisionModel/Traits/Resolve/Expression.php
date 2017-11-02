<?php

namespace Sakwa\DecisionModel\Traits\Resolve;

/**
 * Trait VariableValue
 *
 * @package Sakwa\DecisionModel\Traits\Resolve
 */
trait Expression {

    /**
     * @var string
     */
    private $expression = '';


    /**
     * @param \Sakwa\Persistence\Record $record
     * @return void
     */
    protected function _fill(\Sakwa\Persistence\Record $record)
    {
        if (!is_null($record->expression) && $record->expression != '') {
            $this->expression = $record->expression;
        }
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }
}