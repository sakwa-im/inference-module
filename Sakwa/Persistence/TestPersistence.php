<?php

namespace Sakwa\Persistence;

/**
 * Class XmlPersistence
 *
 */
class TestPersistence implements IPersistence
{
    protected $data = array();
    protected $currentIndex = null;

    /**
     * TestPersistence constructor.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return bool Whether the data was successfully parsed.
     */
    public function open()
    {
        return (count($this->data) > 0);
    }

    /**
     * @return string The version of the file.
     */
    public function getFileVersion()
    {
        return 'test';
    }

    /**
     * Select the next record.
     *
     * @return bool
     */
    public function nextRecord()
    {
        $keys = array_keys($this->data);

        if (is_null($this->currentIndex)) {
            if (count($keys) > 0) {
                $this->currentIndex = $keys[0];
            }
        }
        else {
            $foundKey           = false;
            $currentIndex       = $this->currentIndex;
            $this->currentIndex = null;

            foreach ($keys as $key) {
                if ($foundKey) {
                    $this->currentIndex = $key;
                    break;
                }

                if ($currentIndex == $key) {
                    $foundKey = true;
                }
            }
        }

        return !is_null($this->currentIndex);
    }

    /**
     * @param string $name
     * @param string $defaultValue
     *
     * @return mixed
     */
    public function getFieldValue($name, $defaultValue = null)
    {
        return $this->data[$this->currentIndex][$name];
    }

    /**
     * @param boolean $name
     */
    public function hasField($name)
    {
        if (is_null($this->currentIndex)) {
            return false;
        }

        return isset($this->data[$this->currentIndex][$name]) && !is_null($this->data[$this->currentIndex][$name]);

    }

}