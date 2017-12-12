<?php

namespace Sakwa\DecisionModel;

use Sakwa\Utils\Guid;

/**
 * Base implementation for nodes
 */
class BaseNode
{
    /**
     * @var string
     */
    private $name = null;

    /**
     * @var string
     */
    private $description = null;

    /**
     * @var \Sakwa\Utils\Guid
     */
    private $reference = null;

    /**
     * @var \Sakwa\Utils\Guid
     */
    private $guid = null;

    /**
     * @var \Sakwa\DecisionModel\Enum\Base
     */
    private $type = null;

    /**
     * @var array
     */
    private $nodes = array();

    /**
     * @var \Sakwa\Utils\Guid
     */
    private $parent = null;

    /**
     * @param string $name
     * @param \Sakwa\DecisionModel\Enum\Base $type
     */
    public function __construct($name, $type = null)
    {
        $this->checkType($type);

        $this->setName($name);
        $this->setType($type);
    }

    /**
     * @param \Sakwa\Persistence\Record $record
     */
    public function fill(\Sakwa\Persistence\Record $record)
    {
        $this->setReference(new Guid($record->reference));
        $this->setDescription($record->description);
        $this->_fill($record);
    }

    /**
     * @param \Sakwa\Persistence\Record $record
     */
    protected function _fill(\Sakwa\Persistence\Record $record)
    {
        // should be overridden/implemented by subclasses
    }

    /**
     * @param \Sakwa\Utils\Guid $guid
     *
     * @return \Sakwa\DecisionModel\BaseNode
     */
    public function findNode(\Sakwa\Utils\Guid $guid)
    {
        return $this->getReferenceRegistry()->getKey($guid);
    }

    /**
     * @param \Sakwa\DecisionModel\Enum\NodeType $type
     * @throws Exception
     */
    protected function checkType($type)
    {

        if (!\Sakwa\DecisionModel\Enum\NodeType::isValid($type)) {
            //throw new Exception(Exception::INVALID_TYPE.'  '.$type);
        }
    }

    /**
     * @param \Sakwa\DecisionModel\BaseNode $node
     * @codeCoverageIgnore
     */
    public function merge(BaseNode $node)
    {
        //?
    }

    /**
     * @param \Sakwa\DecisionModel\BaseNode $node
     * @codeCoverageIgnore
     */
    public function compare(BaseNode $node)
    {
        //?
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \Sakwa\DecisionModel\Enum\Base
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \Sakwa\DecisionModel\Enum\Base $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \Sakwa\Utils\Guid
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param \Sakwa\Utils\Guid $reference
     */
    public function setReference(Guid $reference)
    {
        $this->reference = new Guid($reference);
    }

    /**
     * @return \Sakwa\DecisionModel\BaseNode[]
     */
    public function getChildren()
    {
        $nodes = array();
        foreach ($this->nodes as $ref) {
            $nodes[(string)$ref] = $this->getReferenceRegistry()->getKey($ref);
        }
        return $nodes;
    }

    /**
     * @param \Sakwa\DecisionModel\BaseNode $node
     */
    public function addChild(BaseNode $node)
    {
        $this->nodes[(string)$node->getReference()] = $node->getReference();
    }

    /**
     * @param \Sakwa\DecisionModel\BaseNode $node
     */
    public function removeChild(BaseNode $node)
    {
        if (\array_key_exists((string)$node->getReference(), $this->nodes)) {
            unset($this->nodes[(string)$node->getReference()]);
        }
    }

    /**
     * Function for checking if node had children
     * @return boolean
     */
    public function hasChildren()
    {
        return (count($this->nodes) > 0);
    }

    /**
     * @return \Sakwa\DecisionModel\BaseNode $node
     */
    public function getParent()
    {
        return $this->getReferenceRegistry()->getKey($this->parent);
    }

    /**
     * @param \Sakwa\DecisionModel\BaseNode $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent->getReference();
    }

    /**
     * @return \Sakwa\Utils\Registry
     */
    public function getReferenceRegistry()
    {
        return \Sakwa\Utils\Registry::getInstance($this->getGuid());
    }

    /**
     * @param \Sakwa\Utils\Guid $guid
     */
    public function setGuid(\Sakwa\Utils\Guid $guid)
    {
        $this->guid = $guid;
    }

    /**
     * @return \Sakwa\Utils\Guid
     */
    public function getGuid()
    {
        if (is_null($this->guid)) {

            $this->guid = new \Sakwa\Utils\Guid();
        }
        return $this->guid;
    }
}