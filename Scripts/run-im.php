<?php

namespace Sakwa;

include __DIR__."/../Sakwa/Utils/Bootstrap.php";

use Sakwa\Inference\Module\Factory;
use Sakwa\Inference\Module\Configuration;
use Sakwa\Inference\State\Manager;
use Sakwa\Inference\State;
use Sakwa\Utils\Registry;

$configuration = new Configuration();
$configuration->setDecisionModelUri('Models/Tiny-expression.sdm');
//$configuration->setDecisionModelUri('test.sdm');
$im = Factory::createInferenceModule($configuration);
$im->start();


//echo str_replace("\n\n", "\n",print_r(Registry::$instance, true))."\n\n";

foreach (State::getInstance()->getAllAvailableContexts() as $context) {
    echo str_replace("\n\n", "\n",print_r(Manager::getInstance($context), true))."\n\n";
}
