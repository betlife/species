<?php

namespace Artisan\Http;

/**
 * Description of Headers
 *
 * @author Cloud
 */
use Artisan\Core\Collection\Collection;

class Headers extends Collection
{

    /**
     * Adds servers items to collection
     * 
     * @param mixed $key
     * @param mixed $item
     * @return \Artisan\Http\Headers
     */
    public function add($key, $item = null)
    {

        if( is_array($key) ) {
            foreach($key as $k => $v) {
                $this->add($k, $v);
            }
        }
        else {
            parent::add(static::normalize($key), [
                'value'    => $item,
                'originalkey' => $key
            ]);
        }
        return $this;
    }

    /**
     * Retrieves a header value as specified by the key
     * 
     * @param string $key
     * @param mixed $default
     * @return string
     */
    public function get($key, $default = null)
    {
        return parent::get(static::normalize($key), $default)['value'];
    }

    /**
     * Returns a collection of headers with a preserved creation state
     * 
     * @return array
     */
    public function all()
    {
        $output = [];
        foreach(parent::items() as $key => $value) {
            $output[$value['originalkey']] = $value['value'];
        }

        return $output;
    }

    /**
     * Creates a collection of item headers using $environment
     * 
     * @param \Artisan\Http\Environment $environment
     * @return $this
     */
    public function fromEnvironment(Environment $environment)
    {
        foreach($environment->items() as $key => $item) {
            $this->add($key, $item);
        }

        return $this;
    }

    /**
     * 
     * @param string $key
     * @param mixed $default
     * @return string
     */
    public function getOriginalKey($key, $default = null)
    {
        return parent::get(static::normalize($key), $default)['originalkey'];
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function remove($key)
    {
        parent::remove(static::normalize($key));
    }

    /**
     * 
     * @param string $key
     * @return string|bool
     */
    public static function normalize($key)
    {
        if( !is_string($key) ) {
            return false;
        }
        return strtolower($key);
    }

}
