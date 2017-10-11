<?php

namespace Sakwa\Inference\Module;

class Configuration {
    protected $decisionModelUri;

    /**
     * Function for getting the decisionModelUri
     * @return string $decisionModelUri
     */
    function getDecisionModelUri()
    {
        return $this->decisionModelUri;
    }

    /**
     * Function for setting the decisionModelUri
     * @param string $decisionModelUri
     */
    function setDecisionModelUri($decisionModelUri)
    {
        $this->decisionModelUri = $decisionModelUri;
    }
}