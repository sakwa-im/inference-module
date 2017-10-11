<?php

namespace Sakwa\Inference\Execution\Model;

use Sakwa\Inference\State\Manager;

class DomainObject extends Base  {

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


            $entityManager = Manager::getInstance();
            $domainObject = $entityManager->createDomainObject($this->node->getReference());

            $options = array('cycleContext' => $domainObject->getCycleContext());

            foreach ($this->node->getChildren() as $childNode) {
                $executionModel = Factory::createModel($childNode);

                if($executionModel instanceof \Sakwa\Inference\Execution\Model\VariableDef || $executionModel instanceof \Sakwa\Inference\Execution\Model\DomainObject) {
                    $executionModel->evaluate($options);

                    $domainObject->addChildEntity($entityManager->getEntity($childNode->getReference()));
                }
            }
        }
        return false;
    }
};