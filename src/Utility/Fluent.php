<?php

namespace Artisan\Utility;

/**
 * Description of Fluent
 *
 * @author Cloud
 */
use Countable;
use ArrayAccess;

class Fluent implements Countable, ArrayAccess
{

    /**
     *
     * @var type 
     */
    protected $attributes = [];

    /**
     * Creates an instance of fluent
     * 
     * @param array|object $attributes
     */
    public function __construct($attributes = [])
    {
        foreach($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Get an attribute from the fluent collection
     * 
     * @param string $key
     * @param mixed $default
     * @return type
     */
    public function getAttribute($key, $default = null)
    {
        return Hash::get($key, $this->attributes, get_value($default));
    }

    /**
     * Determine the number of attributes in the fluent instance
     * 
     * @return int
     */
    public function count()
    {
        return count($this->attributes);
    }

    /**
     * 
     * @return string
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
    /**
     * 
     * @return type
     */
    public function toObject()
    {
        return Hash::toObject($this->getAttributes());
    }
    /**
     * Determine if offset exists in fluent instance
     * 
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->attributes);
    }

    /**
     * Get an offset from the fluent instance
     * 
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set an offset in the fluent instance
     * 
     * @param string $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * Unset an offset from the fluent instance
     * 
     * @param string $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Dynamically fetch an attribute from the fluent instance
     * 
     * @param string $name
     * @return mixed
     */
    public function __getAttribute($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * Dynamically set an attribute in the fluent instance
     * 
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * Dynamically determine if a named attribute exists in the fluent instance
     * 
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Dynamically unset an attribute from the fluent instance
     * 
     * @param string $name
     */
    public function __unset($name)
    {
        $this->offsetUnset($name);
    }

    /**
     * 
     * @param string $method
     * @param array $arguments
     * @return $this
     */
    public function __call($method, $arguments)
    {
        $this->attributes[$method] = count($arguments) ? $arguments[0] : true;
        return $this;
    }

}
