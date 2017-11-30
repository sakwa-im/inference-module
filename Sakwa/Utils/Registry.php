<?php

namespace Sakwa\Utils;

use Sakwa\Logging\LogTrait;

class Registry
{
    use LogTrait;

    /**
     * @var Registry[]
     */
    public static $instance = array();

    /**
     * @var Guid
     */
    protected static $global_context = null;

    /**
     * @var mixed[]
     */
    protected $data = array();

    /**
     * Registry constructor.
     * It's not allowed to create an instance other then through getInstance so the constructor is private
     */
    private function __construct()
    {
        //
    }

    /**
     * It's not allowed to clone an instance outside managed context (this class) so private
     */
    private function __clone()
    {
        //
    }

    /**
     * Function for retrieving the correct instance of the registry based on context
     * When no context is given the global context is returned (and created if none exists)
     *
     * @param Guid|null $context
     * @return \Sakwa\Utils\Registry
     */
    public static function getInstance(\Sakwa\Utils\Guid $context = null)
    {
        if (is_null($context)) {
            $context = self::$global_context;

            if (is_null($context)) {
                $context              = new Guid();
                self::$global_context = $context;
            }
        }

        if (is_null(self::$global_context)) {
            self::$global_context = new Guid();
        }

        if (!isset(self::$instance["{$context}"])) {
            if (!$context->is(self::$global_context)) {
                self::debug('Creating new context: '.$context);
            }

            self::$instance["{$context}"] = new self();
        }
        return self::$instance["{$context}"];
    }

    /**
     * Alias of add
     * @param string $key
     * @param mixed $value
     * @param Guid $context
     */
    public static function set($key, $value, \Sakwa\Utils\Guid $context = null)
    {
        self::add($key, $value, $context);
    }

    /**
     * Function for adding a key to the registry
     * @param string $key
     * @param mixed $value
     * @param Guid $context
     */
    public static function add($key, $value, \Sakwa\Utils\Guid $context = null)
    {
        $instance = self::getInstance($context);
        $instance->addKey($key, $value);
    }

    /**
     * Function for retrieving values from the registry
     * @param string $key
     * @param \Sakwa\Utils\Guid $context = null
     * @return mixed
     */
    public static function get($key, \Sakwa\Utils\Guid $context = null)
    {
        $instance = self::getInstance($context);
        return $instance->getKey($key);
    }

    /**
     * @param string $key
     * @param \Sakwa\Utils\Guid $context
     * @return bool
     */
    public static function has($key, \Sakwa\Utils\Guid $context = null)
    {
        $instance = self::getInstance($context);
        return $instance->hasKey($key);
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function addKey($key, $value)
    {
        $this->data[(string)$key] = $value;
    }

    /**
     * @param mixed $key
     * @return mixed
     */
    public function getKey($key)
    {
        if ($this->hasKey($key)) {
            return $this->data[(string)$key];
        }

        return null;
    }

    /**
     * @param mixed $key
     * @return bool
     */
    public function hasKey($key)
    {
        return array_key_exists((string)$key, $this->data);
    }
}