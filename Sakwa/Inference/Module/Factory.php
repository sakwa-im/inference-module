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
     * @return \Sakwa\Inference\Module
     */
    public static function createInferenceModule(\Sakwa\Inference\Module\Configuration $configuration)
    {
        $decisionModel = self::loadDecisionModel($configuration);
        $inferenceModule = new Module($decisionModel);
        return $inferenceModule;
    }

    /**
     * Function for loading the decision model
     * @param \Sakwa\Inference\Module\Configuration $configuration
     * @return \Sakwa\DecisionModel\DecisionTree
     */
    private static function loadDecisionModel(\Sakwa\Inference\Module\Configuration $configuration)
    {
        //TODO: make this smarter when needed
        self::info('Loading sdm: '.$configuration->getDecisionModelUri());
        $resource = new \Sakwa\Persistence\XmlPersistence($configuration->getDecisionModelUri());
        return new DecisionTree($resource);
    }
}
