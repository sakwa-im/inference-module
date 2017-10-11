<?php

namespace Sakwa\Utils;

use Sakwa\Exception;
use Sakwa\Utils\Interfaces;

class Guid implements Interfaces\Guid
{
    public $guid                = null;
    protected static $guidCount = 0;

    public function __construct($guid = null)
    {
        if (!is_null($guid)) {
            $this->parseString($guid);
        }
        else {
            $this->setGuid($this->generateGuid());
        }
    }

    /**
     * Returns the guid value
     * @return string $guid
     */
    public function getGuid()
    {
        return (string) $this->guid;
    }

    /**
     * set the guid value
     * @param string $guid
     */
    public function setGuid($guid = null)
    {
        $this->guid = (string) $guid;
    }

    /**
     * Returns the current guid value
     * @return string
     */
    public function __toString()
    {
        return $this->getGuid();
    }

    /**
     * Function for checking if a guid matches this guid
     * @param \Sakwa\Utils\Guid $guid
     * @return boolean
     */
    public function is(\Sakwa\Utils\Guid $guid)
    {
        return ($this->getGuid() == $guid->getGuid());
    }

    /**
     * Function for generating GUID's
     * @return string
     */
    protected function generateGuid()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        mt_srand((microtime(true) * 100000));
        return substr(sprintf('%04X%04X-%04X-%04X-%04X-%03X%05X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 4096), ++self::$guidCount, mt_rand(0, 32768)), 0, 36);
    }

    /**
     * Function for validating a guid string.
     * @param string $guid
     * @return boolean
     */
    public function validateGuidString($guid)
    {
        if (preg_match('/^\{?[A-Za-z0-9]{8}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{12}\}?$/', $guid)) {
            return true;
        }
        return false;
    }

    /**
     * Function for validating and setting the guid string for this Guid object
     * @param string $guid
     * @throws Exception
     */
    public function parseString($guid)
    {
        if (!$this->validateGuidString($guid)) {
            throw new Exception("Invalid GUID '{$guid}' string.");
        }
        $this->setGuid($guid);
    }
}