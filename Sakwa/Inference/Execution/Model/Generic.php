<?php

namespace Sakwa\Inference\Execution\Model;

class Generic extends Base  {

    /**
     * @var boolean
     */
    protected $node_is_evaluated = false;

    /**
     * @param array $options
     * @return bool
     */
    public function evaluate($options = array())
    {
        if (!$this->node_is_evaluated) {
            $this->node_is_evaluated = true;

            return (count($this->node->getChildren()) > 0);
        }
        return false;
    }
};