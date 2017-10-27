<?php

namespace Sakwa\Expression\Runner\Evaluation;

use Sakwa\Exception;

/**
 * Calculates the result by passed values and operator.
 */
class Assignment extends Base
{
    /**
     * Returns the calculated result.
     *
     * @return Value
     * @throws Exception
     */
    public function evaluate()
    {
        /**
         * @var \Sakwa\Inference\State\Entity\Variable $leftEntity
         */
        $leftEntity = $this->elementLeft->getValue();
        $leftEntity->setValue($this->elementRight->getValue());

        return $this->elementRight;
    }
}