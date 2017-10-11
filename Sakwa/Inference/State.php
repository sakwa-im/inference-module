<?php

namespace Sakwa\Inference;

use Sakwa\Utils\Registry;
use Sakwa\Utils\EntityList;
use Sakwa\Utils\Guid;

class State
{
    /**
     * @var \Sakwa\Utils\EntityList
     */
    protected $available_contexts;

    /**
     * @var \Sakwa\Utils\Guid;
     */
    protected $current_context;

    private function __construct()
    {
        $this->available_contexts = new EntityList();
        $this->createNewContext();
    }

    /**
     * function for returning the instance of the inference state
     * @return \Sakwa\Inference\State
     */
    public static function getInstance()
    {
        if (!Registry::has('state')) {
            $instance = new self();
            Registry::set('state', $instance);
        }
        return Registry::get('state');
    }

    /**
     * Function for creating a new context
     * @return \Sakwa\Utils\Guid
     */
    public function createNewContext()
    {
        $guid = new Guid();
        $this->setContext($guid);
        return $guid;
    }

    /**
     * Function for returning all available contexts
     * @return \Sakwa\Utils\EntityList
     */
    public function getAllAvailableContexts()
    {
        return $this->available_contexts;
    }

    /**
     * Function for setting the current context
     * @param \Sakwa\Utils\Guid $guid
     */
    public function setContext(\Sakwa\Utils\Guid $guid)
    {
        if (is_null($this->current_context) || !$this->current_context->is($guid)) {
            $this->current_context = $guid;

            if (!isset($this->available_contexts[(string)$guid])) {
                $this->available_contexts[] = $guid;
            }
        }
    }

    /**
     * Function for getting the current context
     * @return \Sakwa\Utils\Guid
     */
    public function getContext()
    {
        return $this->current_context;
    }
}