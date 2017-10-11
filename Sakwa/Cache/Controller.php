<?php

namespace Sakwa\Cache;


class Controller
{
    protected static $instance = null;

    protected $data = array();

    private function __construct()
    {
        //This private constructor prevents "$obj = new Controller();" from outside of this class
    }

    /**
     * @return null|\Sakwa\Cache\Controller
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = serialize($value);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return unserialize($this->data[$key]);
    }

    /**
     * @param string $key
     *
     * @return boolean
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }
}