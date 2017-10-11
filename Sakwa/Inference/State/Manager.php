<?php

namespace Sakwa\Inference\State;

use Sakwa\Inference\State\Entity\DomainObject;
use Sakwa\Inference\State\Entity\Variable;
use Sakwa\Inference\State;
use Sakwa\Utils\Registry;
use Sakwa\Utils\Guid;

class Manager
{
    /**
     * @var \Sakwa\Inference\State\Entity\Base[]
     */
    protected $entity_references_by_guid = array();

    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $context;

    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $current_branch_point;

    /**
     * Manager constructor.
     * It's not allowed to create random instances so private!
     * @param \Sakwa\Utils\Guid $context
     */
    private function __construct(\Sakwa\Utils\Guid $context)
    {
        $this->context = $context;
    }

    /**
     * Function for getting the correct instance (based on the inference context) of the state object
     *
     * @param \Sakwa\Utils\Guid $context
     *
     * @return \Sakwa\Inference\State\Manager
     */
    public static function getInstance(\Sakwa\Utils\Guid $context = null)
    {
        if (is_null($context)) {
            $context = State::getInstance()->getContext();
        }

        if (!Registry::has(__NAMESPACE__, $context)) {
            Registry::add(__NAMESPACE__, (new self($context)), $context);
        }

        return Registry::get(__NAMESPACE__, $context);
    }

    /**
     * Function for reInitializing state entity's
     */
    public function reInitializeEntitys()
    {
        foreach ($this->entity_references_by_guid as $entity) {
            $entity->reInitialize();
        }
    }

    /**
     * Function used for creating variable objects
     * @param \Sakwa\Utils\Guid $guid
     * @param mixed $value
     * @param \Sakwa\Utils\Guid|null $cycleContext
     * @return \Sakwa\Inference\State\Entity\Variable
     */
    public function createVariable($guid = null, $value = null, \Sakwa\Utils\Guid $cycleContext = null)
    {
        $variable = new Variable($guid, $value, $cycleContext);
        $this->addVariable($variable);

        return $variable;
    }

    /**
     * Function for checking the existence of a variable
     * @param \Sakwa\Utils\Guid $guid
     * @return boolean
     */
    public function hasVariable(\Sakwa\Utils\Guid $guid)
    {
        return isset($this->entity_references_by_guid["$guid"]);
    }

    /**
     * Function for setting new value and or the dataDefinition of a variable
     * @param \Sakwa\Utils\Guid $guid
     * @param mixed $value
     * @return \Sakwa\Inference\State\Entity\Variable
     */
    public function setVariable(\Sakwa\Utils\Guid $guid, $value)
    {
        if (!$this->hasVariable($guid)) {
            $variable = $this->createVariable($guid, $value);
        }
        else {
            $variable = $this->getVariable($guid);
            $variable->setValue($value);
        }

        return $variable;
    }

    /**
     * @param \Sakwa\Utils\Guid $guid
     *
     * @return \Sakwa\Inference\State\Entity\Variable
     */
    public function getVariable(\Sakwa\Utils\Guid $guid)
    {
        if ($this->hasVariable($guid)) {
            return $this->entity_references_by_guid["$guid"];
        }
        else {
            return $this->createVariable($guid);
        }
    }

    /**
     * Function used for creating domain objects
     * @param \Sakwa\Utils\Guid $guid
     * @return \Sakwa\Inference\State\Entity\DomainObject
     */
    public function createDomainObject($guid = null)
    {
        $domainObject = new DomainObject($guid);
        $this->addDomainObject($domainObject);

        return $domainObject;
    }

    /**
     * Function for adding entities to the entity manager
     * @param \Sakwa\Inference\State\Entity\Base $entity
     */
    public function addEntity(\Sakwa\Inference\State\Entity\Base $entity)
    {

        if ($entity instanceof \Sakwa\Inference\State\Entity\Variable) {
            $this->addVariable($entity);
        } else
            if ($entity instanceof \Sakwa\Inference\State\Entity\DomainObject) {
            $this->addDomainObject($entity);
        }
    }

    /**
     * Function for getting a variable by guid
     * @param \Sakwa\Utils\Guid $guid
     * @return \Sakwa\Inference\State\Entity\Base
     */
    public function getEntity(\Sakwa\Utils\Guid $guid)
    {
        if (!isset($this->entity_references_by_guid[(string)$guid])) {
            return null; //TODO: Error handling
        }

        return $this->entity_references_by_guid[(string)$guid];
    }

    /**
     * Function for registering variables in the state object
     * @param \Sakwa\Inference\State\Entity\Variable $variable
     */
    public function addVariable(\Sakwa\Inference\State\Entity\Variable $variable)
    {
        $variable->setContext($this->context);

        $this->entity_references_by_guid[(string)$variable->getGuid()] = $variable;
    }

    /**
     * Function for registering variables in the state object
     * @param \Sakwa\Inference\State\Entity\DomainObject $domain_object
     */
    public function addDomainObject(\Sakwa\Inference\State\Entity\DomainObject $domain_object)
    {
        $domain_object->setContext($this->context);

        $this->entity_references_by_guid[(string)$domain_object->getGuid()] = $domain_object;
    }

    /**
     * Function for creating a new commit point for all variables in the state
     * @param \Sakwa\Utils\Guid $branch_point
     * @return \Sakwa\Utils\Guid
     */
    public function branch(\Sakwa\Utils\Guid $branch_point = null)
    {
        if (is_null($branch_point)) {
            $branch_point = new Guid();
        }

        $this->current_branch_point = $branch_point;

        foreach ($this->entity_references_by_guid as $entity) {
            if ($entity instanceof Variable) {
                $entity->commit($branch_point);
            }
        }

        return $branch_point;
    }

    /**
     * Function for reverting the variable state to a specific commit point
     * @param \Sakwa\Utils\Guid $branch_point
     */
    public function revert(\Sakwa\Utils\Guid $branch_point)
    {
        foreach ($this->entity_references_by_guid as $entity) {
            $entity->revert($branch_point);
        }

        $this->current_branch_point = $branch_point;
    }

    /**
     * Function for setting the current state as true
     */
    public function commit()
    {
        foreach ($this->entity_references_by_guid as $entity) {
            if ($entity instanceof Variable) {
                $entity->push($this->current_branch_point);
            }
            elseif ($entity instanceof DomainObject) {
                $entity->executeDeferredMethodCalls();
            }
        }
    }
}