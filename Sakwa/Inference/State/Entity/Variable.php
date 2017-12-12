<?php

namespace Sakwa\Inference\State\Entity;

use Sakwa\Inference\State\Entity\Variable\Context;
use Sakwa\DecisionModel\Enum\InitializeMode;
use Sakwa\Data\Information\Module AS DIM;
use Sakwa\Logging\LogTrait;
use Sakwa\Utils\Guid;
use Sakwa\Exception;

class Variable extends Base
{
    use LogTrait;
    /**
     * @var \Sakwa\Inference\State\Entity\Variable\Context[] $contexts
     */
    protected $contexts = array();

    /**
     * @var \Sakwa\Utils\Guid $cycleContext
     */
    protected $cycleContext;

    /**
     * @var string|null $description
     */
    protected $description = null;

    /**
     * @param \Sakwa\Utils\Guid $guid
     * @param mixed $value
     * @param \Sakwa\Utils\Guid|null $cycleContext
     */
    public function __construct(\Sakwa\Utils\Guid $guid = null, $value = null, \Sakwa\Utils\Guid $cycleContext = null)
    {
        parent::__construct($guid);

        //Create a cycle context for this variable entity if needed
        if (!is_null($cycleContext)) {
            $this->switchCycleContext($cycleContext);
        }

        if (!is_null($value)) {
            $this->setValue($value);
        }
    }

    /**
     * Function for setting the variable state context
     * @param \Sakwa\Utils\Guid $context
     * @throws \Sakwa\Exception
     */
    public function setContext(\Sakwa\Utils\Guid $context)
    {
        parent::setContext($context);
    }

    /**
     * Function for initialising the variable entity
     */
    protected function initialize()
    {
        $dataDefinition = $this->getDataDefinition();
        $this->description = $dataDefinition->getDescription();
        $this->initializeVariableState();
    }

    /**
     * Function for reinitializing the variable instance
     */
    public function reInitialize()
    {
        $this->initializeVariableState(true);
    }

    /**
     * Function for initializing the variable state on this variable instance
     * @param boolean $isReInitialize = false
     */
    protected function initializeVariableState($isReInitialize = false)
    {
        $dataDefinition = $this->getDataDefinition();

        switch ($dataDefinition->getInitializeMode()) {
            //No action is required here.
            case InitializeMode::None:
                break;

            case InitializeMode::SessionStart:
                if (!$isReInitialize) {
                    $this->resolveValue();
                }
                break;

            case InitializeMode::SessionStartDefaultValue:
                if (!$isReInitialize) {
                    $this->setValue($dataDefinition->getValue());
                }
                break;
/*
            case InitializeMode::CycleStart:
                $this->switchCycleContext($cycleContext);
                $this->setValue(DIM::getInstance()->getVariableValue($this->getGuid()));
                break;

            case InitializeMode::CycleStartDefaultValue:
                $this->setValue($dataDefinition->getValue());
                break;*/
        }
    }

    /**
     * Function for creating/switching the cycleContext
     * @param \Sakwa\Utils\Guid $cycleContext
     */
    public function switchCycleContext(\Sakwa\Utils\Guid $cycleContext)
    {
        self::debug('Switch cycleContext of '.$this->guid.' to: '.$cycleContext);

        if (!isset($this->contexts[(string)$cycleContext])) {
            $this->contexts[(string)$cycleContext] = new Context($this->getGuid(), $this->getContext(), $cycleContext);
        }
        $this->cycleContext = $cycleContext;
    }

    /**
     * Function for returning the current cycleContext for this variable entity
     * @return \Sakwa\Inference\State\Entity\Variable\Context
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
     * Function for setting a new value on this variable
     * @param mixed $value
     */
    public function setValue($value)
    {
        self::debug('Set value of '.$this->guid.' to: '.$value);

        $this->getCurrentContext()->setValue($value);
    }

    /**
     * Function for returning the value of this variable
     * @return mixed
     */
    public function getValue()
    {
        if (!$this->getCurrentContext()->hasValue()) {
            $this->resolveValue();
        }

        //If we where able to resolve a value then we can return it
        return $this->getCurrentContext()->getValue();
    }

    /**
     * Function for resolving variable value using the dim
     */
    public function resolveValue()
    {
        $this->setValue(DIM::getInstance()->getVariableValue($this->getGuid()));
    }

    /**
     * Function for checking the dirty flag
     * @return boolean
     */
    public function isDirty()
    {
        return $this->getCurrentContext()->isDirty();
    }

    /**
     * Function for setting the value of a domain object variable
     * @param \Sakwa\Utils\Guid $guid
     * @param mixed $value
     * @throws \Sakwa\Exception
     * @return null
     */
    public function setVariableValue(\Sakwa\Utils\Guid $guid, $value)
    {
        throw new Exception('This function is not allowed on this type of object');
    }

    /**
     * Function for deferred calling of functions on Domain objects
     * @param string $methodName
     * @param array $arguments
     * @param boolean $deferred = false
     * @throws \Sakwa\Exception
     * @return null
     */
    public function callMethod($methodName, $arguments = array(), $deferred = false)
    {
        throw new Exception('This function is not allowed on this type of object');
    }

    /**
     * Function for executing all deferred method calls
     * @throws Exception
     */
    public function executeDeferredMethodCalls()
    {
        throw new Exception('This function is not allowed on this type of object');
    }

    /**
     * Function for committing the current variable state
     * @param \Sakwa\Utils\Guid $commitPoint GUID for this commit point
     * @return \Sakwa\Utils\Guid
     */
    public function commit(\Sakwa\Utils\Guid $commitPoint = null)
    {
        self::debug('Commit '.$commitPoint.' on '.$this->guid);

        return $this->getCurrentContext()->commit($commitPoint);
    }

    /**
     * Function for reverting variable value to given commit point
     * @param \Sakwa\Utils\Guid $commitPoint
     * @return boolean true when the variable is reverted
     */
    public function revert(\Sakwa\Utils\Guid $commitPoint)
    {
        self::debug('Revert '.$commitPoint.' on '.$this->guid);

        return $this->getCurrentContext()->revert($commitPoint);
    }

    /**
     * Function for accepting variable values als definitive
     * @param \Sakwa\Utils\Guid $commitPoint
     */
    public function push(\Sakwa\Utils\Guid $commitPoint)
    {
        self::debug('Push '.$commitPoint.' on '.$this->guid);

        $this->getCurrentContext()->push($commitPoint);
    }
}