<?php

namespace Sakwa\DecisionModel;

use Sakwa\DecisionModel\Enum\VariableType;
use Sakwa\DecisionModel\Enum\InitializeMode;

/**
 * VariableDef
 */
class VariableDef extends BaseNode
{

    /**
     * @var int
     */
    private $variableType = null;

    /**
     * @var string
     */
    private $initializeMode = null;

    /**
     * @var mixed
     */
    private $value = null;

    /**
     * @param string $name
     * @param int $type
     */
    public function __construct($name, $type = Enum\NodeType::VarDefinition)
    {
        parent::__construct($name, $type);
    }

    /**
     * @return int
     */
    public function getVariableType()
    {
        return $this->variableType;
    }

    /**
     * @param int $variableType
     */
    public function setVariableType($variableType)
    {
        $this->variableType = $variableType;
    }

    /**
     * @return string
     */
    public function getInitializeMode()
    {
        return $this->initializeMode;
    }

    /**
     * @param string $initializeMode
     */
    public function setInitializeMode($initializeMode)
    {
        $this->initializeMode = $initializeMode;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param \Sakwa\Persistence\Record $record
     */
    protected function _fill(\Sakwa\Persistence\Record $record)
    {
        switch ($this->getVariableType()) {
            case VariableType::character:
                $this->setValue($record->charVariableValue);
                break;

            case VariableType::numeric:
                $this->setValue($record->intVariableValue);
                break;

            case VariableType::enumeration:
                $this->setValue($record->enumVariable);
                break;

            case VariableType::date:
                $this->setValue($record->dateVariable);
                break;

            case VariableType::boolean:
                $this->setValue((boolean)$record->intVariableValue);
                break;
        }

        if (!InitializeMode::isValueEnumValue($record->initializeMode)) {
            throw new Exception('Invalid initialize mode detected!');
        }

        $this->setInitializeMode(InitializeMode::getEnumValue($record->initializeMode));
    }
}