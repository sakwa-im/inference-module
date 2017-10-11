<?php

namespace Sakwa\Utils;

use Sakwa\Exception;
use ArrayAccess;
use Iterator;

class EntityList implements ArrayAccess, Iterator
{
    /**
     * @var \Sakwa\Utils\Guid[]
     */
    protected $entities = array();

    /**
     * @var \Sakwa\Utils\Guid
     */
    protected $currentEntity;

    /**
     * Function for getting all set entities in the list
     *
     * @return array \Sakwa\Utils\Guid[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * Function for adding entities to the EntityList
     *
     * @param \Sakwa\Utils\Guid $guid
     *
     * @return boolean
     */
    public function add(\Sakwa\Utils\Guid $guid)
    {
        if (!$this->has($guid)) {
            $this->entities[] = $guid;

            return true;
        }

        return false;
    }

    /**
     * Function for checking the existence of a entity in the list
     *
     * @param \Sakwa\Utils\Guid $guid
     *
     * @return boolean
     */
    public function has(\Sakwa\Utils\Guid $guid)
    {
        return (!is_null($this->getEntityIndex($guid)));
    }

    /**
     * Function for getting the entity by guid
     *
     * @param Guid $guid
     *
     * @return null|\Sakwa\Utils\Guid
     */
    public function getEntity(\Sakwa\Utils\Guid $guid)
    {
        $index = $this->getEntityIndex($guid);

        if (!is_null($index)) {
            return $this->entities[$index];
        }
    }

    /**
     * Function for removing a entity form the list
     *
     * @param \Sakwa\Utils\Guid $guid
     */
    public function remove(\Sakwa\Utils\Guid $guid)
    {
        $index = $this->getEntityIndex($guid);

        if (!is_null($index)) {
            if (!is_null($this->currentEntity) && $this->currentEntity->is($this->entities[$index])) {
                $this->previous();
            }
            unset($this->entities[$index]);
        }
    }

    /**
     * Function for determining the index of a entity in the list
     *
     * @param \Sakwa\Utils\Guid $guid
     *
     * @return integer
     */
    protected function getEntityIndex(\Sakwa\Utils\Guid $guid)
    {
        foreach ($this->entities as $index => $entity) {
            if ($entity->is($guid)) {
                return $index;
            }
        }

        return null;
    }

    /************************************************************************/
    /* Functionality allowing direct member access on the EntityList object */
    /************************************************************************/

    /**
     * Magic setter for adding/removing entities
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $guid = new Guid($name);

        if (is_bool($value)) {
            if ($value) {
                $this->add($guid);
            }
            else {
                $this->remove($guid);
            }
        }
        else {
            if (is_null($value)) {
                $this->add($guid);
            }
        }
    }

    /**
     * Magic unset for removing entities from the list
     *
     * @param string $name
     */
    public function __unset($name)
    {
        $guid = new Guid($name);
        $this->remove($guid);
    }

    /**
     * Function for checking the existence of an entity in the list
     *
     * @param string $name
     *
     * @return boolean
     */
    public function __get($name)
    {
        $guid = new Guid($name);

        return $this->offsetExists($guid);
    }

    /**
     * Function for checking the existence of a entity in the list
     *
     * @param string $name
     *
     * @return boolean
     */
    public function __isset($name)
    {
        $guid = new Guid($name);

        return $this->has($guid);
    }

    /******************************************************************/
    /* Functionality allowing array access on the EntityList object   */
    /******************************************************************/

    /**
     * Function for checking the existence of an entity in the list
     *
     * @param Guid $offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {

        if (($offset instanceof \Sakwa\Utils\Guid) == false) {
            return false;
        }

        return $this->has($offset);
    }

    /**
     * Function for checking the existence of an entity in the list
     *
     * @param \Sakwa\Utils\Guid $offset
     *
     * @return boolean
     * @throws Exception
     */
    public function offsetGet($offset)
    {

        if (($offset instanceof \Sakwa\Utils\Guid) == false) {
            throw new Exception('It\'s not allowed to access the EntityList using the internal index');
        }

        return $this->offsetExists($offset);
    }

    /**
     * Function for adding/removing entities
     *
     * @param null|\Sakwa\Utils\Guid         $offset
     * @param boolean|null|\Sakwa\Utils\Guid $value
     *
     * @return null
     * @throws Exception
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            //We only want to process \Sakwa\Utils\Guid items items

            if (($value instanceof \Sakwa\Utils\Guid) == false) {
                throw new Exception('It\'s not allowed to access the EntityList using the internal index');
            }
            $this->add($value);
        }
        else {

            if ($offset instanceof \Sakwa\Utils\Guid) {
                if ($offset === $value || (is_bool($value) && $value)) {
                    $this->add($offset);
                }
                else {
                    if (is_null($value) || (is_bool($value) && !$value)) {
                        $this->remove($offset);
                    }
                }
            }
        }
        return;
    }

    /**
     * Function for removing a entity from the list
     *
     * @param \Sakwa\Utils\Guid $offset
     *
     * @return null
     */
    public function offsetUnset($offset)
    {

        if (($offset instanceof \Sakwa\Utils\Guid) == false) {
            return;
        }

        $this->remove($offset);

        return;
    }

    /******************************************************************/
    /* Functionality for iterating over entities using a foreach loop */
    /******************************************************************/

    /**
     * Iterator support function for getting the current array item
     *
     * @return \Sakwa\Utils\Guid
     */
    public function current()
    {
        if (is_null($this->currentEntity)) {
            $this->rewind();
        }

        return $this->currentEntity;
    }

    /**
     * Iterator support function for returning the array key
     *
     * @return \Sakwa\Utils\Guid
     */
    public function key()
    {
        return $this->currentEntity;
    }

    /**
     * Iterator support function for setting the previous entity active
     */
    public function next()
    {
        if (is_null($this->currentEntity)) {
            $this->rewind();
        }
        else {
            $currentEntity = $this->currentEntity;
            $currentEntityFound = false;

            foreach ($this->entities as $entity) {
                if ($currentEntityFound) {
                    $this->currentEntity = $entity;
                    break;
                }

                if ($entity->is($this->currentEntity)) {
                    $currentEntityFound = true;
                }
            }

            if ($currentEntity->is($this->currentEntity)) {
                $this->currentEntity = null;
            }
        }
    }

    /**
     * Function for setting the previous entity active
     */
    protected function previous()
    {
        $previous_entity = null;

        if (!is_null($this->currentEntity)) {
            foreach ($this->entities as $entity) {
                if (is_null($previous_entity)) {
                    $previous_entity = $entity;
                }

                if ($entity->is($this->currentEntity)) {
                    $this->currentEntity = $previous_entity;
                    break;
                }

                $previous_entity = $entity;
            }
        }
    }

    /**
     * Iterator support function rewind
     */
    public function rewind()
    {
        $this->currentEntity = null;
        $keys = array_keys($this->entities);

        if (count($keys) > 0) {
            $this->currentEntity = $this->entities[$keys[0]];
        }
    }

    /**
     * Iterator support function for checking validity of current array pointer
     *
     * @return boolean
     */
    public function valid()
    {
        return !is_null($this->currentEntity);
    }
}