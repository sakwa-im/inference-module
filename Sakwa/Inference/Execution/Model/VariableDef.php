<?php

namespace Sakwa\Inference\Execution\Model;

use Sakwa\Inference\State\Manager;

class VariableDef extends Base  {

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

            $cycleContext = null;
            if (isset($options['cycleContext'])) {
                $cycleContext = $options['cycleContext'];
            }

            $entityManager = Manager::getInstance();
            $entityManager->createVariable($this->node->getReference(), null, $cycleContext);

            return true;
        }
        return false;
    }

    /**
     * @return void
     */
    public function postEvaluate()
    {
        // should be overridden/implemented by subclass
    }
};