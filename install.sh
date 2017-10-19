#!/bin/sh

git pull

composer install

echo "Changing file and folder permissions"
chmod -R 0644 Logging/Output
chmod 0666 Sakwa/Expression/Parser/Element/Transformations.php

echo "Regenerating expression parser statemachine transitions"
cd Scripts
php GenerateTransformations.php