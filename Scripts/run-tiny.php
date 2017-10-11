<?php

namespace Sakwa;

include __DIR__."/../Sakwa/Utils/Bootstrap.php";



$test = new \Sakwa\Persistence\XmlPersistence('../models/Tiny-model.sdm');

$decisionTree = new \Sakwa\DecisionModel\DecisionTree($test);

$node =   $decisionTree->retrieve();

//echo '<pre>'.var_export($node,true).'</pre>';

echo '<pre>'.var_export(\Sakwa\Utils\Registry::$instance, true).'</pre>';

foreach ($node->getChildren() as $child) {
    echo '<pre>'.var_export($child,true).'</pre>';
}