<?php

namespace Sakwa\Inference\State\Entity\Variable;

use Sakwa\Utils\Guid;

class Context
{
    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $guid;

    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $context = null;

    /**
     * @var \Sakwa\Utils\Guid;
     */
    protected $setReference;

    /**
     * @var \Sakwa\Utils\Guid;
     */
    protected $cycleContext;


    /**
     * @var \Sakwa\Inference\State\Entity\Variable\Revision[]
     */
    protected $revisions = array();

    /**
     * @var \Sakwa\Inference\State\Entity\Variable\Revision $currentRevision
     */
    protected $currentRevision;

    /**
     * @var boolean $isDirty true when value has changed
     */
    protected $dirty = false;


    public function __construct(\Sakwa\Utils\Guid $guid, \Sakwa\Utils\Guid $context, \Sakwa\Utils\Guid $cycleContext)
    {
        $this->cycleContext = $cycleContext;
        $this->context      = $context;
        $this->guid         = $guid;
    }

    /**
     * Function for retrieving the GUID if this variable entity
     * @return \Sakwa\Utils\Guid
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Function for retrieving the inference context
     * @return \Sakwa\Utils\Guid
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Function for retrieving the cycle context
     * @return \Sakwa\Utils\Guid
     */
    public function getCycleContext()
    {
        return $this->cycleContext;
    }

    /**
     * Function for setting a new value on this variable
     * @param mixed $value
     */
    public function setValue($value)
    {
        $revision = new Revision($value);

        $this->currentRevision = $revision;
        $this->revisions[]     = $revision;

        $this->dirty = true;
    }

    /**
     * @return boolean
     */
    public function hasValue()
    {
        return !is_null($this->currentRevision);
    }

    /**
     * Function for returning the value of this variable
     * @return mixed
     */
    public function getValue()
    {
        return $this->currentRevision->getValue();
    }

    /**
     * Function for checking the dirty flag
     * @return boolean
     */
    public function isDirty()
    {
        return $this->dirty;
    }

    /**
     * Function for committing the current variable state
     * @param \Sakwa\Utils\Guid $commitPoint GUID for this commit point
     * @return \Sakwa\Utils\Guid
     */
    public function commit(\Sakwa\Utils\Guid $guid = null)
    {
        if (is_null($guid)) {
            $guid = new Guid();
        }

        $commitPoint = new CommitPoint($guid, $this->dirty);

        $this->currentRevision->addCommitPoint($commitPoint);

        return $commitPoint;
    }

    /**
     * Function for reverting variable value to given commit point
     * @param \Sakwa\Utils\Guid $commitPoint
     * @return boolean true when the variable is reverted
     */
    public function revert(\Sakwa\Utils\Guid $commitPoint)
    {
        $newEndIndex = null;
        $dirty = false;

        for ($i = count($this->revisions) - 1; $i >= 0; $i--) {
            $variableRevision = $this->revisions[$i];

            if ($variableRevision->hasCommitPoint($commitPoint)) {
                $this->currentRevision = $variableRevision;

                $newEndIndex = $i;
                $dirty = $variableRevision->getCommitPoint($commitPoint)->isDirty();
            }
        }

        if (!is_null($newEndIndex)) {
            $this->revisions = array_slice($this->revisions, 0, $newEndIndex + 1);
            $this->dirty     = $dirty;

            return true;
        }

        return false;
    }

    /**
     * Function for accepting variable values als definitive
     * @param \Sakwa\Utils\Guid $guid
     */
    public function push(\Sakwa\Utils\Guid $guid)
    {
        $commitPoint = new CommitPoint($guid, false);

        $revision = new Revision($this->currentRevision->getValue());
        $revision->addCommitPoint($commitPoint);

        $this->currentRevision = $revision;
        $this->revisions       = array($revision);
        $this->dirty           = false;
    }
}