<?php

/**
 * 
 * (c) Suurshater Gabriel <suurshater.ihyongo@st.futminna.edu.ng>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Artisan\Utility;

/**
 * Description of Request
 *
 * @author Cloud
 */
use RuntimeException;
use Artisan\Http\Headers;
use Artisan\Http\Environment;
class Request
{
    /**
     * Container of $_GET and $_POST combined
     * @var array
     */
    protected static $_all       = [];

    /**
     * List of supported HTTP request methods
     * @var array 
     */
    protected static $_supported = [
        'get', 'put', 'post', 'delete', 'update'
    ];

    /**
     * 
     * @param string $verb
     * @return bool
     * @throws RuntimeException
     */
    public static function exists($verb = 'post')
    {
        if( !Hash::in($verb, static::$_supported) ) {
            throw new RuntimeException("The HTTP request method '{$verb}' is not supported");
        }

        return static::requestMethod($verb);
    }

    /**
     * 
     * @param string $key
     * @return string
     */
    public static function get($key, $default = null)
    {
        static::all();
        return escape(Hash::retrive($key, static::$_all, $default));
    }

    /**
     * 
     * @param string $key
     * @return string
     */
    public static function old($key)
    {
        $old = static::get($key);
        if( !empty($old) ) {
            Hash::remove(static::$_all, $key);
        }
        return $old;
    }
    /**
     * The request URI of the originating request source
     * @return string
     */
    public static function uri()
    {
        return static::environment()->get('request_uri');
    }
    /**
     * 
     * @return string
     */
    public static function host()
    {
        return Request::environment()->get('http_host');
    }
    /**
     * 
     * @return string
     */
    public static function protocol()
    {
        return Request::environment()->get('request_protocol');
    }
    /**
     * 
     * @return string
     */
    public static function method()
    {
        return static::environment()->get('request_method');
    }
    /**
     * 
     * @param string $key
     * @param string|null $default
     * @return mixed
     */
    public static function server($key, $default = null)
    {
        $server = static::environment()->items();
        return Hash::retrive($key, $server, $default);
    }
    /**
     * List of supported HTTP verbs
     * @return array
     */
    public static function supported()
    {
        return static::$_supported;
    }

    /**
     * Is the HTTP request method GET?
     * @return bool
     */
    public static function isGet()
    {
        return static::requestMethod('get');
    }

    /**
     * Is the HTTP request method POST?
     * @return bool
     */
    public static function isPost()
    {
        return static::requestMethod('post');
    }
    /**
     * Determines an actual HTTP request method
     * @internal
     * @param string $method
     * @return bool
     */
    protected static function requestMethod($method)
    {
        return static::environment()->get('request_method') === strtoupper($method);
    }
    /**
     * 
     * @return \Artisan\Http\Headers
     */
    public static function environment()
    {
        return (new Headers())->fromEnvironment(Environment::create($_SERVER));
    }
    /**
     * Combines both $_GET and $_POST into a single collection
     * @return type
     */
    protected static function all()
    {
        static::$_all = Hash::combine($_GET, $_POST);
    }
    public static function functionName()
    {
        
    }

}
