<?php

namespace Sakwa;

include __DIR__."/../Sakwa/Utils/Bootstrap.php";

/*
use Sakwa\Expression\Engine;
use Sakwa\Cache\Controller AS CacheController;

$engine = new Engine();
//$engine->setExpression('0+42+69');
$engine->setExpression('80 + sum (10, 20, 30) + max(42, 63)');
$result = $engine->processExpression();

$cacheController = CacheController::getInstance();

echo str_replace("\n\n", "\n", print_r(array($cacheController, $result), true));
//*/

//*
use Sakwa\Expression\Parser;
use Sakwa\Expression\Planner;
use Sakwa\Expression\Runner;
use Sakwa\Utils\Registry;
use Sakwa\Utils\Guid;
use Sakwa\Inference\State;

$expressons = array(
//    '{44444444-dddd-5555-eeee-666666666666} += 1',
    '{44444444-dddd-5555-eeee-666666666666} /= 1'
);

$decisionModel = new \Sakwa\DecisionModel\VariableDef('foobar');
$decisionModel->setGuid(new Guid('44444444-dddd-5555-eeee-666666666666'));

Registry::set('decisionModel', $decisionModel, State::getInstance()->getContext());

\Sakwa\Utils\Registry::getInstance(new Guid('44444444-dddd-5555-eeee-666666666666'))->addKey(new Guid('44444444-dddd-5555-eeee-666666666666'), $decisionModel);

$em = \Sakwa\Inference\State\Manager::getInstance();
$em->createVariable(new Guid('44444444-dddd-5555-eeee-666666666666'), 1);


foreach ($expressons as $expression) {
    $parser = new Parser();
    $parser->setExpression($expression);

    $parser->parseExpression();
    $element = $parser->getElement();

    echo "source:  {$parser->getExpression()}\n";
    echo "parsed:  $element\n";

    $planner = new Planner();
    $planner->setElement($element);
    $planner->planExpression();

    $element = $planner->getElement();

    echo "planned: $element\n";

    $runner = new Runner();
    $runner->setElement($element);
    $result = $runner->run();

    echo "result:  {$result->getValue()}\n\n";

    //echo str_replace("\n\n", "\n", print_r($element, true))."\n";
}
//*/










