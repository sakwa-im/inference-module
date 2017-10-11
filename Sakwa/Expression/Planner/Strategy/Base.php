<?php

namespace Sakwa\Expression\Planner\Strategy;

abstract class Base {
    abstract public function evaluate(\Sakwa\Expression\Parser\Element $element);
}