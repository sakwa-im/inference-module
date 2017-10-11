<?php

namespace Sakwa\Inference\State\Entity\DomainObject;

class MethodCall
{
    /**
     * @var string $method_name
     */
    protected $method_name;

    /**
     * @var mixed[] $arguments
     */
    protected $arguments = array();

    /**
     * @var boolean $deferred
     */
    protected $deferred = false;

    /**
     * @var boolean $executed
     */
    protected $executed = false;

    /**
     * @var \Sakwa\Utils\Guid $domainObjectReference
     */
    protected $domainObjectReference;

    /**
     * @param string $method_name
     * @param array $arguments
     * @param boolean $deferred = false
     */
    public function __construct($reference, $method_name, $arguments, $deferred = false)
    {
        $this->domainObjectReference = $reference;
        $this->method_name           = $method_name;
        $this->arguments             = $arguments;

        $this->deferred = $deferred;
    }

    /**
     * Function for calling methods on the domain object in the DIM
     */
    public function callMethod()
    {
        try {
            if (!$this->executed) {
                $this->executed = true;

                //TODO: implement code for calling method
            }
        } catch (\Exception $e) {
            //TODO: implement error handling
        }
    }

    /**
     * Function for getting the method name
     * @return string
     */
    public function getMethodName()
    {
        return $this->method_name;
    }

    /**
     * Function for getting the arguments for this function call
     * @return array mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Function for getting the deferred flag
     * @return boolean
     */
    public function getDeferred()
    {
        return $this->deferred;
    }

    /**
     * Function for getting the executed flag
     * @return boolean
     */
    public function getExecuted()
    {
        return $this->executed;
    }
}