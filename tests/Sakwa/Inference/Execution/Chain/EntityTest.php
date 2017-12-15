<?php

namespace Tests\Sakwa\Inference\Execution\Chain;

use Sakwa\Inference\Execution\Chain\Entity;

class EntityTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function currentNodeShouldGetNullWhenNoNodeKeyIsSet()
    {
        $entity = new Entity(array());
        $this->assertNull($entity->currentNode());
    }
    /**
     * @test
     */
    public function isLeafNodeShouldGetNullWhenNoNodeKeyIsSet()
    {
        $entity = new Entity(array());
        $this->assertFalse($entity->isLeafNode());
    }
}