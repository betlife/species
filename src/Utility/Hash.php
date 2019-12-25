<?php

namespace Artisan\Utility;

/**
 * Description of Hash
 *
 * @author Cloud
 */
use stdClass;
use Countable;
use ArrayAccess;
use ArrayObject;
use ArrayIterator;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use InvalidArgumentException;

class Hash
{

    /**
     * 
     * @param string $path
     * @param array|\Countable $array
     * @param mixed $default
     * @return mixed
     * @throws InvalidArgumentException
     */
    public static function get($path, $array, $default = null)
    {
        if( !(is_array($array) || $array instanceof Countable) ) {
            throw new InvalidArgumentException();
        }

        $path = explode('.', $path);
        foreach($path as $bit) {
            $array = static::retrive($bit, $array, $default);
        }

        return $array;
    }

    /**
     * Does array have this value
     * 
     * @param string $value
     * @param array  $array
     * @return bool
     */
    public static function in($value, $array)
    {
        return in_array($value, $array);
    }

    /**
     * Does the supplied key exists in array
     * 
     * @param mixed $key
     * @param ArrayAccess|array $array
     * @return bool
     */
    public static function exists($key, $array)
    {
        if( ($array instanceof ArrayAccess ) ) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * 
     * @param type $key
     * @param ArrayAccess $array
     * @param type $default
     * @return ArrayAccess
     */
    public static function retrive($key, &$array, $default = null)
    {
        if( $array instanceof ArrayAccess ) {
            return $array->offsetGet($key);
        }

        if( static::exists($key, $array) ) {
            return $array[$key];
        }

        return $default;
    }

    /**
     * 
     * @param string $key
     * @param string $value
     * @param array $items
     * @return array
     */
    public static function add($key, $value = null, &$items = [])
    {
        if( is_array($key) ) {
            foreach($key as $k => $v) {
                static::add($k, $v, $items);
            }
            return $items;
        }

        return $items[$key] = $value;
    }

    /**
     * 
     * @param array $items
     * @return array
     */
    public static function combine(...$items)
    {
        return array_merge(...$items);
    }

    /**
     * 
     * @param array $first
     * @param array $second
     * @return array
     */
    public static function diff($first, $second)
    {
        return (array_diff_key($first, $second) + $second);
    }

    /**
     * 
     * @param array|ArrayAccess|Countable $array
     * @return bool
     */
    public static function countable($array)
    {
        return is_array($array) || ($array instanceof ArrayAccess) || ($array instanceof Countable);
    }

    /**
     * 
     * @param array $array
     * @param int $flags
     * @return ArrayIterator
     */
    public static function getArrayIterator($array, $flags = null)
    {
        return new ArrayIterator($array, $flags);
    }

    /**
     * 
     * @param array $array
     * @param int $flags
     * @return ArrayObject
     */
    public static function getArrayObject($array, $flags = null)
    {
        return new ArrayObject($array, $flags);
    }

    /**
     * Returns array items by key count
     * Note: An array array is not counted
     * 
     * @param array $array 
     * @return int
     */
    public static function count($array)
    {
        return static::getArrayIterator($array)->count();
    }

    /**
     * Flattens an array
     * 
     * @param array $array
     * @return RecursiveIteratorIterator
     */
    public static function collaspe($array)
    {
        $output   = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
        foreach($iterator as $item) {
            $output[] = $item;
        }
        return $output;
    }

    /**
     * Removes an item from an from either with or without "dot" notation
     * 
     * @param array $array
     * @param string $key
     * @return void
     */
    public static function remove(&$array, $key)
    {
        $original = &$array;
        $keys     = (array) $key;

        if( !static::count($keys) ) {
            return;
        }

        foreach($keys as $key) {
            // Executes if key is without "dot" notation
            if( static::exists($key, $array) ) {
                unset($array[$key]);

                continue;
            }

            $parts = explode('.', $key);
            $array = &$original;

            while(count($parts) > 1) {
                $part = array_shift($parts);

                if( isset($array[$part]) && static::countable($array[$part]) ) {
                    $array = &$array[$part];
                }
                else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }

    /**
     * Is array of type associative
     * 
     * @param array $array
     * @return int
     */
    public static function isAssoc($array)
    {
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    /**
     * Is array of type numeric
     * 
     * @param array $array
     * @return bool
     */
    public static function isNumeric($array)
    {
        return !static::isAssoc($array);
    }

    public static function toArray($param)
    {
        
    }
    /**
     * Converts an array to object
     * 
     * @param array $array
     * @return stdClass
     */
    public static function toObject(array $array)
    {
        $resolver = new stdClass();
        foreach($array as $key => $value) {
            if( is_array($value)){
                $resolver->{$key} = static::toObject($value);
                continue;
            }
            
            $resolver->{$key} = $value;
        }
        
        return $resolver;
    }
public static function functionName($param)
    {
        
    }
    public static function test($key, &$array)
    {
        $bits = explode('.', $key);
        foreach($bits as $k) {
            if( isset($array[$k]) )
                unset($array[$k]);
        }

        return $array;
    }

}
