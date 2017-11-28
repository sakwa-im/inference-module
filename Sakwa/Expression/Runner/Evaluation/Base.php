<?php

namespace Sakwa\Expression\Runner\Evaluation;

use Sakwa\Exception;
use Sakwa\Expression\Runner\Evaluation\Value;
use Sakwa\Logging\LogTrait;

abstract class Base
{
    use LogTrait;

    /**
     * @var Value
     */
    protected $elementLeft;

    /**
     * @var Value
     */
    protected $elementRight;

    /**
     * @var string $operator
     */
    protected $operator;

    /**
     * Evaluation constructor.
     *
     * @param Value $elementLeft
     * @param Value $elementRight
     * @param Value $operator
     */
    public function __construct($elementLeft, $elementRight, $operator)
    {
        $this->elementLeft = $elementLeft;
        $this->elementRight = $elementRight;

        /**
         * @var \Sakwa\Expression\Parser\Element $element
         */
        $element = $operator->getValue();

        $this->operator = $element->getToken();
    }

    /**
     * Returns the calculated result.
     *
     * @return Value
     * @throws Exception
     */
    public abstract function evaluate();
}