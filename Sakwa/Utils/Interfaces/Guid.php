<?php

namespace Sakwa\Utils\Interfaces;

interface Guid {

    /**
     * Guid constructor.
     *
     * @param null $guid
     */
    public function __construct($guid = null);

    /**
     * Returns the guid value
     * @return string $guid
     */
    public function getGuid();

    /**
     * set the guid value
     * @param string $guid
     */
    public function setGuid($guid = null);

    /**
     * Returns the current guid value
     * @return string
     */
    public function __toString();

    /**
     * Function for checking if a guid matches this guid
     * @param \Sakwa\Utils\Guid $guid
     * @return boolean
     */
    public function is(\Sakwa\Utils\Guid $guid);

    /**
     * Function for validating a guid string.
     * @param string $guid
     * @return boolean
     */
    public function validateGuidString($guid);

    /**
     * Function for validating and setting the guid string for this Guid object
     * @param string $guid
     * @throws Exception
     */
    public function parseString($guid);
}