<?php

namespace Artisan\Core\Collection;

/**
 * Description of Collection
 *
 * @author Cloud
 */
use Countable;
use ArrayIterator;
use Artisan\Utility\Hash;

class Collection implements Countable
{

    /**
     *
     * @var array 
     */
    protected $items = [];
    /**
     * 
     * 
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->add($data);
    }

    /**
     * Does this collection have the specified key?
     * 
     * @param type $key
     * @return type
     */
    public function has($key)
    {
        return Hash::exists($key, $this->items);
    }

    /**
     * Add an item to collection
     * 
     * @param type $key
     * @param type $item
     * @return $this
     */
    public function add($key, $item = null)
    {
        Hash::add($key, $item, $this->items);
        return $this;
    }

    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Hash::get($key, $this->items(), $default);
    }

    /**
     * Returns the items count in collection
     * 
     * @return int
     */
    public function count()
    {
        return $this->getIterator()->count();
    }

    /**
     * Container of data collection
     * 
     * @return array
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * Resets collection to empty
     * 
     * @return void
     */
    public function reset()
    {
        $this->items = [];
    }

    /**
     * Remove item from collection as specified by the key
     * 
     * @param string|array $key
     * @return \Artisan\Core\Collection
     */
    public function remove($key)
    {
        Hash::remove($key, $this->items);
        return $this;
    }

    /**
     * Returns new instance of ArrayIterator
     * 
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items());
    }

}
