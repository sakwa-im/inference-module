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
     * Evaluation constructor.
     *
     * @param Value $elementLeft
     * @param Value $elementRight
     */
    public function __construct($elementLeft, $elementRight)
    {
        $this->elementLeft = $elementLeft;
        $this->elementRight = $elementRight;
    }
}