<?php

namespace Sakwa\Inference\State\Entity\Variable;

use Sakwa\Utils\EntityList;

class Revision
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var \Sakwa\Utils\EntityList
     */
    protected $commit_points;

    public function __construct($value = null)
    {
        $this->commit_points = new EntityList();
        $this->value         = $value;
    }

    /**
     * Function for returning the value corresponding to this revision
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Function toString for magically returning string representation of the value
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * Function for adding a commit point
     * @param \Sakwa\Utils\Interfaces\Guid $commit_point
     */
    public function addCommitPoint(\Sakwa\Utils\Interfaces\Guid $commit_point)
    {
        $this->commit_points->add($commit_point);
    }

    /**
     * Function for checking for the existence of a commit point
     * @param \Sakwa\Utils\Guid $commit_point
     * @return boolean
     */
    public function hasCommitPoint(\Sakwa\Utils\Guid $commit_point)
    {
        return $this->commit_points->has($commit_point);
    }

    /**
     * @return \Sakwa\Inference\State\Entity\Variable\CommitPoint
     */
    public function getCommitPoint(\Sakwa\Utils\Guid $commit_point)
    {
        return $this->commit_points->getEntity($commit_point);
    }
}