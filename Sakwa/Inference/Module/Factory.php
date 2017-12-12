<?php

namespace Sakwa\Inference\Module;

use Sakwa\DecisionModel\DecisionTree;
use Sakwa\Inference\Module;
use Sakwa\Logging\LogTrait;

class Factory
{
    use LogTrait;

    /**
     * Function for creating an inference module instance
     * @param \Sakwa\Inference\Module\Configuration $configuration
     * @param array $testData
     * @return \Sakwa\Inference\Module
     */
    public static function createInferenceModule(\Sakwa\Inference\Module\Configuration $configuration, $testData = array())
    {
        $decisionModel = self::loadDecisionModel($configuration, $testData);
        return new Module($decisionModel);
    }

    /**
     * Function for loading the decision model
     * @param \Sakwa\Inference\Module\Configuration $configuration
     * @param array $testData
     * @return \Sakwa\DecisionModel\DecisionTree
     */
    private static function loadDecisionModel(\Sakwa\Inference\Module\Configuration $configuration, $testData = array())
    {
        $persistenceDriver = $configuration->getPersistenceDriver();

        switch ($persistenceDriver) {
            case 'xml':
                self::info('Loading sdm: '.$configuration->getDecisionModelUri());
                $resource = new \Sakwa\Persistence\XmlPersistence($configuration->getDecisionModelUri());
                break;

            case 'test':
                self::info('Loading persistence test driver');
                $resource = new \Sakwa\Persistence\TestPersistence($testData);
                break;
        }

        return new DecisionTree($resource);
    }
}
