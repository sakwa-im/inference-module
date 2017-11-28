<?php

use Sakwa\Utils\EntityList;
use Sakwa\Utils\Guid;


class EntityListTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function shouldBeAbleToAddEntitiesToTheList()
    {
        $list = new EntityList();
        $guid1 = new Guid();

        $this->assertTrue($list->add($guid1));  // First time it's added to the list, so true
        $this->assertFalse($list->add($guid1)); // Second time the item already exists in the list, so false
        $this->assertTrue($list->has($guid1));  // The item should exist in the list, so true


        $guid2 = new Guid();
        $list->{$guid2} = null;
        $this->assertTrue($list->has($guid2));  // The item should exist in the list, so true
        $list->{$guid2} = false;
        $this->assertFalse($list->has($guid2)); // The item should exist in the list, so false
        $list->{$guid2} = true;
        $this->assertTrue($list->has($guid2));  // The item should exist in the list, so true
        $list[$guid2] = null;
        $this->assertFalse($list->has($guid2)); // The item should exist in the list, so false
        $list[$guid2] = $guid2;
        $this->assertTrue($list->has($guid2));  // The item should exist in the list, so true
    }

    /**
     * @test
     */
    public function shouldBeAbleToValidateTheExistenceOfItemsInTheList()
    {
        $list = new EntityList();
        $guid = new Guid();

        $this->assertFalse(isset($list[$guid])); //should return false

        $list[] = $guid;
        $this->assertNotNull($list->getEntity($guid)); //This should return a guid object

        $this->assertTrue(isset($list->{$guid}));//This should return true


        $guid = new Guid();
        $this->assertNull($list->getEntity($guid)); //This should return null
    }

    /**
     * @test
     */
    public function shouldBeAbleToGetItemsFromTheList()
    {
        $list = new EntityList();
        $guid = new Guid();
        $list[] = $guid;

        $this->assertNotNull($list->{$guid});
    }

    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldNotBeAbleToUseTheInternalIndex()
    {
        $list = new EntityList();
        $guid = new Guid();
        $list[] = $guid;

        unset($list[0]);

        $item = $list[0];
    }

    /**
     * @test
     * @expectedException \Sakwa\Exception
     */
    public function shouldNotBeAbleToSetItemsOtherThenGuid()
    {
        $list = new EntityList();
        $list[] = 123;
    }

    /**
     * @test
     */
    public function shouldBeAbleToRemoveItemsFromTheList()
    {
        $list = new EntityList();
        $guid1 = new Guid();
        $guid2 = new Guid();
        $guid3 = new Guid();
        $guid4 = new Guid();

        $list[] = $guid1;
        $list[] = $guid2;
        $list[] = $guid3;
        $list[] = $guid4;

        $this->assertTrue($list[$guid1]);
        $list->remove($guid1);
        $this->assertFalse($list[$guid1]);

        $this->assertTrue($list[$guid2]);
        $list[$guid2] = false;
        $this->assertFalse($list[$guid2]);

        $this->assertTrue($list[$guid3]);
        unset($list[$guid3]);
        $this->assertFalse($list[$guid3]);

        $this->assertTrue($list[$guid4]);
        unset($list->{$guid4});
        $this->assertFalse($list[$guid4]);

        $this->assertCount(0, $list);
    }

    /**
     * @test
     */
    public function shouldBeAbleToIterateThroughTheEntities()
    {
        $list = new EntityList();
        $guid1 = new Guid();
        $guid2 = new Guid();
        $guid3 = new Guid();
        $guid4 = new Guid();

        $list[] = $guid1;
        $this->assertNull($list->key());
        $this->assertNotNull($list->current());
        $this->assertNotNull($list->key());

        foreach($list as $guid) {
            $list->remove($guid);
        }

        $this->assertCount(0, $list);

        $list[] = $guid2;
        $list[] = $guid3;
        $list[] = $guid4;

        foreach($list as $guid) {
            $this->assertInstanceOf('\Sakwa\Utils\Guid', $guid);
        }
    }

    /**
     * @test
     */
    public function shouldBeAbleToGetTheInternalEntityList()
    {
        $list = new EntityList();
        $guid1 = new Guid();
        $guid2 = new Guid();

        $list[] = $guid1;
        $list[] = $guid2;

        $entities = $list->getEntities();

        $this->assertCount(2, $entities);

        foreach($entities as $guid) {
            $this->assertInstanceOf('\Sakwa\Utils\Guid', $guid);
        }
    }
}