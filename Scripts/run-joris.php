<?php

namespace Sakwa;

include __DIR__."/../Sakwa/Utils/Bootstrap.php";



$test = new \Sakwa\Persistence\XmlPersistence('/home/joris/Documents/Projects/decision-models/Sakwa Michelle Deleted statussen.sdm');
while ($test->nextRecord()) {
    $nodeName = $test->getFieldValue('name');
    $classType   = $test->getFieldValue('class-type');


    $decisionTree = new \Sakwa\DecisionModel\DecisionTree($nodeName);
    $foo = $decisionTree->createNewNode($classType);

    echo $test->getFieldValue('node-type') . ': ' . $test->getFieldValue('name') . PHP_EOL;
}