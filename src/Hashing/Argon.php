<?php

namespace Artisan\Hashing;

/**
 * Description of Argon
 *
 * @author Cloud
 */
use RuntimeException;
use Artisan\Contracts\Hashing\Hasher;

class Argon extends AbstractHasher implements Hasher
{

    /**
     *
     * @var int 
     */
    protected $time   = 2;
    /**
     *
     * @var int 
     */
    protected $memory = 1024;
    /**
     *
     * @var int 
     */
    protected $thread = 2;

    /**
     * 
     * @param type $options
     */
    public function __construct($options = [])
    {
        $this->time   = $this->time($options);
        $this->thread = $this->thread($options);
        $this->memory = $this->memory($options);
        parent::__construct($options);
    }

    /**
     * 
     * @param type $value
     * @param array $options
     * @return type
     * @throws RuntimeException
     */
    public function make($value, array $options = [])
    {
        $hashed = password_hash($value, $this->algorithm(), [
            $this->options($options)
        ]);

        if( $hashed === false ) {
            throw new RuntimeException();
        }

        return $hashed;
    }

    /**
     * 
     * @param type $data
     * @param type $hashedValue
     * @throws RuntimeException
     */
    public function check($data, $hashedValue)
    {
        if( $this->verifyAlgorithm && $this->info($hashedValue)['algoName'] !== 'argon2i' ) {
            throw new RuntimeException($this->invalid('ARGON2I'));
        }
        return parent::check($data, $hashedValue);
    }

    /**
     * 
     * @param string $hashedValue
     * @param array $options
     * @return bool
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return password_needs_rehash($hashedValue, $this->algorithm(), [
            $this->options($options)
        ]);
    }

    /**
     * 
     * @param string $data
     * @param string $hashedValue
     * @param array $options
     * @return string|void
     */
    public function rehash($data, $hashedValue, array $options = [])
    {
        if( $this->check($data, $hashedValue) ) {
            if( $this->needsRehash($hashedValue, $options) ) {
                return $this->make($data, $options);
            }
            return $hashedValue;
        }
        return;
    }

    /**
     * 
     * @return int
     */
    public function algorithm()
    {
        return PASSWORD_ARGON2I;
    }

    /**
     * 
     * @param type $options
     * @return type
     */
    protected function time($options)
    {
        return $options['time_cost'] ?? $this->time;
    }

    /**
     * 
     * @param type $options
     * @return type
     */
    protected function memory($options)
    {
        return $options['memory_cost'] ?? $this->memory;
    }

    /**
     * 
     * @param type $options
     * @return type
     */
    protected function thread($options)
    {
        return $options['threads'] ?? $this->thread;
    }

    /**
     * 
     * @param type $options
     * @return type
     */
    protected function options($options)
    {
        return [
            'time_cost'   => $this->time($options),
            'threads'     => $this->thread($options),
            'memory_cost' => $this->memory($options)
        ];
    }

}
