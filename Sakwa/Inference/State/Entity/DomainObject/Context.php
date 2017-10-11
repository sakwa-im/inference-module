<?php

namespace Sakwa\Inference\State\Entity\DomainObject;

class Context
{
    /**
     * @var \Sakwa\Inference\State\Entity\DomainObject\MethodCall[];
     */
    protected $methodCalls = array();

    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $guid;

    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $context = null;

    /**
     * @var \Sakwa\Utils\Guid;
     */
    protected $cycleContext;

    /**
     * Context constructor.
     *
     * @param \Sakwa\Utils\Guid $guid
     * @param \Sakwa\Utils\Guid $context
     * @param \Sakwa\Utils\Guid $cycleContext
     */
    public function __construct(\Sakwa\Utils\Guid $guid, \Sakwa\Utils\Guid $context, \Sakwa\Utils\Guid $cycleContext)
    {
        $this->cycleContext = $cycleContext;
        $this->context      = $context;
        $this->guid         = $guid;
    }

    /**
     * Function for retrieving the GUID if this variable entity
     * @return \Sakwa\Utils\Guid
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Function for retrieving the inference context
     * @return \Sakwa\Utils\Guid
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Function for retrieving the cycle context
     * @return \Sakwa\Utils\Guid
     */
    public function getCycleContext()
    {
        return $this->cycleContext;
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
        $methodCall          = new MethodCall($this->getGuid(), $methodName, $arguments, $deferred);
        $this->methodCalls[] = $methodCall;

        if (!$deferred) {
            $methodCall->callMethod();
            return $methodCall;
        }

        return null;
    }

    /**
     * Function for executing all deferred method calls
     * @return array \Sakwa\Inference\State\Entity\DomainObject\MethodCall[]
     */
    public function executeDeferredMethodCalls()
    {
        $output = array();

        foreach ($this->methodCalls as $methodCall) {
            if ($methodCall->getDeferred() && !$methodCall->getExecuted()) {
                $methodCall->callMethod();
                $output[] = $methodCall;
            }
        }

        return $output;
    }
}