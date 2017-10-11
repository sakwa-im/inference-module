<?php

namespace Sakwa\Inference\State\Entity;

use Sakwa\Inference\State\Entity\DomainObject\Context;
use Sakwa\Utils\EntityList;
use Sakwa\Logging\LogTrait;
use Sakwa\Exception;
use Sakwa\Utils\Guid;

class DomainObject extends Base
{
    use LogTrait;

    /**
     * @var \Sakwa\Inference\State\Entity\DomainObject\Context[] $contexts
     */
    protected $contexts = array();

    /**
     * @var \Sakwa\Utils\EntityList $entityReferences
     */
    protected $entityReferences;

    /**
     * @var \Sakwa\Utils\EntityList $cycleContexts
     */
    protected $cycleContexts;

    /**
     * @var \Sakwa\Utils\Guid $cycleContext
     */
    protected $cycleContext;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * DomainObject constructor.
     *
     * @param \Sakwa\Utils\Guid|null $guid
     * @param \Sakwa\Utils\Guid|null $cycleContext
     */
    public function __construct(\Sakwa\Utils\Guid $guid = null, \Sakwa\Utils\Guid $cycleContext = null)
    {
        parent::__construct($guid);

        $this->entityReferences = new EntityList();
        $this->cycleContexts    = new EntityList();

        if (is_null($cycleContext)) {
            $cycleContext = $this->getGuid();
        }

        $this->cycleContext = $cycleContext;
        $this->cycleContexts->add($this->cycleContext);
        $this->contexts[(string)$cycleContext] = new Context($this->getGuid(), $this->getContext(), $cycleContext);
    }

    /**
     * Function for getting the current cycle context
     * @return \Sakwa\Utils\Guid
     */
    public function getCycleContext()
    {
        return $this->cycleContext;
    }

    /**
     * Function for initialising the variable entity
     */
    protected function initialize()
    {
        $dataDefinition = $this->getDataDefinition();
        $this->description = $dataDefinition->getDescription();
        $this->initializeDomainObjectState();
    }

    /**
     * Function for reinitializing the variable instance
     */
    public function reInitialize()
    {
        foreach ($this->getChildEntities() as $entity) {
            $entity->reInitialize();
        }

        $this->initializeDomainObjectState();
    }

    /**
     * Function for initializing the variable state on this variable instance
     * @param boolean $isReInitialize = false
     */
    protected function initializeDomainObjectState($isReInitialize = false)
    {
        $dataDefinition = $this->getDataDefinition();
        //TODO: implement code here
    }

    /**
     * Get all child entities related to this DomainObject
     * @return \Sakwa\Inference\State\Entity\Base[]
     */
    public function getChildEntities()
    {
        $variableEntityManager = $this->getVariableEntityManager();

        $entities = array();

        foreach ($this->entityReferences as $guid) {
            $entities[] = $variableEntityManager->getEntity($guid);
        }

        return $entities;
    }

    /**
     * @param \Sakwa\Inference\State\Entity\Base $entity
     */
    public function addChildEntity(\Sakwa\Inference\State\Entity\Base $entity)
    {
        self::debug('Add child entity '.$entity->getGuid().' to '.$this->guid);

        $this->entityReferences[] = $entity->getGuid();

        //Create contexts for all available cycles
        foreach ($this->cycleContexts as $cycleContext) {
            $entity->switchCycleContext($cycleContext);
        }

        //Set cycle context to the current context
        $entity->switchCycleContext($this->cycleContext);
    }

    /**
     * Function for creating/switching the cycleContext
     * @param \Sakwa\Utils\Guid $cycleContext
     */
    public function switchCycleContext(\Sakwa\Utils\Guid $cycleContext)
    {
        self::debug('Switch cycleContext of '.$this->guid.' to: '.$cycleContext);

        foreach ($this->getChildEntities() as $entity) {
            $entity->switchCycleContext($cycleContext);
        }

        if (!$this->cycleContexts->has($cycleContext)) {
            $this->cycleContexts->add($cycleContext);
        }

        if (!isset($this->contexts[(string)$cycleContext])) {
            $this->contexts[(string)$cycleContext] = new Context($this->getGuid(), $this->getContext(), $cycleContext);
        }

        $this->cycleContext = $cycleContext;
    }

    /**
     * Function for returning the current cycleContext for this variable entity
     * @return \Sakwa\Inference\State\Entity\DomainObject\Context
     */
    protected function getCurrentContext()
    {
        if (is_null($this->cycleContext)) {
            $this->cycleContext = new Guid();

            $this->contexts[(string)$this->cycleContext] = new Context($this->getGuid(), $this->getContext(), $this->cycleContext);
        }

        return $this->contexts[(string)$this->cycleContext];
    }

    /**
     * Function for deferred calling of functions on Domain objects
     * @param string $methodName
     * @param array $arguments
     * @param boolean $deferred = false
     * @return null|\Sakwa\Inference\State\Entity\DomainObject\MethodCall
     */
    public function callMethod($methodName, $arguments = array(), $deferred = false)
    {
        self::debug('Call '.$methodName.' on'.$this->guid);

        return $this->getCurrentContext()->callMethod($methodName, $arguments, $deferred);
    }

    /**
     * Function for executing all deferred method calls
     * @return array \Sakwa\Inference\State\Entity\DomainObject\MethodCall[]
     */
    public function executeDeferredMethodCalls()
    {
        self::debug('Execute deferred method calls on'.$this->guid);

        return $this->getCurrentContext()->executeDeferredMethodCalls();
    }

    /**
     * Function for setting a new value on this variable
     * @param mixed $value
     * @throws \Sakwa\Exception
     */
    public function setValue($value)
    {
        throw new Exception('This function is not allowed on this type of object');
    }

    /**
     * Function for returning the value of this variable
     * @throws \Sakwa\Exception
     */
    public function getValue()
    {
        throw new Exception('This function is not allowed on this type of object');
    }

    /**
     * Function for setting the value of a domain object variable
     * @param \Sakwa\Utils\Guid $guid
     * @param mixed $value
     */
    public function setVariableValue(\Sakwa\Utils\Guid $guid, $value)
    {
        $entityManager = $this->getVariableEntityManager();
        $entityManager->setVariable($guid, $value);
    }

    /**
     * Function for committing the current variable state
     * @param \Sakwa\Utils\Guid $commit_point GUID for this commit point
     * @throws \Sakwa\Exception
     * @return null
     */
    public function commit(\Sakwa\Utils\Guid $commit_point = null)
    {
        throw new Exception('This function is not allowed on this type of object');
    }

    /**
     * Function for reverting variable value to given commit point
     * @param \Sakwa\Utils\Guid $commit_point
     * @throws \Sakwa\Exception
     * @return null
     */
    public function revert(\Sakwa\Utils\Guid $commit_point)
    {
        throw new Exception('This function is not allowed on this type of object');
    }

    /**
     * Function for accepting variable values als definitive
     * @param \Sakwa\Utils\Guid $commit_point
     * @throws \Sakwa\Exception
     */
    public function push(\Sakwa\Utils\Guid $commit_point)
    {
        throw new Exception('This function is not allowed on this type of object');
    }
}