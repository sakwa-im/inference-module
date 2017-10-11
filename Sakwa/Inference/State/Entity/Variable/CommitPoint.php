<?php

namespace Sakwa\Inference\State\Entity\Variable;

use Sakwa\Utils\Guid;

class CommitPoint extends Guid
{
    protected $dirty = false;

    public function __construct($guid = null, $dirty = false)
    {
        parent::__construct($guid);
        $this->dirty = $dirty;
    }

    public function isDirty()
    {
        return $this->dirty;
    }
}