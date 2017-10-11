<?php

namespace Sakwa\Expression\Runner\Functions\Plugin;

class Sum extends Base
{
    /**
     * Function used for summing all values in a set
     * @param \Sakwa\Expression\Runner\Evaluation\Value $params
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     *
     */
    public static function sum(...$params)
    {
        $sum = array_sum(array_map(function ($val) {
            return $val->getValue();
        }, $params));

        return new \Sakwa\Expression\Runner\Evaluation\Value($sum);
    }
}