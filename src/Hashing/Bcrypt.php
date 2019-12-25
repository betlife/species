<?php

namespace Artisan\Hashing;

/**
 * Description of Bcrypt
 *
 * @author Cloud
 */
use RuntimeException;
use Artisan\Contracts\Hashing\Hasher;

class Bcrypt extends AbstractHasher implements Hasher
{

    /**
     * Number of hashing rounds
     * @var int 
     */
    protected $rounds          = 10;

    /**
     * 
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        $this->rounds = $this->cost($options);
    }

    /**
     * Creates hash from value
     * 
     * @param type $value
     * @param array $options
     * @return string
     * @throws RuntimeException
     */
    public function make($value, array $options = [])
    {
        $hashed = password_hash($value, $this->algorithm(), $this->options($options));
        if( $hashed === false ) {
            throw new RuntimeException();
        }

        return $hashed;
    }

    /**
     * 
     * @param string $data
     * @param string $hashedValue
     * @return bool
     * @throws RuntimeException
     */
    public function check($data, $hashedValue)
    {
        if( $this->verifyAlgorithm && $this->info($hashedValue)['algoName'] !== 'bcrypt' ) {
            throw new RuntimeException($this->invalid('BCRYPT'));
        }
        
        return parent::check($data, $hashedValue);
    }

    /**
     * 
     * @param string $hashedValue
     * @param array $options
     * @return type
     */
    public function needsRehash($hashedValue, array $options = [])
    {

        return password_needs_rehash($hashedValue, $this->algorithm(), $this->options($options));
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
        if($this->check($data, $hashedValue)){
            if($this->needsRehash($hashedValue, $options)){
                return $this->make($data, $options);
            }
            return $hashedValue;
        }
        return;
    }
    /**
     * 
     * @param int $rounds
     * @return $this
     */
    public function setRounds(int $rounds)
    {
        $this->rounds = $rounds;
        return $this;
    }

    /**
     * This code will benchmark your server to determine how high of a cost you can
     * afford. You want to set the highest cost that you can without slowing down
     * you server too much. 8-10 is a good baseline, and more is good if your servers
     * are fast enough. The code below aims for ≤ 50 milliseconds stretching time,
     * which is a good baseline for systems handling interactive logins.
     * 
     * @param int $timeTarget
     * @see PHP documentation for more info
     */
    public function roundsTest($timeTarget = 0.05)
    {
        $timeTarget = (int) $timeTarget;
        $rounds       = 8;
        do {
            $rounds++;
            $start = microtime(true);
            password_hash("test", $this->algorithm(), ["cost" => $rounds]);
            $end   = microtime(true);
        }
        while(($end - $start) < $timeTarget);

        return "Appropriate Cost Found: " . $rounds;
    }
    /**
     * 
     * @return type
     */
    public function algorithm()
    {
        return PASSWORD_BCRYPT;
    }
    /**
     * 
     * @param type $options
     * @return type
     */
    protected function cost($options)
    {
        return $options['cost'] ?? $this->rounds;
    }
    /**
     * 
     * @param type $options
     * @return type
     */
    protected function options($options)
    {
        return [
            'cost' => $this->cost($options)
        ];
    }

}
