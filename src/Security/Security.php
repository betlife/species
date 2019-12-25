<?php

namespace Artisan\Security;

/**
 * Description of Security
 *
 * @author Cloud
 */
use Closure;
use RuntimeException;
class Security
{
    //put your code here
    public static function hash($command)
    {
        return static::_invoke($command, new Hash);
    }
    public static function anonymous($newthis, $callable)
    {
      return $callable($newthis);
    }
    public static function openssl($command)
    {
        return static::_invoke($command, function() use ($arguments){
            return new Openssl(...$arguments);
        });
    }
    public static function randomBytes($length = 64)
    {
        if( function_exists('random_bytes')){
            return random_bytes($length);
        }
        
        if (!function_exists('openssl_random_pseudo_bytes')) {
            throw new RuntimeException(
            'System could not find a cryptographically secured random data function ' .
            'Consider installing openssl extension. ' 
            );
        }
        $cstrong = true;
        return openssl_random_pseudo_bytes($length, $cstrong);
    }
    /**
     * 
     * @param string $length
     * @return string
     */
    public static function randomHash($length = 64)
    {
        return bin2hex(static::randomBytes(
                        ceil($length / 2)
                ));
    }
    /**
     * 
     * @param Closure $callable
     * @param \Artisan\Security\Hash|\Artisan\Security\Openssl $security
     * @return type
     */
    private static function _invoke($callable, $security)
    {
        if($callable instanceof Closure){
            return $callable->call($security);
        }
        return;
    }
}
