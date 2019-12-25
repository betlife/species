<?php

namespace Artisan\Hashing;

/**
 * Description of Hasher
 *
 * @author Cloud
 */
abstract class AbstractHasher
{
    /**
     *
     * @var bool 
     */
    protected $verifyAlgorithm = true;
    /**
     * 
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->verifyAlgorithm = $options['verify'] ?? $this->verifyAlgorithm;
    }
    /**
     * 
     * @param string $hash
     * @return array
     */
    public function info($hash)
    {
        return password_get_info($hash);
    }
    /**
     * 
     * @param type $data
     * @param type $hashedValue
     * @return type
     */
    public function check($data, $hashedValue)
    {
        if(!is_string($data) || !is_string($hashedValue)){
            return false;
        }
        return password_verify($data, $hashedValue);
    }
    /**
     * Name of hashing algorithm to use
     * 
     * @return string
     */
    abstract public function algorithm();
    /**
     * 
     * @param string $name
     * @return string
     */
    protected function invalid($name)
    {
        return sprintf('This password was not hashed with the %s hashing algorithm', $name);
    }
    /**
     * 
     * @param array $options Description
     */
    abstract protected function options(array $options);
}
