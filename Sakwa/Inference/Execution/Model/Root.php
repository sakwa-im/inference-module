<?php

namespace Sakwa\Inference\Execution\Model;

class Root extends Base  {

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
        //TODO: implement set behaviour
        if (!$this->node_is_evaluated) {
            $this->node_is_evaluated = true;
            return true;
        }
        return false;
    }
}