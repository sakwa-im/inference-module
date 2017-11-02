<?php

namespace Sakwa\Expression\Parser;

use Sakwa\Utils\Guid;
use Sakwa\Expression\Parser\Element\Base;
use Sakwa\Inference\State\Manager;
use Sakwa\Expression\Runner\Evaluation\Value as EvaluationValue;

class Element extends Base
{
    /**
     * @var string $elementType
     */
    protected $elementType;

    /**
     * @var string $token
     */
    protected $token;

    /**
     * @var \Sakwa\Expression\Parser\Element[] $childElements
     */
    protected $childElements = array();

    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $entityReference;

    /**
     * Element constructor.
     *
     * @param string $elementType
     * @param string $token
     */
    public function __construct($elementType, $token)
    {
        $this->elementType = $elementType;

        if ($elementType == self::TOKEN_VARIABLE_IDENTIFIER) {
            $this->setEntityReference(new Guid($token));
        }
        else {
            $this->setToken($token);
        }
    }

    /**
     * @param \Sakwa\Expression\Parser\Element $element
     */
    public function addChildElement($element)
    {
        $this->childElements[] = $element;
    }

    /**
     * @return \Sakwa\Expression\Parser\Element[]
     */
    public function getChildren()
    {
        return $this->childElements;
    }

    /**
     * @param \Sakwa\Expression\Parser\Element[] $childElements
     */
    public function setChildren($childElements)
    {
        $this->childElements = $childElements;
    }

    /**
     * @return boolean
     */
    public function hasChildren()
    {
        return count($this->childElements) > 0;
    }

    /**
     * @return string
     */
    public function getElementType()
    {
        return $this->elementType;
    }

    public function isElementTypeOfTheOperatorFamily()
    {
        return in_array($this->getElementType(), array(
            Element::TOKEN_OPERATOR,
            Element::TOKEN_LOGIC_OPERATOR,
            Element::TOKEN_ASSIGNMENT_OPERATOR
        ));
    }

    /**
     * @param string $elementType
     */
    public function setElementType($elementType)
    {
        $this->elementType = $elementType;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param \Sakwa\Utils\Guid $reference
     */
    public function setEntityReference($reference)
    {
        $this->entityReference = $reference;
    }

    /**
     * @return \Sakwa\Utils\Guid
     */
    public function getEntityReference()
    {
        return $this->entityReference;
    }

    /**
     * @return null|\Sakwa\Inference\State\Entity\Base
     */
    public function getEntity()
    {
        if ($this->hasEntityReference()) {
            $entityManager = Manager::getInstance();

            return $entityManager->getEntity($this->entityReference);
        }
    }

    /**
     * @return boolean
     */
    public function hasEntityReference()
    {
        return !is_null($this->entityReference);
    }

    public function getEntities()
    {
        $output = array();

        if ($this->hasEntityReference()) {
            $output[] = $this->getEntity();
        }

        foreach ($this->childElements as $childElement) {
            $output = array_merge($output, $childElement->getEntities());
        }

        return $output;
    }

    /**
     * @return EvaluationValue
     */
    public function getValue()
    {
        if ($this->getElementType() == \Sakwa\Expression\Parser\Element::TOKEN_VARIABLE_IDENTIFIER) {
            return new EvaluationValue($this->getEntity(), EvaluationValue::IS_ENTITY);
        }
        elseif ($this->isElementTypeOfTheOperatorFamily()) {
            return new EvaluationValue($this, EvaluationValue::IS_OPERATOR);
        }
        else {
            switch ($this->getElementType()) {
                case Element::TOKEN_LITERAL:
                    $value = new EvaluationValue($this->getToken(), EvaluationValue::IS_LITERAL);
                    break;
                default:
                    $value = new EvaluationValue($this->getToken(), EvaluationValue::IS_NUMERIC);
                    break;
            }

            return $value;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $output = array();

        switch ($this->getElementType()) {
            case self::TOKEN_ROOT:
            case self::TOKEN_GROUP:
            case self::TOKEN_PARAMETER:
                $outputChildElement = array();

                foreach ($this->getChildren() as $childNode) {
                    $outputChildElement[] = $childNode->__toString();
                }

                if ($this->getElementType() == self::TOKEN_GROUP) {
                    $output[] = '('.implode(' ', $outputChildElement).')';
                }
                else {
                    $output[] = implode(' ', $outputChildElement);
                }
                break;

            case self::TOKEN_LITERAL:
                $token = $this->getToken();
                $token = str_replace('\\', '\\\\', $token);
                $token = str_replace('"', '\\"', $token);
                $output[] = '"'.$token.'"';
                break;

            case self::TOKEN_VARIABLE_IDENTIFIER:
                $output[] = '{'.$this->getEntityReference().'}';
                break;

            default:
                $output[] = $this->getToken();
                break;
        }

        return implode(' ', $output);
    }
}