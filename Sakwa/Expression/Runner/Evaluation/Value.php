<?php

namespace Sakwa\Expression\Runner\Evaluation;


class Value
{
    const IS_NULL     = 0,
          IS_NUMERIC  = 1,
          IS_LITERAL  = 2,
          IS_BOOLEAN  = 3,
          IS_ENTITY   = 4,
          IS_OPERATOR = 5,
          IS_DATE     = 6;

    /**
     * @var integer
     */
    protected $valueType;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Value constructor.
     *
     * @param mixed $value
     * @param bool  $isLiteral
     */
    public function __construct($value, $valueType = self::IS_NUMERIC)
    {
        if ($valueType == self::IS_NULL) {
            $value = null;
        }

        $this->value = $value;
        $this->valueType = $valueType;
    }

    /**
     * @return boolean
     */
    public function isNull(): bool
    {
        return $this->valueType == self::IS_NULL;
    }

    /**
     * @return boolean
     */
    public function isNumeric(): bool
    {
        return $this->valueType == self::IS_NUMERIC;
    }

    /**
     * @return boolean
     */
    public function isLiteral(): bool
    {
        return $this->valueType == self::IS_LITERAL;
    }

    /**
     * @return boolean
     */
    public function isBoolean(): bool
    {
        return $this->valueType == self::IS_BOOLEAN;
    }

    /**
     * @return boolean
     */
    public function isEntity(): bool
    {
        return $this->valueType == self::IS_ENTITY;
    }

    /**
     * @return boolean
     */
    public function isOperator(): bool
    {
        return $this->valueType == self::IS_OPERATOR;
    }

    /**
     * @return boolean
     */
    public function isDate(): bool
    {
        return $this->valueType == self::IS_DATE;
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
        if (($this->isNull()     && is_null($value))    ||
            ($this->isLiteral()  && is_string($value))  ||
            ($this->isNumeric()  && is_numeric($value)) ||
            ($this->isOperator() && is_string($value))  ||
            ($this->isBoolean()  && is_bool($value))    ||
            ($this->isEntity()   && is_object($value))  ||
            ($this->isDate()     && is_string($value))) {
            $this->value = $value;
        }
    }
}