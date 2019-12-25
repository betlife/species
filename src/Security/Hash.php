<?php

namespace Artisan\Security;

/**
 * Description of Hash
 *
 * @author Cloud
 */
use Closure;
use RuntimeException;

class Hash
{

    //put your code here
    const _method = 'sha256';

    /**
     * 
     * @param string $string
     * @param string|null $method
     * @return string
     */
    public static function hash($string, $method = null)
    {
        
        return hash(static::_method($method), $string);
    }

    /**
     * 
     * @param string $string
     * @param string $key
     * @param string|null $method
     * @return string
     */
    public static function hmac($string, $key, $method = null)
    {
        return hash_hmac(static::_method($method), $string, $key);
    }

    /**
     * 
     * @param string $original
     * @param string|Closure $compare
     * @return bool
     */
    public static function equals($original, $compare)
    {

        if( ($compare instanceof Closure ) ) {
            echo 'true';
            return static::_equals($original, with(new static, $compare));
        }
        return static::_equals($original, static::hash($compare));
    }

    /**
     * 
     * @param string $image
     * @param string $compare
     * @return bool
     */
    private static function _equals($image, $compare)
    {
        if(!is_string($image) || !is_string($compare)){
            return false;
        }
        return hash_equals($image, $compare);
    }
    public static function password($key, $salt, $method = 'sha256')
    {
        return hash_pbkdf2(static::_method($method), $key, $salt, 10000, 64, false);
    }
    /**
     * 
     * @param string $method
     * @return string
     * @throws RuntimeException
     */
    private static function _method($method)
    {
        $method = $method ?? static::_method;

        $availableAlgorithms = openssl_get_md_methods();
        if( !in_array($method, $availableAlgorithms) ) {
            throw new RuntimeException(sprintf(
                    "The hash method '%s' not found. See list of available algoritms: %s", $method, implode(',', $availableAlgorithms)
            ));
        }

        return $method;
    }

}
