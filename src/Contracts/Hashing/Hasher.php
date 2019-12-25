<?php

namespace Artisan\Contracts\Hashing;

/**
 *
 * @author Cloud
 */
interface Hasher
{

    /**
     * Get the information about the given hashed value
     * 
     * @param string $hashedValue
     */
    public function info($hashedValue);

    /**
     * Hash the given value
     * 
     * @param string $value
     * @param array $options
     */
    public function make($value, array $options = []);

    /**
     * Validates a value against a hashed value
     * 
     * @param string $data
     * @param string $hashedValue
     */
    public function check($data, $hashedValue);
    /**
     * 
     * @param string $hashedValue
     * @param array $options
     */
    public function needsRehash($hashedValue, array $options = []);
    /**
     * 
     * @param type $data
     * @param type $hashedValue
     * @param array $options
     */
    public function rehash($data, $hashedValue, array $options = []);
}
