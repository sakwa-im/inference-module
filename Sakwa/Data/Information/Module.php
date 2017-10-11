<?php

namespace Sakwa\Data\Information;

use Sakwa\Utils\Registry;
use Sakwa\Inference\State;

class Module {
    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $context;


    /**
     * Module constructor.
     * It's not allowed to create random instances so this function is declared private
     * @param \Sakwa\Utils\Guid $context
     */
    private function __construct(\Sakwa\Utils\Guid $context)
    {
        $this->context = $context;
    }

    /**
     * Function for getting the correct instance (based on the inference context) of the state object
     * @param \Sakwa\Utils\Guid $context
     * @return \Sakwa\Data\Information\Module
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
     * Function for retrieving data from the DIM
     * @param \Sakwa\Utils\Guid $guid
     * @return mixed
     */
    public function getVariableValue(\Sakwa\Utils\Guid $guid)
    {
        $dataDefinition = $this->getDataDefinition($guid);
        //TODO implement functionality
    }

    /**
     * Function for loading the data definition for this entity
     * @param \Sakwa\Utils\Guid $guid
     * @return \Sakwa\DecisionModel\VariableDef
     */
    protected function getDataDefinition(\Sakwa\Utils\Guid $guid)
    {
        /**
         * @var \Sakwa\DecisionModel\DecisionTree $dataDefinition
         */
        $dataDefinition = Registry::get('decisionModel', State::getInstance()->getContext());
        return $dataDefinition->findNode($guid);
    }
}