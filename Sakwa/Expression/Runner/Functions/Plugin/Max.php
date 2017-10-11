<?php

namespace Sakwa\Expression\Runner\Functions\Plugin;

class Max extends Base
{
    /**
     * Function used for getting the max value
     *
     * @param \Sakwa\Expression\Runner\Evaluation\Value[] ...$params
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    public static function max(...$params)
    {
        $max = max(array_map(function ($val) {
            return $val->getValue();
        }, $params));

        return new \Sakwa\Expression\Runner\Evaluation\Value($max);
    }
}