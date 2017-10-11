<?php

namespace Sakwa;

include __DIR__."/../Sakwa/Utils/Bootstrap.php";

use ReflectionClass;

$obj = new ReflectionClass('\Sakwa\Expression\Parser\Base');
$constants = $obj->getConstants();

$transformations = array();

foreach ($constants as $source_index => $source_name) {
    $source_constant = substr($source_index, 6);
    if (substr($source_index, 0, 5) != 'TOKEN') {
        continue;
    }

    foreach ($constants as $target_index => $target_name) {
        if (substr($target_index, 0, 5) != 'TOKEN') {
            continue;
        }

        $target_constant = substr($target_index, 6);
        $transformation_index = 'TRANSITION_'.$source_constant.'_TO_'.$target_constant;

        $transformations[$transformation_index] = $source_name.$target_name;
    }
}

$key_length = 0;

$first_key = null;

foreach ($transformations as $key => $value) {
    if ($key_length < strlen($key)) {
        $key_length = strlen($key);
    }

    if (is_null($first_key)) {
        $first_key = $key;
    }
}

$key_length++;

$code  = '<'."?php\n\nnamespace Sakwa\Expression\Parser;\n\nabstract class Transformations\n{\n";
$code .= '    const '.str_pad($first_key, $key_length, ' ', STR_PAD_RIGHT).' = \''.array_shift($transformations).'\'';

foreach ($transformations as $key => $value) {
    $code .= ",\n          ".str_pad($key, $key_length, ' ', STR_PAD_RIGHT).' = \''.$value.'\'';
}

$code .= ";\n}";

file_put_contents(\Sakwa\Utils\File::createFilePath('Sakwa', 'Expression', 'Parser', 'Transformations.php'), $code);