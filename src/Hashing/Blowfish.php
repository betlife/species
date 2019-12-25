<?php

namespace Artisan\Hashing;

/**
 * Description of Blowfish
 *
 * @author Cloud
 */
use Artisan\Contracts\Hashing\Hasher;
class Blowfish extends AbstractHasher implements Hasher
{
    //put your code here
    public function algorithm()
    {
        return CRYPT_BLOWFISH;
    }
    protected function options(array $options)
    {
        ;
    }

    public function make($value, array $options = array())
    {
        
    }

    public function needsRehash($hashedValue, array $options = array())
    {
        
    }

}
