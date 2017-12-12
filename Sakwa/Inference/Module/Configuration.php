<?php

namespace Sakwa\Inference\Module;

use Sakwa\Exception;

class Configuration {
    protected $decisionModelUri;
    protected $persistenceDriver = 'xml';

    /**
     * Function for getting the decisionModelUri
     * @return string $decisionModelUri
     */
    public function getDecisionModelUri()
    {
        return $this->decisionModelUri;
    }

    /**
     * Function for setting the decisionModelUri
     * @param string $decisionModelUri
     */
    public function setDecisionModelUri($decisionModelUri)
    {
        $this->decisionModelUri = $decisionModelUri;
    }

    /**
     * @param string $persistenceDriver
     */
    public function setPersistenceDriver($persistenceDriver = 'xml')
    {
        if (!in_array($persistenceDriver, array('xml', 'test'))) {
            throw new Exception('Invalid persistence ('.$persistenceDriver.') driver selected');
        }
        $this->persistenceDriver = $persistenceDriver;
    }

    /**
     * @return string
     */
    public function getPersistenceDriver()
    {
        return $this->persistenceDriver;
    }
}