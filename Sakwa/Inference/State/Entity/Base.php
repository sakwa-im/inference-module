<?php

namespace Sakwa\Inference\State\Entity;

use Sakwa\Utils\Guid;
use Sakwa\Inference\State\Manager;
use Sakwa\Exception;
use Sakwa\Utils\Registry;
use Sakwa\Inference\State;

abstract class Base
{
    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $guid;

    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $context = null;

    public function __construct(\Sakwa\Utils\Guid $guid = null)
    {
        if (!is_null($guid)) {
            $this->setGuid($guid);
        } else {
            $this->setGuid((new Guid()));
        }

        $this->registerInVariableEntityManager();

        $this->initialize();
    }

    /**
     * Function for initialising the variable entity
     */
    protected function initialize()
    {
        //This function should be implemented in the sub classes
    }

    /**
     * Function for reinitializing the variable instance
     */
    public function reInitialize()
    {
        //This function should be implemented in the sub classes
    }

    /**
     * Function for retrieving the
     *
     * @return \Sakwa\Inference\State\Manager
     */
    protected function getVariableEntityManager()
    {
        return Manager::getInstance($this->context);
    }

    /**
     * Function for registering this variable in the Manager
     */
    public function registerInVariableEntityManager()
    {
        $this->getVariableEntityManager()->addEntity($this);
    }

    /**
     * Function for setting the variable state context
     * @param \Sakwa\Utils\Guid $context
     * @throws \Sakwa\Exception
     */
    public function setContext(\Sakwa\Utils\Guid $context)
    {
        if (!is_null($this->context)) {
            if ($this->context->is($context)) {
                return;
            }

            throw new Exception("It's not allowed to change the context one it has been set!");
        }
        $this->context = $context;
    }

    /**
     * Function for returning the variable state context
     * @return \Sakwa\Utils\Guid
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Function for setting the GUID
     * @param \Sakwa\Utils\Guid $guid
     */
    protected function setGuid(Guid $guid)
    {
        $this->guid = $guid;
    }

    /**
     * Function for getting the GUID
     * @return \Sakwa\Utils\Guid
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Function for loading the data definition for this entity
     * @return \Sakwa\DecisionModel\VariableDef
     */
    public function getDataDefinition()
    {
        /**
         * @var \Sakwa\DecisionModel\DecisionTree $dataDefinition
         */
        $dataDefinition = Registry::get('decisionModel', State::getInstance()->getContext());
        return $dataDefinition->findNode($this->getGuid());
    }


    /**
     * Function for creating/switching the cycleContext
     * @param \Sakwa\Utils\Guid $cycleContext
     */
    abstract public function switchCycleContext(\Sakwa\Utils\Guid $cycleContext);

    /**
     * Function for setting a new value on this variable
     * @param mixed $value
     */
    abstract public function setValue($value);

    /**
     * Function for returning the value of this variable
     * @return mixed
     */
    abstract public function getValue();

    /**
     * Function for setting the value of a domain object variable
     * @param \Sakwa\Utils\Guid $guid
     * @param mixed $value
     */
    abstract public function setVariableValue(\Sakwa\Utils\Guid $guid, $value);

    /**
     * Function for deferred calling of functions on Domain objects
     * @param string $methodName
     * @param array $arguments
     * @param boolean $deferred true if this function should be executed when reaching a leaf node
     * @return null|\Sakwa\Inference\State\Entity\DomainObject\MethodCall
     */
    abstract public function callMethod($methodName, $arguments = array(), $deferred = false);

    /**
     * Function for executing all deferred method calls
     * @return array MethodCall[]
     */
    abstract public function executeDeferredMethodCalls();

    /**
     * Function for committing the current variable state
     * @param \Sakwa\Utils\Guid $commit_point GUID for this commit point
     * @return \Sakwa\Utils\Guid
     */
    abstract public function commit(\Sakwa\Utils\Guid $commit_point = null);

    /**
     * Function for reverting variable value to given commit point
     * @param \Sakwa\Utils\Guid $commit_point
     * @return boolean true when the variable is reverted
     */
    abstract public function revert(\Sakwa\Utils\Guid $commit_point);

    /**
     * Function for accepting variable values als definitive
     * @param \Sakwa\Utils\Guid $commit_point
     */
    abstract public function push(\Sakwa\Utils\Guid $commit_point);
}