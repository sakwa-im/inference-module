<?php

namespace Sakwa\Expression\Runner\Functions\Plugin;

class Abs extends Base
{
    /**
     * Function used for getting the absolute value
     * @param \Sakwa\Expression\Runner\Evaluation\Value $param
     *
     * @return \Sakwa\Expression\Runner\Evaluation\Value
     */
    public static function abs($param)
    {
        return new \Sakwa\Expression\Runner\Evaluation\Value(abs($param->getValue()));
    }
}