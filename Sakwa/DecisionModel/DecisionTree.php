<?php

namespace Sakwa\DecisionModel;

/**
 * DecisionTree
 */
class DecisionTree extends BaseNode
{
    /**
     * @var \Sakwa\Persistence\IPersistence
     */
    private $persistence = null;

    /**
     * @var string
     */
    private $rootNodeRef = null;


    /**
     * @param \Sakwa\Persistence\IPersistence $persistence
     */
    public function __construct(\Sakwa\Persistence\IPersistence $persistence)
    {
        parent::__construct($persistence);

        $this->setPersistence($persistence);
    }

    /**
     * create instance of a node described by the record
     *
     * @param \Sakwa\Persistence\Record $record
     * @return \Sakwa\DecisionModel\BaseNode
     */
    public function createNewNode(\Sakwa\Persistence\Record $record)
    {

        $class = \Sakwa\DecisionModel\ObjectMapping::getObjectName($record->classType);

        $type  = \Sakwa\DecisionModel\Enum\NodeType::getEnumValue($record->nodeType);

        /** @var \Sakwa\DecisionModel\VariableDef $node */
        $node  = new $class($record->name, $type);
        $node->fill($record);

//        if($node instanceof \Sakwa\DecisionModel\Branch || $node instanceof \Sakwa\DecisionModel\Expression) {
//            $node->resolveEntityReference($record);
//        }

        return $node;
    }

    /**
     * Returns the root node of a tree
     * 
     * @return \Sakwa\DecisionModel\BaseNode
     */
    public function retrieve()
    {
        $persistence = $this->getPersistence();
        $node        = $this->buildTree($persistence);

        // store reference to root node
        $this->setRootNodeRef($node->getReference());
        return $node;
    }

    /**
     * Builds node calls itself for child nodes
     *
     * @param \Sakwa\Persistence\Record $persistence
     * @return \Sakwa\DecisionModel\BaseNode
     */
    private function buildTree($persistence)
    {
        $persistence->nextRecord();

        $record   = \Sakwa\Persistence\Record::createPersistenceRecord($persistence);
        $count    = $persistence->getFieldValue('count');
        $children = array();

        while ($count > 0) {
            $children[] = $this->buildTree($persistence);
            --$count;
        }

        $node = $this->createNewNode($record);

        foreach ($children as $child) {
            $node->addChild($child);
            $child->setParent($node);
        }

        // store in ref registry
        $node->setGuid($this->getGuid());
        $this->getReferenceRegistry()->addKey($node->getReference(), $node);
        return $node;
    }

    /**
     * @return \Sakwa\Persistence\IPersistence
     */
    public function getPersistence()
    {
        return $this->persistence;
    }

    /**
     * @param \Sakwa\Persistence\IPersistence $persistence
     */
    public function setPersistence(\Sakwa\Persistence\IPersistence $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * @return \Sakwa\DecisionModel\BaseNode
     */
    public function getRoot()
    {
        return $this->getReferenceRegistry()
                ->getKey($this->getRootNodeRef());
    }

    /**
     * @return string
     */
    public function getRootNodeRef()
    {
        return $this->rootNodeRef;
    }

    /**
     * @param string $rootNodeRef
     */
    public function setRootNodeRef($rootNodeRef)
    {
        $this->rootNodeRef = $rootNodeRef;
    }
}