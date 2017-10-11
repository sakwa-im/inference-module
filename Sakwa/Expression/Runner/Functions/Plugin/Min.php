<?php

namespace Sakwa\Expression\Runner\Functions\Plugin;

class Min extends Base
{
    /**
     * Function used for getting the min value
     * @param \Sakwa\Expression\Runner\Evaluation\Value[] ...$params
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    public static function min(...$params)
    {
        $min = min(array_map(function ($val) {
            return $val->getValue();
        }, $params));

        return new \Sakwa\Expression\Runner\Evaluation\Value($min);
    }
}