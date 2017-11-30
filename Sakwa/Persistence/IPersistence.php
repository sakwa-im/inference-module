<?php

namespace Sakwa\Persistence;

/**
 * Interface IPersistence
 *
 */
interface IPersistence
{

    /**
     * @return bool Whether the data was successfully parsed.
     */
    public function open();

    /**
     * @return string The version of the file.
     */
    public function getFileVersion();

    /**
     * Select the next record.
     *
     * @return bool
     */
    public function nextRecord();

    /**
     * @param string $name
     * @param string $defaultValue
     *
     * @return mixed
     */
    public function getFieldValue($name, $defaultValue = null);

    /**
     * @param boolean $name
     */
    public function hasField($name);
}