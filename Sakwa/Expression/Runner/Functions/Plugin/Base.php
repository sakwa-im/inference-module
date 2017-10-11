<?php

namespace Sakwa\Expression\Runner\Functions\Plugin;

use ReflectionMethod;
use Sakwa\Exception;

abstract class Base
{
    /**
     * Function for getting the plugin definition
     * @return array
     */
    public function getDefinition()
    {
        $rawClassName = get_class($this);
        $className    = substr($rawClassName, strrpos($rawClassName, '\\') + 1);
        $functionName = strtolower($className);

        $reflection = new ReflectionMethod($rawClassName, $functionName);
        $parameters = array();

        foreach ($reflection->getParameters() as $parameter) {
            $parameters[$parameter->getPosition()] = array('name'     => $parameter->getName(),
                                                           'default'  => $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null,
                                                           'optional' => $parameter->isOptional(),
                                                           'variadic' => $parameter->isVariadic());
        }

        $description = $reflection->getDocComment();

        if ($description !== false) {
            $descriptionLines = explode("\n", $description);
            $lines = array();

            foreach ($descriptionLines as $line) {
                $line = trim($line, "\t /*");

                if (strlen($line) == 0) {
                    continue;
                }

                if ($line[0] == '@') {
                    continue;
                }

                $lines[] = $line;
            }

            $description = implode("\n", $lines);
        }
        else {
            $description = $functionName;
        }

        return array('function'    => $functionName,
                     'name'        => $className,
                     'class'       => $rawClassName,
                     'description' => $description,
                     'parameters'  => $parameters);
    }

    /**
     * Function for executing the plugin
     * @param mixed[] ...$params
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    public function execute(...$params)
    {
        $def = $this->getDefinition();

        $function = $def['function'];

        if (!method_exists($this, $function)) {
            throw new Exception('Plugin '.$def['name'].' should contain a function '.$def['function'].'.');
        }

        return $this->$function(...$params);
    }
}