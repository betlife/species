<?php

namespace Artisan\Utility;

/**
 * Description of Registry
 *
 * @author Cloud
 */
use RuntimeException;
class Registry
{
    /**
     * Container of registered instances
     * @var array
     */
    protected static $_instances = [];
    /**
     * We do not want instantiation of this class
     */
    private function __construct()
    {
        // Do nothing
    }
    /**
     * We definitely do not want cloning either
     */
    private function __clone()
    {
        // Do nothing
    }
    /**
     * Determines if the named instance if available in registry
     * @param string $name
     * @return bool
     */
    public static function exists($name)
    {
        return Hash::exists($name, static::instances());
    }
    /**
     * Register an instance to registry
     * @param string $name
     * @param mixde $class
     */
    public static function set($name, $class)
    {
        return new static(Hash::add($name, $class, static::$_instances));
    }
    /**
     * Access a registered instance from registry
     * 
     * @param string $name
     * @return object
     * @throw RuntimeException
     */
    public static function get($name)
    {
        throw_unless(static::exists($name), new RuntimeException(sprintf(
                "Trying to fetch unregistered object '%s' from registry", $name
                )));
        return Hash::get($name, static::$_instances);
    }
    /**
     * 
     * @param callable $callback
     * @return Closure
     */
    public static function call(callable $callback)
    {
        return with(new static, $callback);
    }
    /**
     * Erase a registered instance from registry
     * 
     * @param string $name
     * @return static
     */
    public static function erase($name)
    {
        throw_unless(static::exists($name), 'RuntimeException', "'{$name}' not registered");
        return new static(Hash::remove(static::$_instances, $name));
    }
    /**
     * Collection of registered instances
     * 
     * @return array
     */
    public static function instances()
    {
        return static::$_instances;
    }
}
