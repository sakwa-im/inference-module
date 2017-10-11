<?php

namespace Sakwa\Persistence;

/**
 * Object representation of record structure
 */
class Record
{
    /**
     * @var string
     */
    public $classType = null;

    /**
     * @var string
     */
    public $reference = null;

    /**
     * @var string
     */
    public $domainReference = null;

    /**
     * @var string
     */
    public $domainReferenceR = null;

    /**
     * @var string
     */
    public $count = null;

    /**
     * @var string
     */
    public $nodeType = null;

    /**
     * @var string
     */
    public $name = null;

    /**
     * @var string
     */
    public $description = null;

    /**
     * @var string
     */
    public $domainShortName = null;

    /**
     * @var string
     */
    public $charVariableValue = null;

    /**
     * @var string
     */
    public $variableType = null;

    /**
     * @var string
     */
    public $intVariableValue = null;

    /**
     * @var string
     */
    public $intVariableMinvalue = null;

    /**
     * @var string
     */
    public $intVariableMaxvalue = null;

    /**
     * @var string
     */
    public $intVariableStepvalue = null;

    /**
     * @var string 
     */
    public $enumVariable = null;

    /**
     * @var array
     */
    public $enumVariableBranches = array();

    /**
     * @var string
     */
    public $branchLogic = null;

    /**
     * @var string
     */
    public $expression;

    /**
     * @var string
     */
    public $branchEvaluation;

    /**
     * @var string
     */
    public $initializeMode = 'None';


    /**
     * Function for creating \Sakwa\Persistence\Record
     * @param \Sakwa\Persistence\IPersistence $persistence
     * @return \Sakwa\Persistence\Record
     */
    public static function createPersistenceRecord(\Sakwa\Persistence\IPersistence $persistence)
    {
        $inst = new self();

        // fill object by reflecting current record on object
        foreach ($inst as $key => &$val) {
            if ($persistence->hasField($key)) {
                $val = $persistence->getFieldValue($inst->convertCase($key), null);
            }
        }
        return $inst;
    }

    /**
     * @param string $field
     * @return string
     */
    private function convertCase($field)
    {
        $val = ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '-$0', $field)), '-');
        return $val;
    }
}