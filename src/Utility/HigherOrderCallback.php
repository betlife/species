<?php

namespace Artisan\Utility;

/**
 * Description of Callable
 *
 * @author Cloud
 */
class HigherOrderCallback
{
    /**
     *
     * @var type 
     */
    protected $target = null;
    /**
     * 
     * @param type $target
     */
    public function __construct($target)
    {
        $this->target = $target;
    }
    /**
     * 
     * @param type $method
     * @param type $arguments
     * @return type
     */
    public function __call($method, $arguments)
    {
        $this->target->{$method}(...$arguments);
        return $this->target;
    }
}
